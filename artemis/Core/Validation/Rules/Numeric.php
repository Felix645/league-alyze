<?php


namespace Artemis\Core\Validation\Rules;


use Artemis\Core\Validation\Rule;
use Artemis\Core\Validation\Field;
use Artemis\Core\Validation\Traits\hasNumberFormats;


class Numeric extends Rule 
{
    use hasNumberFormats;

    /**
     * Checks if given field is numeric
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

        $value = $this->getValue($this->field->getFieldName());
        $formatted = $this->toSystemNumber($value);
        $return = is_numeric($formatted);

        if($return)
            container('request')->addToBody($this->field->getFieldName(), (float)$formatted);

        return $return;
    }
}