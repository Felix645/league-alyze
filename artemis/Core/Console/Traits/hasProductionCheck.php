<?php


namespace Artemis\Core\Console\Traits;


use Symfony\Component\Console\Output\OutputInterface;


trait hasProductionCheck
{
    /**
     * Checks if app is in debug mode.
     *
     * @param OutputInterface $output
     *
     * @return bool
     */
    private function checkProduction(OutputInterface $output) : bool
    {
        if( !app()->debug() ) {
            $output->writeln("Failure: Kronos commands are not available while in production mode!");
            return false;
        }

        return true;
    }
}