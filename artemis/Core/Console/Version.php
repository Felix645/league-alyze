<?php


namespace Artemis\Core\Console;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Version extends Command
{
    /**
     * Command name
     *
     * @var string
     */
    protected static $defaultName = 'version';

    /**
     * Configures the commands arguments
     *
     * @return void
     */
    public function configure()
    {
        $this->setDescription('Gets the current framework version.');
    }

    /**
     * Executes the command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output) : int
    {
        $output->write("Current Framework Build: " . app()->version());
        return Command::SUCCESS;
    }
}