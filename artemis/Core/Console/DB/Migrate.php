<?php


namespace Artemis\Core\Console\DB;


use Artemis\Core\Console\Traits\hasProductionCheck;
use Artemis\Core\Database\Migration\Migrator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class Migrate extends Command
{
    use hasProductionCheck;

    /**
     * Command name
     *
     * @var string
     */
    protected static $defaultName = 'db:migrate';

    /**
     * Configures the commands arguments
     *
     * @return void
     */
    public function configure()
    {
        $this->addArgument('database', InputArgument::REQUIRED, 'Database key to be used');
        $this->addOption('rollback', 'r', InputOption::VALUE_NONE, 'Performs a rollback on the migration');
        $this->addOption('status', 's', InputOption::VALUE_NONE, 'Gets the status of all tracked migrations.');
        $this->addOption('fresh', 'f', InputOption::VALUE_NONE, 'Performs a fresh migration.');
        $this->setDescription('Migration CLI.');
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

        $status = $input->getOption('status');
        $fresh = $input->getOption('fresh');
        $rollback = $input->getOption('rollback');

        $migrator = new Migrator($input->getArgument('database'), $input, $output);

        try {
            if( $status ) {
                $migrator->status();
                return Command::SUCCESS;
            }

            if( $fresh ) {
                $migrator->fresh();
                return Command::SUCCESS;
            }

            if( $rollback ) {
                $message = $migrator->rollback();
                $output->writeln($message);
                return Command::SUCCESS;
            }

            $message = $migrator->migrate();
            $output->writeln($message);
            return Command::SUCCESS;
        } catch( \Exception $e ) {
            $output->writeln($e->getMessage());
            return Command::FAILURE;
        }
    }
}