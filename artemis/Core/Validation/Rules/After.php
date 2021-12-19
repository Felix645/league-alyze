<?php


namespace Artemis\Core\Validation\Rules;


use Artemis\Core\Validation\Exceptions\ValidationException;
use Artemis\Core\Validation\Field;
use Artemis\Core\Validation\Rule;
use Artemis\Core\Validation\Traits\hasDateFormats;
use Exception;


class After extends Rule
{
    use hasDateFormats;

    /**
     * Required parameters
     *
     * @var array
     */
    protected $params = ['after'];

    /**
     * Validates if given field is a date before the date parameter
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

        $param = $this->field->getAttribute('after');
        $value = $this->getValue($this->field->getFieldName());

        try {
            return $this->toDate($value) > $this->toDate($param);
        } catch(Exception $e) {
            $field_name = $this->field->getFieldName();
            $message = "Date supplied for param 'before' on field '{$field_name}' is not a valid date!";
            throw new ValidationException($message);
        }
    }
}