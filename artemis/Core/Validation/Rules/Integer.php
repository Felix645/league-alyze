<?php


namespace Artemis\Core\Validation\Rules;


use Artemis\Core\Validation\Rule;
use Artemis\Core\Validation\Field;


class Integer extends Rule 
{
    /**
     * Checks if given field is an integer
     * 
     * @param Field $field
     * 
     * @return bool
     */
    public function check($field)
    {
        $this->setField($field);
        $this->field->setNumeric(true);

        if( !$this->fieldExists() )
            return !$this->field->isRequired();

        return false !== filter_var($this->getValue($this->field->getFieldName()), FILTER_VALIDATE_INT);
    }
}