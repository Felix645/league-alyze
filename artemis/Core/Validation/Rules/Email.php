<?php


namespace Artemis\Core\Validation\Rules;


use Artemis\Core\Validation\Rule;
use Artemis\Core\Validation\Field;


class Email extends Rule 
{
    /**
     * Checks if field is a valid email
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

        return false !== filter_var($this->getValue($this->field->getFieldName()), FILTER_VALIDATE_EMAIL);
    }
}