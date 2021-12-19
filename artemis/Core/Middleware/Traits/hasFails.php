<?php


namespace Artemis\Core\Middleware\Traits;


use Artemis\Core\Http\ResponseHandler;

trait hasFails
{
    use hasErrorPage;

    /**
     * Callback function to be executed if middleware fails.
     *
     * @var \Closure|null
     */
    private $fails;

    /**
     * Adds a callback function to be executed when the middleware fails.
     *
     * @param \Closure $callback    Callback function to be executed if middleware fails.
     *                              If none is provided an error page will be displayed instead.
     * 
     * @return $this
     */
    public function addFails($callback)
    {
        $this->fails = $callback;
        return $this;
    }

    /**
     * Tries to execute the fails property.
     *
     * @return void
     */
    private function executeFails()
    {
        if( is_callable($this->fails) ) {
            ResponseHandler::new(container()->getCallback($this->fails));
        }
    }

    /**
     * Logic to be executed when the middleware fails.
     *
     * @return void
     */
    private function middlewareFails()
    {
        $this->executeFails();
        $this->displayErrorPage();
    }
}