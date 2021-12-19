<?php


namespace Artemis\Core\Template;


use Artemis\Resource\Extensions\CustomBladeExtension;
use Exception;


class Template
{
    /**
     * Path where the views will be found
     *
     * @var string
     */
    private $view_path = ROOT_PATH . 'app/Views';

    /**
     * Path for the view cache
     *
     * @var string
     */
    private $cache_path = ROOT_PATH . 'cache/views';

    /**
     * Starts the view rendering process
     * 
     * @param string $view
     * @param array $data
     * @throws Exception
     * 
     * @return string
     */
    public function renderView($view, $data = [])
    {
        DataStorage::addToData($data);
        $data = DataStorage::getData();

        $blade = new CustomBladeExtension($this->view_path, $this->cache_path);
        return $blade->run($view, $data['view_data'] ?? []);
    }
}