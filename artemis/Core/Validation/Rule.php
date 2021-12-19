<?php


namespace Artemis\Core\Validation;


use Artemis\Core\Validation\Exceptions\ValidationException;


abstract class Rule
{
    /**
     * The Field Object
     * 
     * @var Field
     */
    public $field;

    /**
     * Parameters for the rule
     * 
     * @var array
     */
    protected $params = [];

    /**
     * Haystack, holds the data which is to be validated
     * 
     * @var array
     */
    protected $haystack = [];

    /**
     * Returns the validation message.
     *
     * @return string
     */
    public function message()
    {
        return 'Validation failed';
    }

    /**
     * Sets the Field object
     * 
     * @param Field $field
     */
    final public function setField(Field $field)
    {
        $this->field = $field;
    }

    /**
     * Sets the haystack against validation will be running
     * 
     * @param array $haystack
     * 
     * @return void
     */
    final public function setHaystack(array $haystack)
    {
        $this->haystack = $haystack;
    }

    /**
     * Gets a value from the haystack
     * 
     * @param string $key
     * 
     * @return mixed
     */
    final public function getValue($key)
    {
        return $this->haystack[$key] ?? null;
    }

    /**
     * Gets the length of a value
     * 
     * @param string $value
     * 
     * @return int
     */
    final public function getValueSize($value)
    {
        return strlen($this->getValue($value));
    }

    /**
     * Checks if required parameters are present
     * 
     * @param array $params
     * @throws ValidationException
     * 
     * @return void
     */
    final protected function requireParams($params)
    {
        $field_params = $this->field->getAllAttributes();
        $field_name = $this->field->getFieldName();
        
        foreach( $params as $param ) {
            if( !isset( $field_params[$param] ) ) {
                $message = "Missing parameter for rule '$param' on field '$field_name'";
                throw new ValidationException($message);
            }
        }
    }

    /**
     * Checks if given field exists
     *
     * @return bool
     */
    final protected function fieldExists()
    {
        return isset($this->haystack[$this->field->getFieldName()]);
    }

    /**
     * Checks if the given field is empty.
     *
     * @param string $field_key
     *
     * @return bool
     */
    final protected function fieldIsEmpty($field_key)
    {
        return !isset($this->haystack[$field_key]) || $this->haystack[$field_key] == null;
    }

    /**
     * Implementation for the rule check
     * 
     * @param Field $field
     * 
     * @return bool
     */
    abstract public function check($field);
}