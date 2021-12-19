<?php


namespace Artemis\Core\Console\Maintenance;


use Artemis\Core\Maintenance\Maintenance;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Up extends Command
{
    /**
     * Command name
     *
     * @var string
     */
    protected static $defaultName = 'app:up';

    /**
     * Configures the commands arguments
     *
     * @return void
     */
    public function configure()
    {
        $this->setDescription('Disables application maintenance mode');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Maintenance::disable();

        $output->write("Success: App is no longer in maintenance mode.");
        return Command::SUCCESS;
    }
}