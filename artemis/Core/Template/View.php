<?php


namespace Artemis\Core\Template;


use Artemis\Support\Arr;
use Artemis\Client\Facades\Template;
use Exception;


class View
{
    /**
     * The view path
     * 
     * @var string
     */
    private $view = '';

    /**
     * The view data
     * 
     * @var array
     */
    private $data = [];

    /**
     * The shared view data
     * 
     * @var array
     */
    private $shared_data = [];

    /**
     * Sets shared view data
     * 
     * @param array $share_data
     * 
     * @return void
     */
    public function share($share_data)
    {
        $this->shared_data = Arr::merge($this->shared_data, $share_data);
    }

    /**
     * Sets the view path
     * 
     * @param string $view
     * 
     * @return $this
     */
    public function setView($view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * Sets the view data
     * 
     * @param array $data
     * 
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Renders the view
     * 
     * @param void
     * 
     * @return string
     */
    public function render()
    {
        try {
            if( !empty($this->shared_data) )
                Template::addSharedViewData($this->shared_data);

            return Template::renderView($this->view, $this->data);
        } catch(\Throwable $e) {
            report($e);
            exit;
        }
    }
}