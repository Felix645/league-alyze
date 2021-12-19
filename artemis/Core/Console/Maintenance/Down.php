<?php


namespace Artemis\Core\Console\Maintenance;


use Artemis\Support\Str;
use Artemis\Core\Maintenance\Maintenance;
use Artemis\Core\Providers\RouteServiceProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Down extends Command
{
    /**
     * Command name
     *
     * @var string
     */
    protected static $defaultName = 'app:down';

    /**
     * Configures the commands arguments
     *
     * @return void
     */
    public function configure()
    {
        $this->setDescription('Sets application in maintenance mode');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Maintenance::enable();

        $route = RouteServiceProvider::MAINTENANCE_SECRET_ROUTE;
        $route = Str::replace('{secret}', Maintenance::secret(), $route);

        $output->writeln("Success: App is now in maintenance mode.");
        $output->writeln("Bypass URL: " . env('APP_DOMAIN') . $route);
        $output->writeln("Bypass secret: " . Maintenance::secret());

        return Command::SUCCESS;
    }
}