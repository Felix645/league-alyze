<?php


namespace Artemis\Core\Console\Make;


use Artemis\Support\FileSystem;
use Symfony\Component\Console\Command\Command;
use Artemis\Core\Console\Traits\hasFileRenderer;
use Symfony\Component\Console\Input\InputArgument;
use Artemis\Core\Console\Traits\hasProductionCheck;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Mail extends Command
{
    use hasFileRenderer, hasProductionCheck;

    /**
     * Command name
     * 
     * @var string
     */
    protected static $defaultName = 'make:mail';

    /**
     * Configures the commands arguments
     * 
     * @return void
     */
    public function configure()
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Name of the mail');
        $this->setDescription('Creates a new mail.');
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
        $base_dir = ROOT_PATH . 'app/Mail';
        $base_namespace = 'App\Mail';

        $name_explode = explode('/', $name);
        $mail_name = array_pop($name_explode);

        $full_path = $base_dir;
        $mail_namespace = $base_namespace;
        foreach( $name_explode as $path_bit ) {
            $full_path .= '/' . $path_bit;
            $mail_namespace .= '\\' . $path_bit;
        }

        $file_path = $full_path . '/' . $mail_name . '.php';

        if( !FileSystem::dirExists($full_path) ) {
            FileSystem::createDir($full_path);
        }

        if( FileSystem::exists($file_path) ) {
            $output->write("Failure: Mail already exists.");
            return Command::FAILURE;
        }

        $file_content = $this->buildFileContent($mail_namespace, $mail_name);
        $file = fopen($file_path, 'w');

        if( false === $file ) {
            $output->write("Failure: Mail could not be created.");
            return Command::FAILURE;
        }

        fwrite($file, $file_content);
        fclose($file);

        $output->write("Success: Mail created.");
        return Command::SUCCESS;
    }

    /**
     * Builds the mail content
     * 
     * @param string $mail_namespace
     * @param string $mail_name
     * 
     * @return string
     */
    private function buildFileContent(string $mail_namespace, string $mail_name) : string
    {
        $data = compact('mail_namespace', 'mail_name');

        return $this->render('console.mail', $data);
    }
}