<?php

namespace Artemis\Core\Validation\Traits;

trait hasRequiredCheck
{
    /**
     * Performs the required check for the current field.
     *
     * @return bool
     */
    private function checkRequired()
    {
        $this->field->setRequired(true);

        if( $this->fieldIsEmpty($this->field->getFieldName()) ) {
            return false;
        }

        return true;
    }
}