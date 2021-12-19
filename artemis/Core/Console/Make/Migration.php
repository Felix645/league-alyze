<?php


namespace Artemis\Core\Console\Make;


use Artemis\Support\FileSystem;
use Symfony\Component\Console\Command\Command;
use Artemis\Core\Console\Traits\hasFileRenderer;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Artemis\Core\Console\Traits\hasProductionCheck;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Migration extends Command
{
    use hasFileRenderer, hasProductionCheck;

    /**
     * Command name
     *
     * @var string
     */
    protected static $defaultName = 'make:migration';

    /**
     * Configures the commands arguments
     *
     * @return void
     */
    public function configure()
    {
        $this->addArgument('database', InputArgument::REQUIRED, 'Database key.');
        $this->addArgument('name', InputArgument::REQUIRED, 'Migration name. Name MUST be in snake-case!');
        $this->addOption('users', 'u', InputOption::VALUE_NONE, 'Creates a framework users table migration.');
        $this->addOption('ldap', 'l', InputOption::VALUE_NONE, 'Creates a framework ldap_settings table migration.');
        $this->addOption('users-mech', 'm', InputOption::VALUE_NONE, 'Creates a framework users_auth_mech table migration.');
        $this->addOption('settings', 's', InputOption::VALUE_NONE, 'Creates a framework settings table migration.');
        $this->addOption('api', 'a', InputOption::VALUE_NONE, 'Creates a framework api_auth table migration.');
        $this->setDescription('Creates a new migration');
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
        $migration = $database . '_' . $input->getArgument('name');

        $name_exploded = explode('_', $migration);
        $class_name = '';

        foreach($name_exploded as $name_part) {
            $class_name .= ucfirst($name_part);
        }

        $timestamp = dateTime()->now()->format('Y_m_d_His-')->get();
        $migration_name = $timestamp . $migration;

        $dir = ROOT_PATH . 'database/migrations/' . $database;
        $file = $dir . '/' . $migration_name . '.php';

        $data = compact('class_name', 'database');
        $file_content = $this->render($this->getView($input), $data);

        if( !FileSystem::dirExists($dir) ) {
            FileSystem::createDir($dir);
        }

        if( FileSystem::exists($file) ) {
            $output->writeln("Failure: Migration already exists.");
            return Command::FAILURE;
        }

        $file = fopen($file, 'w');

        if( false === $file ) {
            $output->writeln("Failure: Migration could not be created.");
            return Command::FAILURE;
        }

        fwrite($file, $file_content);
        fclose($file);

        $output->writeln("Success: Migration created.");
        return Command::SUCCESS;
    }

    /**
     * Gets the view needed for the specifications.
     *
     * @param InputInterface $input
     *
     * @return string
     */
    private function getView(InputInterface $input)
    {
        if( $input->getOption('users') ) {
            return 'console.migration-users';
        }

        if( $input->getOption('ldap') ) {
            return 'console.migration-ldap';
        }

        if( $input->getOption('users-mech') ) {
            return 'console.migration-users-mech';
        }

        if( $input->getOption('settings') ) {
            return 'console.migration-settings';
        }

        if( $input->getOption('api') ) {
            return 'console.migration-api-auth';
        }

        return 'console.migration';
    }
}