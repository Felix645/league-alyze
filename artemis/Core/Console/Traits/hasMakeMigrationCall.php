<?php


namespace Artemis\Core\Console\Traits;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;


trait hasMakeMigrationCall
{
    /**
     * Calls the make:migration command with given arguments
     *
     * @param array $arguments
     * @param OutputInterface $output
     *
     * @return int
     */
    public function makeMigration(array $arguments, OutputInterface $output) : int
    {
        $command = $this->getApplication()->find('make:migration');

        try {
            $migration_input = new ArrayInput($arguments);

            return $command->run($migration_input, $output);
        } catch( \Exception $e ) {
            $output->writeln('Failure: Migration could not be created.');
            return Command::FAILURE;
        }
    }
}