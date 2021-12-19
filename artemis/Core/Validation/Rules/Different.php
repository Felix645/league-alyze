<?php

namespace Artemis\Core\Validation\Rules;

use Artemis\Core\Validation\Rule;

class Different extends Rule
{
    protected $params = ['different'];

    /**
     * @inheritDoc
     */
    public function check($field)
    {
        $this->requireParams($this->params);

        if( $this->fieldIsEmpty($this->field->getFieldName()) ) {
            return !$this->field->isRequired();
        }

        $parent = $this->field->getAttribute('different');

        if( $this->fieldIsEmpty($parent) ) {
            return true;
        }

        if( $this->haystack[$this->field->getFieldName()] == $this->haystack[$parent] ) {
            return false;
        }

        return true;
    }
}