<?php


namespace Artemis\Core\Http\Interfaces\Request;


interface HasValidationInterface
{
    /**
     * Validates a request based on the rule defined in the rules() method
     *
     * @param array $rules
     * @param array $body
     *
     * @return void
     */
    public function validate($rules, $body = []);

    /**
     * Gets the validated request variables
     *
     * @return array
     */
    public function validated();

    /**
     * Adds a key/value pair to the validated array
     *
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    public function addToValidated($key, $value);
}