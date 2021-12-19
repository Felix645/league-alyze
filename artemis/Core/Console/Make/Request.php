<?php


namespace Artemis\Core\Console\Make;


use Artemis\Support\FileSystem;
use Symfony\Component\Console\Command\Command;
use Artemis\Core\Console\Traits\hasFileRenderer;
use Symfony\Component\Console\Input\InputArgument;
use Artemis\Core\Console\Traits\hasProductionCheck;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Request extends Command
{
    use hasFileRenderer, hasProductionCheck;

    /**
     * Command name
     * 
     * @var string
     */
    protected static $defaultName = 'make:request';

    /**
     * Configures the commands arguments
     * 
     * @return void
     */
    public function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the request');
        $this->setDescription('Creates a new FormRequest.');
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
        $base_dir = ROOT_PATH . 'app/Http/Requests';
        $base_namespace = 'App\Http\Requests';

        $name_explode = explode('/', $name);
        $request_name = array_pop($name_explode);

        $full_path = $base_dir;
        $request_namespace = $base_namespace;
        foreach( $name_explode as $path_bit ) {
            $full_path .= '/' . $path_bit;
            $request_namespace .= '\\' . $path_bit;
        }

        $file_path = $full_path . '/' . $request_name . '.php';

        if( !FileSystem::dirExists($full_path) ) {
            FileSystem::createDir($full_path);
        }

        if( FileSystem::exists($file_path) ) {
            $output->write("Failure: Request already exists.");
            return Command::FAILURE;
        }

        $file_content = $this->buildFileContent($request_namespace, $request_name);
        $file = fopen($file_path, 'w');

        if( false === $file ) {
            $output->write("Failure: Request could not be created.");
            return Command::FAILURE;
        }

        fwrite($file, $file_content);
        fclose($file);

        $output->write("Success: Request created.");
        return Command::SUCCESS;
    }

    /**
     * Builds the request content
     * 
     * @param string $request_namespace
     * @param string $request_name
     * 
     * @return string
     */
    private function buildFileContent(string $request_namespace, string $request_name) : string
    {
        $data = compact('request_namespace', 'request_name');

        return $this->render('console.request', $data);
    }
}