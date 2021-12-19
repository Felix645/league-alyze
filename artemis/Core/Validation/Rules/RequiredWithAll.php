<?php

namespace Artemis\Core\Validation\Rules;

use Artemis\Core\Validation\Exceptions\ValidationException;
use Artemis\Core\Validation\Rule;
use Artemis\Core\Validation\Traits\hasRequiredCheck;

class RequiredWithAll extends Rule
{
    use hasRequiredCheck;

    protected $params = ['requiredWithAll'];

    /**
     * @inheritDoc
     */
    public function check($field)
    {
        $this->requireParams($this->params);

        $params = explode(',', $this->field->getAttribute('requiredWithAll'));

        if( !isset($params[0]) ) {
            $message = "Missing rule params for {$this->field->getFieldName()}: requiredWithAll";
            throw new ValidationException($message);
        }

        foreach( $params as $parent_value ) {
            if( $this->fieldIsEmpty($parent_value) ) {
                return true;
            }
        }

        return $this->checkRequired();
    }
}