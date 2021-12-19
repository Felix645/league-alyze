<?php


namespace Artemis\Core\Validation\Traits;


use Artemis\Core\Validation\Rule;
use Artemis\Core\Validation\Rules\Max;
use Artemis\Core\Validation\Rules\Min;


trait validatesMinMax
{
    /**
     * Validates minimum value
     *
     * @param Min $rule
     *
     * @return bool
     */
    protected function validateMin($rule)
    {
        $min = $rule->field->getAttribute('min');

        $value = $this->getFieldValue($rule);

        return $this->validateMinValue($min, $value);
    }

    /**
     * Validates maximum value
     *
     * @param Max $rule
     *
     * @return bool
     */
    protected function validateMax($rule)
    {
        $max = $rule->field->getAttribute('max');

        $value = $this->getFieldValue($rule);

        return $this->validateMaxValue($max, $value);
    }

    /**
     * Compares the minimum value against the field value
     *
     * @param int|float $min
     * @param int|float $value
     *
     * @return bool
     */
    private function validateMinValue($min, $value)
    {
        return $value >= $min;
    }

    /**
     * Compares the maximum value against the field value
     *
     * @param int|string $max
     * @param int|string $value
     *
     * @return bool
     */
    private function validateMaxValue($max, $value)
    {
        return $value <= $max;
    }

    /**
     * Gets the field value
     *
     * @param Rule $rule
     *
     * @return int|string
     */
    private function getFieldValue(Rule $rule)
    {
        if( $rule->field->isNumeric() )
            return $rule->getValue($rule->field->getFieldName());

        return $rule->getValueSize($rule->field->getFieldName());
    }
}