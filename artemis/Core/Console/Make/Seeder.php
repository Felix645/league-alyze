<?php


namespace Artemis\Core\Console\Make;


use Artemis\Support\FileSystem;
use Symfony\Component\Console\Command\Command;
use Artemis\Core\Console\Traits\hasFileRenderer;
use Symfony\Component\Console\Input\InputArgument;
use Artemis\Core\Console\Traits\hasProductionCheck;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Seeder extends Command
{
    use hasFileRenderer, hasProductionCheck;

    /**
     * Command name
     *
     * @var string
     */
    protected static $defaultName = 'make:seeder';

    /**
     * Configures the commands arguments
     *
     * @return void
     */
    public function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the seeder');
        $this->setDescription('Creates a new seeder.');
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
        $base_dir = ROOT_PATH . 'database/seeds';
        $base_namespace = 'Database\Seeds';

        $name_explode = explode('/', $name);
        $seeder_name = array_pop($name_explode);

        $full_path = $base_dir;
        $seeder_namespace = $base_namespace;
        foreach( $name_explode as $path_bit ) {
            $full_path .= '/' . $path_bit;
            $seeder_namespace .= '\\' . $path_bit;
        }

        $file_path = $full_path . '/' . $seeder_name . '.php';

        if( !FileSystem::dirExists($full_path) ) {
            FileSystem::createDir($full_path);
        }

        if( FileSystem::exists($file_path) ) {
            $output->write("Failure: Seeder already exists.");
            return Command::FAILURE;
        }

        $file_content = $this->buildFileContent($seeder_namespace, $seeder_name);
        $file = fopen($file_path, 'w');

        if( false === $file ) {
            $output->write("Failure: Seeder could not be created.");
            return Command::FAILURE;
        }

        fwrite($file, $file_content);
        fclose($file);

        $output->write("Success: Seeder created.");
        return Command::SUCCESS;
    }

    /**
     * Builds the mail content
     *
     * @param string $seeder_namespace
     * @param string $seeder_name
     *
     * @return string
     */
    private function buildFileContent(string $seeder_namespace, string $seeder_name) : string
    {
        $data = compact('seeder_namespace', 'seeder_name');

        return $this->render('console.seeder', $data);
    }
}