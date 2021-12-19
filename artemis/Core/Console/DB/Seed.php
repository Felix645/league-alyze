<?php


namespace Artemis\Core\Console\DB;


use Artemis\Core\Console\Traits\hasFileRenderer;
use Artemis\Core\Console\Traits\hasProductionCheck;
use Artemis\Core\Database\Seeder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;


class Seed extends Command
{
    use hasFileRenderer, hasProductionCheck;

    /**
     * Command name
     *
     * @var string
     */
    protected static $defaultName = 'db:seed';

    /**
     * Configures the commands arguments
     *
     * @return void
     */
    public function configure()
    {
        $this->addArgument('seeder', InputArgument::REQUIRED, 'Seeder to be executed');
        $this->setDescription('Execute the given Seeder.');
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

        $seeder = $input->getArgument('seeder');

        $base_namespace = '\Database\Seeds\\';

        $seeder_class = $base_namespace . str_replace('/', '\\', $seeder);

        if( !is_subclass_of($seeder_class, Seeder::class) ) {
            $output->write("Failure: Seeder does not extend \Artemis\Core\Database\Seeder");
            return Command::FAILURE;
        }

        $stopwatch = new Stopwatch();

        $output->writeln("Start seeding ...");
        $stopwatch->start('seeding');

        try {
            /* @var Seeder $seeder_instance */
            $seeder_instance = container($seeder_class);

            $seeder_instance->run();
        } catch(\Exception $e) {
            $output->write("Failure: Seeding failed, {$e->getMessage()}");
            return Command::FAILURE;
        }

        $event = $stopwatch->stop('seeding');
        $time = number_format(($event->getDuration() / 1000), 2);

        $output->write("Success: Seeding completed. ($time s)");
        return Command::SUCCESS;
    }
}