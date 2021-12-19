<?php


namespace Artemis\Core\Validation\Rules;


use Artemis\Core\Validation\Exceptions\ValidationException;
use Artemis\Core\Validation\Field;
use Artemis\Core\Validation\Rule;


class Defaults extends Rule
{
    /**
     * Required parameters
     *
     * @var array
     */
    protected $params = ['default'];

    /**
     * Sets a default value if the field is not present or empty
     *
     * @param Field $field
     * @throws ValidationException
     *
     * @return true
     */
    public function check($field)
    {
        $this->setField($field);
        $this->requireParams($this->params);

        if( $this->fieldExists() && !empty($this->haystack[$field->getFieldName()]) )
            return true;

        $default = $this->handleDefaultValues($this->field->getAttribute('default'));
        container('request')->addToBody($this->field->getFieldName(), $default);

        $this->haystack[$field->getFieldName()] = $default;
        container('validation')->updateHaystack($this->haystack);

        return true;
    }

    /**
     * Converts some default parameters into different data types
     *
     * @param string|null $value
     *
     * @return bool|string|null
     */
    public function handleDefaultValues($value)
    {
        if( 'null' === $value || 'NULL' === $value )
            return null;

        if( 'true' === $value || 'TRUE' === $value )
            return true;

        if( 'false' === $value || 'FALSE' === $value )
            return false;

        return $value;
    }
}