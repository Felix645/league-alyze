<?php


namespace Artemis\Core\Console\Make;


use Artemis\Core\Console\Traits\hasFileRenderer;
use Artemis\Core\Console\Traits\hasProductionCheck;
use Artemis\Support\FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Listener extends Command
{
    use hasFileRenderer, hasProductionCheck;

    /**
     * Command name
     *
     * @var string
     */
    protected static $defaultName = 'make:listener';

    /**
     * Configures the commands arguments
     *
     * @return void
     */
    public function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the Listener');
        $this->addArgument('event', InputArgument::REQUIRED, 'Name of the Event');
        $this->setDescription('Creates a new Listener.');
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

        $name = $input->getArgument('name');
        $event = $input->getArgument('event');
        $base_dir = ROOT_PATH . 'app/Listeners';
        $base_namespace = 'App\Listeners';

        $name_explode = explode('/', $name);
        $listener_name = array_pop($name_explode);

        $full_path = $base_dir;
        $listener_namespace = $base_namespace;
        foreach( $name_explode as $path_bit ) {
            $full_path .= '/' . $path_bit;
            $listener_namespace .= '\\' . $path_bit;
        }

        $file_path = $full_path . '/' . $listener_name . '.php';

        if( !FileSystem::dirExists($full_path) ) {
            FileSystem::createDir($full_path);
        }

        if( FileSystem::exists($file_path) ) {
            $output->write("Failure: Listener already exists.");
            return Command::FAILURE;
        }

        $file_content = $this->buildFileContent($listener_namespace, $listener_name, $this->getEventName($event));
        $file = fopen($file_path, 'w');

        if( false === $file ) {
            $output->write("Failure: Listener could not be created.");
            return Command::FAILURE;
        }

        fwrite($file, $file_content);
        fclose($file);

        $output->write("Success: Listener created.");
        return Command::SUCCESS;
    }

    /**
     * Builds the request content
     *
     * @param string $listener_namespace
     * @param string $listener_name
     * @param string $event
     *
     * @return string
     */
    private function buildFileContent(string $listener_namespace, string $listener_name, string $event) : string
    {
        $data = compact('listener_namespace', 'listener_name', 'event');

        return $this->render('console.listener', $data);
    }

    /**
     * Gets the full event name of given event.
     *
     * @param $event
     *
     * @return string
     */
    private function getEventName($event)
    {
        return '\App\Events\\' . str_replace('/', '\\', $event);
    }
}