<?php

namespace Artemis\Core\Validation\Rules;

use Artemis\Core\Validation\Exceptions\ValidationException;
use Artemis\Core\Validation\Rule;
use Artemis\Core\Validation\Traits\hasRequiredCheck;

class RequiredUnless extends Rule
{
    use hasRequiredCheck;

    protected $params = ['requiredUnless'];

    /**
     * @inheritDoc
     */
    public function check($field)
    {
        $this->requireParams($this->params);

        $params = explode(',', $this->field->getAttribute('requiredUnless'));

        if( !isset($params[0]) || !isset($params[1]) ) {
            $message = "Missing rule params for {$this->field->getFieldName()}: requiredUnless";
            throw new ValidationException($message);
        }

        $parent_field = $params[0];
        $parent_value_expected = $params[1];

        if( !$this->fieldIsEmpty($parent_field) )
        {
            if( $this->haystack[$parent_field] == $parent_value_expected )
            {
                return true;
            }
        }

        return $this->checkRequired();
    }
}