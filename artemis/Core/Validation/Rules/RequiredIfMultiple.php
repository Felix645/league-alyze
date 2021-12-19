<?php

namespace Artemis\Core\Validation\Rules;

use Artemis\Core\Validation\Exceptions\ValidationException;
use Artemis\Core\Validation\Rule;
use Artemis\Core\Validation\Traits\hasRequiredCheck;

class RequiredIfMultiple extends Rule
{
    use hasRequiredCheck;

    protected $params = ['requiredIfMultiple'];

    /**
     * @inheritDoc
     */
    public function check($field)
    {
        $this->requireParams($this->params);

        $params = explode(';', $this->field->getAttribute('requiredIfMultiple'));

        if( !isset($params[0]) ) {
            $message = "Missing rule params for {$this->field->getFieldName()}: requiredIfMultiple";
            throw new ValidationException($message);
        }

        $matched_parents = 0;
        foreach( $params as $param ) {
            $field_value_pair = explode(',', $param);

            if( !isset($field_value_pair[0]) || !isset($field_value_pair[1]) ) {
                $message = "Missing rule params for {$this->field->getFieldName()}: requiredIfMultiple";
                throw new ValidationException($message);
            }

            $parent_field = $field_value_pair[0];
            $parent_value_expected = $field_value_pair[1];

            if( $this->fieldIsEmpty($parent_field) ) {
                return true;
            }

            if( $this->haystack[$parent_field] == $parent_value_expected ) {
                $matched_parents++;
            }
        }

        if( $matched_parents < count($params) ) {
            return true;
        }

        return $this->checkRequired();
    }
}