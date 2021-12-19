<?php

namespace Artemis\Core\Validation\Rules;


use Artemis\Core\Validation\Exceptions\ValidationException;
use Artemis\Core\Validation\Rule;
use Artemis\Core\Validation\Traits\hasRequiredCheck;

class RequiredWithoutAll extends Rule
{
    use hasRequiredCheck;

    protected $params = ['requiredWithoutAll'];

    /**
     * @inheritDoc
     */
    public function check($field)
    {
        $this->requireParams($this->params);

        $params = explode(',', $this->field->getAttribute('requiredWithoutAll'));

        if( !isset($params[0]) ) {
            $message = "Missing rule params for {$this->field->getFieldName()}: requiredWithoutAll";
            throw new ValidationException($message);
        }

        foreach( $params as $parent_field ) {
            if( !$this->fieldIsEmpty($parent_field) ) {
                return true;
            }
        }

        return $this->checkRequired();
    }
}