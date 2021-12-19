<?php


namespace Artemis\Core\Console\Cache;


use Artemis\Support\FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class Clear extends Command
{
    /**
     * Path to the cached view templates.
     *
     * @var string
     */
    private const CACHE_VIEW_PATH = ROOT_PATH . 'cache/views';

    /**
     * Path to cached data.
     *
     * @var string
     */
    private const CACHE_DATA_PATH = ROOT_PATH . 'cache/data';

    /**
     * Command name
     *
     * @var string
     */
    protected static $defaultName = 'cache:clear';

    /**
     * Configures the commands arguments
     *
     * @return void
     */
    public function configure()
    {
        $this->setDescription('Clears the whole cache');
    }

    /**
     * Command execution
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if( FileSystem::dirExists(self::CACHE_VIEW_PATH) ) {
            FileSystem::clearDir(self::CACHE_VIEW_PATH);
        }

        if( FileSystem::dirExists(self::CACHE_DATA_PATH) ) {
            FileSystem::clearDir(self::CACHE_DATA_PATH);
        }

        $output->write("Success: cache cleared.");
        return Command::SUCCESS;
    }
}