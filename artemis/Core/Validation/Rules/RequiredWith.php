<?php

namespace Artemis\Core\Validation\Rules;

use Artemis\Core\Validation\Exceptions\ValidationException;
use Artemis\Core\Validation\Rule;
use Artemis\Core\Validation\Traits\hasRequiredCheck;

class RequiredWith extends Rule
{
    use hasRequiredCheck;

    protected $params = ['requiredWith'];

    /**
     * @inheritDoc
     */
    public function check($field)
    {
        $this->requireParams($this->params);

        $params = explode(',', $this->field->getAttribute('requiredWith'));

        if( !isset($params[0]) ) {
            $message = "Missing rule params for {$this->field->getFieldName()}: requiredWith";
            throw new ValidationException($message);
        }

        foreach( $params as $parent_field ) {
            if( $this->fieldIsEmpty($parent_field) ) {
                continue;
            }

            return $this->checkRequired();
        }

        return true;
    }
}