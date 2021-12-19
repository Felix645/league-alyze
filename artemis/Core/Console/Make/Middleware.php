<?php


namespace Artemis\Core\Console\Make;


use Artemis\Support\FileSystem;
use Symfony\Component\Console\Command\Command;
use Artemis\Core\Console\Traits\hasFileRenderer;
use Symfony\Component\Console\Input\InputArgument;
use Artemis\Core\Console\Traits\hasProductionCheck;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Middleware extends Command
{
    use hasFileRenderer, hasProductionCheck;

    /**
     * Command name
     *
     * @var string
     */
    protected static $defaultName = 'make:middleware';

    /**
     * Configures the commands arguments
     *
     * @return void
     */
    public function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the middleware');
        $this->setDescription('Creates a new middleware with given name.');
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
        $base_dir = ROOT_PATH . 'app/Http/Middlewares';
        $base_namespace = 'App\Http\Middlewares';

        $name_explode = explode('/', $name);
        $middleware_name = array_pop($name_explode);

        $full_path = $base_dir;
        $middleware_namespace = $base_namespace;
        foreach( $name_explode as $path_bit ) {
            $full_path .= '/' . $path_bit;
            $middleware_namespace .= '\\' . $path_bit;
        }

        $file_path = $full_path . '/' . $middleware_name . '.php';

        if( !FileSystem::dirExists($full_path) ) {
            FileSystem::createDir($full_path);
        }

        if( FileSystem::exists($file_path) ) {
            $output->write("Failure: Middleware already exists.");
            return Command::FAILURE;
        }

        $file_content = $this->buildFileContent($middleware_namespace, $middleware_name);
        $file = fopen($file_path, 'w');

        if( false === $file ) {
            $output->write("Failure: Middleware could not be created.");
            return Command::FAILURE;
        }

        fwrite($file, $file_content);
        fclose($file);

        $output->write("Success: Middleware created.");
        return Command::SUCCESS;
    }

    /**
     * Builds the controller content
     *
     * @param $middleware_namespace
     * @param $middleware_name
     *
     * @return string
     */
    private function buildFileContent($middleware_namespace, $middleware_name) : string
    {
        $data = compact('middleware_namespace', 'middleware_name');

        return $this->render('console.middleware', $data);
    }
}