<?php

namespace Artemis\Core\Validation\Rules;

use Artemis\Core\Validation\Rule;

class Regex extends Rule
{
    protected $params = ['regex'];

    /**
     * @inheritDoc
     */
    public function check($field)
    {
        $this->requireParams($this->params);

        if( $this->fieldIsEmpty($this->field->getFieldName()) ) {
            return !$this->field->isRequired();
        }

        $regex = $this->field->getAttribute('regex');

        return preg_match($regex, $this->haystack[$this->field->getFieldName()]) === 1;
    }
}