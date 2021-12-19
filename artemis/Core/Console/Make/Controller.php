<?php


namespace Artemis\Core\Console\Make;


use Artemis\Core\Console\Traits\hasFileRenderer;
use Artemis\Core\Console\Traits\hasProductionCheck;
use Artemis\Support\FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class Controller extends Command
{
    use hasFileRenderer, hasProductionCheck;

    /**
     * Command name
     * 
     * @var string
     */
    protected static $defaultName = 'make:controller';

    /**
     * Identifier if the controller is going to be a resourceful controller
     *
     * @var bool
     */
    private $is_resource;

    /**
     * Configures the commands arguments
     * 
     * @return void
     */
    public function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the controller');
        $this->addArgument('action', InputArgument::OPTIONAL, '(optional) Action inside of that controller');
        $this->addOption('resource', 'r', InputOption::VALUE_NONE, 'Creates the controller with all resource methods');
        $this->setDescription('Creates a new controller with given name. Optionally a controller action may be defined');
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
        $controller_action = $input->getArgument('action');
        $base_dir = ROOT_PATH . 'app/Http/Controllers';
        $base_namespace = 'App\Http\Controllers';

        $this->is_resource = $input->getOption('resource');

        $name_explode = explode('/', $name);
        $controller_name = array_pop($name_explode);

        $full_path = $base_dir;
        $controller_namespace = $base_namespace;
        foreach( $name_explode as $path_bit ) {
            $full_path .= '/' . $path_bit;
            $controller_namespace .= '\\' . $path_bit;
        }

        $file_path = $full_path . '/' . $controller_name . '.php';

        if( !FileSystem::dirExists($full_path) ) {
            FileSystem::createDir($full_path);
        }

        if( FileSystem::exists($file_path) ) {
            $output->write("Failure: Controller already exists.");
            return Command::FAILURE;
        }

        $file_content = $this->buildFileContent($controller_namespace, $controller_name, $controller_action);
        $file = fopen($file_path, 'w');

        if( false === $file ) {
            $output->write("Failure: Controller could not be created.");
            return Command::FAILURE;
        }

        fwrite($file, $file_content);
        fclose($file);

        $output->write("Success: Controller created.");
        return Command::SUCCESS;
    }

    /**
     * Builds the controller content
     * 
     * @param $controller_namespace
     * @param $controller_name
     * @param $controller_action
     * 
     * @return string
     */
    private function buildFileContent($controller_namespace, $controller_name, $controller_action) : string
    {
        $is_resource = $this->is_resource;
        $data = compact('controller_namespace', 'controller_name', 'controller_action', 'is_resource');

        return $this->render('console.controller', $data);
    }
}