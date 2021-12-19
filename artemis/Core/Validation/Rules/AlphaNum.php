<?php


namespace Artemis\Core\Validation\Rules;


use Artemis\Core\Validation\Field;
use Artemis\Core\Validation\Rule;
use Artemis\Core\Validation\Traits\hasStringHelpers;


class AlphaNum extends Rule
{
    use hasStringHelpers;

    /**
     * Checks if given field only contains alpha-numerical characters
     *
     * @param Field $field
     *
     * @return bool
     */
    public function check($field)
    {
        $this->setField($field);

        if( !$this->fieldExists() )
            return !$this->field->isRequired();

        $value = $this->castFieldToString($this);

        if( !is_string($value) && !is_numeric($value) )
            return false;

        return $this->validateAlphaNum($value);
    }
}