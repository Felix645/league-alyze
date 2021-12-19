<?php


namespace Artemis\Core\Validation\Rules;


use Artemis\Core\Validation\Field;
use Artemis\Core\Validation\Rule;
use Artemis\Core\Validation\Traits\hasDateFormats;
use Exception;


class Date extends Rule
{
    use hasDateFormats;

    /**
     * Checks if the given field is a valid date
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

        try{
            $value = $this->toTimestamp($this->getValue($this->field->getFieldName()));
            container('request')->addToBody($this->field->getFieldName(), $value);
        } catch(Exception $e) {
            return false;
        }

        $this->field->setDate(true);

        return true;
    }
}