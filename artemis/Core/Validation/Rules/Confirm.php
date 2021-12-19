<?php


namespace Artemis\Core\Validation\Rules;


use Artemis\Core\Validation\Rule;
use Artemis\Core\Validation\Field;


class Confirm extends Rule 
{
    /**
     * Checks if a confirm field is present
     * 
     * @param Field $field
     * 
     * @return bool
     */
    public function check($field)
    {
        $this->setField($field);

        $field_name = $this->field->getFieldName();
        $field_name_confirm = $field_name . '_confirm';

        if( !$this->fieldExists() )
            return !$this->field->isRequired();

        $value = $this->getValue($field_name);
        $value_confirm = $this->getValue($field_name_confirm);

        if( is_null($value_confirm) ) {
            return false;
        }

        return $value === $value_confirm;
    }
}