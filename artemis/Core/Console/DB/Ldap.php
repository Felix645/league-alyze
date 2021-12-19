<?php


namespace Artemis\Core\Console\DB;


use Artemis\Core\Console\Traits\hasMakeMigrationCall;
use Artemis\Core\Console\Traits\hasProductionCheck;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Ldap extends Command
{
    use hasProductionCheck, hasMakeMigrationCall;

    /**
     * Command name
     *
     * @var string
     */
    protected static $defaultName = 'db:ldap';

    /**
     * Configures the commands arguments
     *
     * @return void
     */
    public function configure()
    {
        $this->addArgument('database', InputArgument::REQUIRED, 'Database key to be used');
        $this->setDescription('Creates a new migration for the framework ldap tables.');
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

        $database = $input->getArgument('database');

        $arguments = [
            'database'  => $database,
            'name'      => 'create_framework_ldap_settings_table',
            '--ldap'    => true
        ];

        if( Command::FAILURE === $this->makeMigration($arguments, $output) ) {
            return Command::FAILURE;
        }

        sleep(1);

        $arguments = [
            'database'  => $database,
            'name'      => 'create_framework_users_auth_mech_table',
            '--users-mech'    => true
        ];

        return $this->makeMigration($arguments, $output);
    }
}