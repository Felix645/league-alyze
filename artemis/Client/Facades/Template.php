<?php


namespace Artemis\Client\Facades;


use Artemis\Core\Template\DataStorage;
use Exception;


class Template 
{
    /**
     * @param string $view
     * @param array $data
     * @throws Exception
     * 
     * @return string
     */
    public static function renderView($view, $data = [])
    {
        return container('template')->renderView($view, $data);
    }

    /**
     * Adds addition view data
     * 
     * @param array $shared_data
     * 
     * @return void
     */
    public static function addSharedViewData($shared_data)
    {
        DataStorage::addSharedViewData($shared_data);
    }
}