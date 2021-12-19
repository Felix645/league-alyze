<?php


namespace Artemis\Core\Validation\Rules;


use Artemis\Core\Validation\Rule;
use Artemis\Core\Validation\Field;
use Artemis\Core\Validation\Traits\hasRequiredCheck;


class Required extends Rule 
{
    use hasRequiredCheck;

    /**
     * Checks if given field is present
     * 
     * @param Field $field
     * 
     * @return bool
     */
    public function check($field)
    {
        return $this->checkRequired();
    }
}