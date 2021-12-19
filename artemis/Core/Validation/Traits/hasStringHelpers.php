<?php


namespace Artemis\Core\Validation\Traits;


use Artemis\Core\Validation\Rule;


trait hasStringHelpers
{
    /**
     * Gets the field value an casts it as a string type
     *
     * @param Rule $rule
     *
     * @return string
     */
    public function castFieldToString($rule)
    {
        return (string)$rule->getValue($rule->field->getFieldName());
    }

    /**
     * Validates if given string only contains alphabetical characeters
     *
     * @param string $value
     *
     * @return bool
     */
    public function validateAlpha($value)
    {
        return ctype_alpha($value);
    }

    /**
     * Validates if given string only contains alpha-numerical characters
     *
     * @param string $value
     *
     * @return bool
     */
    public function validateAlphaNum($value)
    {
        return ctype_alnum($value);
    }
}