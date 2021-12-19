<?php


namespace Artemis\Core\Validation\Rules;


use Artemis\Core\Validation\Exceptions\ValidationException;
use Artemis\Core\Validation\Rule;
use Artemis\Core\Validation\Field;
use Artemis\Core\Validation\Traits\validatesMinMax;


class Max extends Rule 
{
    use validatesMinMax;

    /**
     * Required parameters
     * 
     * @var array
     */
    protected $params = ['max'];

    /**
     * Checks if given field obeys the given maximum parameter
     * 
     * @param Field $field
     * @throws ValidationException
     * 
     * @return bool
     */
    public function check($field)
    {
        $this->setField($field);
        $this->requireParams($this->params);

        if( !$this->fieldExists() )
            return !$this->field->isRequired();

        return $this->validateMax($this);
    }
}