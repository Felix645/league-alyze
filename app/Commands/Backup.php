<?php

namespace App\Commands;

use App\Models\Game;
use Artemis\Client\Facades\Hash;
use Artemis\Support\Arr;
use Artemis\Support\Exceptions\Json\InvalidJsonException;
use Artemis\Support\FileSystem;
use Artemis\Support\Json;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Backup extends Command
{

    /**
     * Command name
     *
     * @var string
     */
    protected static $defaultName = 'custom:backup';

    private string $seperator = '-------------------------------------------------------------';

    private string $backup_log_path;

    public function __construct()
    {
        parent::__construct();
        $this->backup_log_path = app()->root() . 'storage/backup/backups.json';
    }

    /**
     * Configures the commands arguments
     *
     * @return void
     */
    public function configure()
    {
        $this->addOption('restore', 'r', InputOption::VALUE_NONE, 'Restores the backup');
        $this->setDescription('Backups the current database state into a json file.');
    }

    public function execute(InputInterface $input, OutputInterface $output) : int
    {
        $should_restore = $input->getOption('restore');

        try {
            if( $should_restore ) {
                $output->writeln('Restoring backup ...');
                $output->writeln($this->seperator);

                $this->restoreBackup($output);

                $output->writeln('Backup restored!');
            } else {
                $output->writeln('Starting backup ...');
                $output->writeln($this->seperator);

                $this->performBackup($output);

                $output->writeln('Backup complete!');
            }
        } catch( Exception $e) {
            $output->writeln($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * @param OutputInterface $output
     * @throws InvalidJsonException
     */
    private function performBackup(OutputInterface $output)
    {
        $output->writeln('Reading matches ...');

        $matches = Game::all()->makeHidden('id')->toArray();

        $output->writeln('Match reading complete!');
        $output->writeln($this->seperator);

        $file_name = dateTime()->format('Y-m-d_H-i-s') . '_' . Hash::uuid() . '.json';

        if( !FileSystem::dirExists(app()->root() . 'storage/backup/matches') ) {
            $output->writeln('Creating backup directory ...');

            FileSystem::createDir(app()->root() . 'storage/backup/matches');

            $output->writeln('Backup directory created!');
            $output->writeln($this->seperator);
        }

        $output->writeln('Writing new backup file ...');

        Json::writeJsonFile($matches, app()->root() . 'storage/backup/matches/' . $file_name);

        $output->writeln('New backup file created!');
        $output->writeln($this->seperator);

        if( !FileSystem::exists($this->backup_log_path) ) {
            $output->writeln('Creating new backup log ...');
            $data = [];
            $data[] = $file_name;
            $output->writeln('New backup log created!');
            $output->writeln($this->seperator);

            $output->writeln('Updating backup log file ...');
        } else {
            $output->writeln('Reading backup log ...');
            $data = Json::jsonFileContent($this->backup_log_path);
            $output->writeln('Backup log found!');
            $output->writeln($this->seperator);

            $output->writeln('Updating backup log file ...');
            $last_key = array_key_last($data);
            $data[$last_key + 1] = $file_name;
        }

        Json::writeJsonFile($data, $this->backup_log_path, JSON_PRETTY_PRINT);

        $output->writeln('Backup log file updated!');
        $output->writeln($this->seperator);
    }

    /**
     * @param OutputInterface $output
     * @throws Exception
     */
    private function restoreBackup(OutputInterface $output)
    {
        if( !FileSystem::exists($this->backup_log_path) ) {
            throw new Exception('Failure: No backup log present!');
        }

        $output->writeln('Reading backup log ...');

        $log = Json::jsonFileContent($this->backup_log_path);

        $output->writeln('Backup log reading complete!');
        $output->writeln($this->seperator);


        $output->writeln('Reading latest backup ...');

        $latest_backup = $log[array_key_last($log)];
        $backup_path = app()->root() . 'storage/backup/matches/' . $latest_backup;

        $output->writeln('Latest backup found!');
        $output->writeln($this->seperator);


        if( !FileSystem::exists($backup_path) ) {
            throw new Exception('Failure: Backup ' . $latest_backup . ' does not exist!');
        }

        $output->writeln('Reading backup data ...');

        $data = Json::jsonFileContent($backup_path);

        $output->writeln('Backup data read!');
        $output->writeln($this->seperator);


        $output->writeln('Truncating matches table ...');

        Game::query()->truncate();

        $output->writeln('Truncate complete!');
        $output->writeln($this->seperator);


        $output->writeln('Inserting backup data!');

        Game::query()->insert($data);

        $output->writeln('Insert complete!');
        $output->writeln($this->seperator);
    }
}