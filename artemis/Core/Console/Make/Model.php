<?php


namespace Artemis\Core\Console\Make;


use Artemis\Support\FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Artemis\Core\Console\Traits\hasProductionCheck;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Model extends Command
{
    use hasProductionCheck;

    /**
     * Command name
     *
     * @var string
     */
    protected static $defaultName = 'make:model';

    /**
     * Identifier if timestamps should be handled by eloquent
     *
     * @var bool
     */
    private $has_timestamps;

    /**
     * Configures the commands arguments
     *
     * @return void
     */
    public function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the model');
        $this->addArgument('connection', InputArgument::REQUIRED, 'Database key for connection');
        $this->addArgument('table', InputArgument::REQUIRED, 'Table name');
        $this->addArgument('pk', InputArgument::REQUIRED, 'Primary key of the table');
        $this->addOption('timestamps', 't', InputOption::VALUE_NONE, 'Whether eloquent should handle timestamp or not');
        $this->setDescription('Creates a new Eloquent Model with given arguments');
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
        $connection = $input->getArgument('connection');
        $table = $input->getArgument('table');
        $pk = $input->getArgument('pk');
        $base_dir = ROOT_PATH . 'app/Models';
        $base_namespace = 'App\Models';

        $this->has_timestamps = $input->getOption('timestamps');

        $name_explode = explode('/', $name);
        $model_name = array_pop($name_explode);

        $full_path = $base_dir;
        $model_namespace = $base_namespace;
        foreach( $name_explode as $path_bit ) {
            $full_path .= '/' . $path_bit;
            $model_namespace .= '\\' . $path_bit;
        }

        $file_path = $full_path . '/' . $model_name . '.php';

        if( !FileSystem::dirExists($full_path) ) {
            FileSystem::createDir($full_path);
        }

        if( FileSystem::exists($file_path) ) {
            $output->write("Failure: Model already exists.");
            return Command::FAILURE;
        }

        $file_content = $this->buildFileContent($model_namespace, $model_name, $connection, $table, $pk);
        $file = fopen($file_path, 'w');

        if( false === $file ) {
            $output->write("Failure: Model could not be created.");
            return Command::FAILURE;
        }

        fwrite($file, $file_content);
        fclose($file);

        $output->write("Success: Model created.");
        return Command::SUCCESS;
    }

    /**
     * Builds the controller content
     *
     * @param $model_namespace
     * @param $model_name
     * @param $connection
     * @param $table
     * @param $pk
     *
     * @return string
     */
    private function buildFileContent($model_namespace, $model_name, $connection, $table, $pk) : string
    {
        if( $this->has_timestamps )
            return "<?php\n\n\nnamespace ".$model_namespace.";\n\n\nuse Artemis\Client\Eloquent;\n\n\nclass ".$model_name." extends Eloquent\n{\n    public \$connection = '".$connection."';\n    protected \$table = '".$table."';\n    protected \$primaryKey = '".$pk."';\n}" . PHP_EOL;

        return "<?php\n\n\nnamespace ".$model_namespace.";\n\n\nuse Artemis\Client\Eloquent;\n\n\nclass ".$model_name." extends Eloquent\n{\n    public \$connection = '".$connection."';\n    protected \$table = '".$table."';\n    protected \$primaryKey = '".$pk."';\n    public \$timestamps = false;\n}" . PHP_EOL;
    }
}