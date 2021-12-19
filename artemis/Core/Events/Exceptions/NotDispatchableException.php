<?php


namespace Artemis\Core\Events\Exceptions;


use Artemis\Core\Events\Traits\Dispatchable;
use Throwable;

class NotDispatchableException extends \Exception
{
    public function __construct($event, $message = "", $code = 0, Throwable $previous = null)
    {
        $dispatchable = Dispatchable::class;
        $event = $this->getEventName($event);

        if( empty($message) ) {
            $message = "Event $event does not use the $dispatchable trait";
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * @param $event
     *
     * @return string
     */
    private function getEventName($event)
    {
        if( is_object($event) ) {
            return get_class($event);
        }

        if( is_string($event) && class_exists($event) ) {
            return $event;
        }

        return 'EVENT NOT RECOGNIZED';
    }
}