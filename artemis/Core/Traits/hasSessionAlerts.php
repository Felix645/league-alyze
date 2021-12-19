<?php


namespace Artemis\Core\Traits;


trait hasSessionAlerts
{
    /**
     * Adds a success message to the session
     *
     * @param string $key
     * @param string $message
     *
     * @return void
     */
    private function setSuccess($key, $message)
    {
        container('session')->addAlert('success', $key, $message);
    }

    /**
     * Adds a error message to the session
     *
     * @param string $key
     * @param string $message
     *
     * @return void
     */
    private function setError($key, $message)
    {
        container('session')->addAlert('error', $key, $message);
    }
}