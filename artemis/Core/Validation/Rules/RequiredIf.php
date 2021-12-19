<?php

namespace Artemis\Core\Validation\Rules;

use Artemis\Core\Validation\Exceptions\ValidationException;
use Artemis\Core\Validation\Rule;
use Artemis\Core\Validation\Traits\hasRequiredCheck;

class RequiredIf extends Rule
{
    use hasRequiredCheck;

    protected $params = ['requiredIf'];

    /**
     * @inheritDoc
     */
    public function check($field)
    {
        $this->requireParams($this->params);

        $params = explode(',', $this->field->getAttribute('requiredIf'));

        if( !isset($params[0]) || !isset($params[1]) ) {
            $message = "Missing rule params for {$this->field->getFieldName()}: requiredIf";
            throw new ValidationException($message);
        }

        $parent_field = $params[0];
        $parent_value_expected = $params[1];

        if( $this->fieldIsEmpty($parent_field) )
        {
            return true;
        }

        if( $this->haystack[$parent_field] != $parent_value_expected )
        {
            return true;
        }

        return $this->checkRequired();
    }
}