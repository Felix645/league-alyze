<?php


namespace Artemis\Core\Console\DB;


use Artemis\Core\Console\Traits\hasProductionCheck;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Framework extends Command
{
    use hasProductionCheck;

    /**
     * Command name
     *
     * @var string
     */
    protected static $defaultName = 'db:framework';

    /**
     * Seperator string.
     *
     * @var string
     */
    private $seperator = '---------------------------------';

    /**
     * Configures the commands arguments
     *
     * @return void
     */
    public function configure()
    {
        $this->addArgument('database', InputArgument::REQUIRED, 'Database key to be used');
        $this->setDescription('Creates a new migrations for all framework tables.');
    }

    /**
     * Command execution
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output) : int
    {
        if( !$this->checkProduction($output) ) {
            return Command::FAILURE;
        }

        $arguments = [
            'database'  => $input->getArgument('database')
        ];

        $output->writeln('Create settings table migration ...');
        if( Command::FAILURE === $this->callCommand('db:settings', $arguments, $output) ) {
            return Command::FAILURE;
        }

        $output->writeln($this->seperator);

        $output->writeln('Create users table migration ...');
        if( Command::FAILURE === $this->callCommand('db:users', $arguments, $output) ) {
            return Command::FAILURE;
        }

        $output->writeln($this->seperator);

        $output->writeln('Create ldap_settings table and users_auth_mech table migrations ...');
        if( Command::FAILURE === $this->callCommand('db:ldap', $arguments, $output) ) {
            return Command::FAILURE;
        }

        $output->writeln($this->seperator);

        $output->writeln('Create api_auth table migration ...');
        return $this->callCommand('db:api', $arguments, $output);
    }

    /**
     * Calls a given command with given arguments
     *
     * @param string $command
     * @param array $arguments
     * @param OutputInterface $output
     *
     * @return int
     */
    private function callCommand(string $command, array $arguments, OutputInterface $output)
    {
        sleep(1);

        $command = $this->getApplication()->find($command);

        try {
            $migration_input = new ArrayInput($arguments);

            return $command->run($migration_input, $output);
        } catch( \Exception $e ) {
            $output->writeln('Failure: Migration could not be created.');
            return Command::FAILURE;
        }
    }
}