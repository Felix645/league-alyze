<?php


namespace Artemis\Core\Console\Make;


use Artemis\Core\Console\Traits\hasFileRenderer;
use Artemis\Core\Console\Traits\hasProductionCheck;
use Artemis\Support\FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Event extends Command
{
    use hasFileRenderer, hasProductionCheck;

    /**
     * Command name
     *
     * @var string
     */
    protected static $defaultName = 'make:event';

    /**
     * Configures the commands arguments
     *
     * @return void
     */
    public function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the event');
        $this->setDescription('Creates a new Event.');
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
        $base_dir = ROOT_PATH . 'app/Events';
        $base_namespace = 'App\Events';

        $name_explode = explode('/', $name);
        $event_name = array_pop($name_explode);

        $full_path = $base_dir;
        $event_namespace = $base_namespace;
        foreach( $name_explode as $path_bit ) {
            $full_path .= '/' . $path_bit;
            $event_namespace .= '\\' . $path_bit;
        }

        $file_path = $full_path . '/' . $event_name . '.php';

        if( !FileSystem::dirExists($full_path) ) {
            FileSystem::createDir($full_path);
        }
            

        if( FileSystem::exists($file_path) ) {
            $output->write("Failure: Event already exists.");
            return Command::FAILURE;
        }

        $file_content = $this->buildFileContent($event_namespace, $event_name);
        $file = fopen($file_path, 'w');

        if( false === $file ) {
            $output->write("Failure: Event could not be created.");
            return Command::FAILURE;
        }

        fwrite($file, $file_content);
        fclose($file);

        $output->write("Success: Event created.");
        return Command::SUCCESS;
    }

    /**
     * Builds the request content
     *
     * @param string $event_namespace
     * @param string $event_name
     *
     * @return string
     */
    private function buildFileContent(string $event_namespace, string $event_name) : string
    {
        $data = compact('event_namespace', 'event_name');

        return $this->render('console.event', $data);
    }
}