<?php


namespace Artemis\Core\Validation;


use Artemis\Core\Validation\Exceptions\ValidationException;


class Field
{
    /**
     * The input field name
     * 
     * @var string
     */
    private $field_name;

    /**
     * The rules for that field
     * 
     * @var array
     */
    private $rules = [];

    /**
     * Identifier if the field is required
     *
     * @var bool
     */
    private $is_required = false;

    /**
     * Identifier if the field is numeric
     *
     * @var bool
     */
    private $is_numeric = false;

    /**
     * Identifier if the field is a date or timestamp
     *
     * @var bool
     */
    private $is_date = false;

    /**
     * The rule parameters for that field
     * 
     * @var array
     */
    private $attributes = [];

    /**
     * Identifier how many checks are required for this field to be valid
     * 
     * @var int
     */
    private $required_checks = 0;

    /**
     * Identifier at which rule the field validation failed
     * 
     * @var string
     */
    private $failed_at = '';

    /**
     * Input Field Constructor
     * 
     * @param string $field_name
     */
    public function __construct($field_name)
    {
        $this->field_name = $field_name;
    }

    /**
     * Validates the field
     * 
     * @param Validation $Validation
     *
     * @throws ValidationException
     * 
     * @return bool
     */
    public function validate($Validation)
    {
        $passed_checks = 0;
        $this->required_checks = count($this->rules);

        foreach( $this->rules as $rule ) {
            if( is_string($rule) ) {
                $Validator = $Validation->getValidator($rule);
            } elseif( $rule instanceof Rule ) {
                $Validator = $rule;
            } else {
                throw new ValidationException('Invalid rule specified');
            }

            $Validator->setHaystack($Validation->getHaystack());
            $Validator->setField($this);

            if( $Validator->check($this) ) {
                $passed_checks++;
            } else {
                $this->failed_at = $rule;
                break;
            }              
        }

        return $this->checkForValidation($passed_checks);
    }

    /**
     * Checks if validation passed
     * 
     * @param int $passed_checks
     * 
     * @return bool
     */
    private function checkForValidation($passed_checks)
    {
        return $this->required_checks === $passed_checks;
    }

    /**
     * Gets the rule at which the field failed at
     * 
     * @return string
     */
    public function failedAt()
    {
        return $this->failed_at;
    }

    /**
     * Get the field name
     * 
     * @return string
     */
    public function getFieldName()
    {
        return $this->field_name;
    }

    /**
     * Gets a field attribute
     * 
     * @param string $key
     * 
     * @return null|string
     */
    public function getAttribute($key)
    {
        return $this->attributes[$key] ?? NULL;
    }

    /**
     * Gets all field attributes
     * 
     * @return array
     */
    public function getAllAttributes()
    {
        return $this->attributes;
    }

    /**
     * Adds a validation rule
     * 
     * @param array $rules
     * 
     * @return void
     */
    public function addRules($rules)
    {
        $this->rules = $rules;
    }

    /**
     * Adds an attributes
     * 
     * @param array $attributes
     * 
     * @return void
     */
    public function addAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Gets the identifier if the field is required or not
     *
     * @return bool
     */
    public function isRequired()
    {
        return $this->is_required;
    }

    /**
     * Gets the identifier if the field is numeric or not
     *
     * @return bool
     */
    public function isNumeric()
    {
        return $this->is_numeric;
    }

    /**
     * Gets the identifier if the field is date or timestamp
     *
     * @return bool
     */
    public function isDate()
    {
        return $this->is_date;
    }

    /**
     * Sets the 'required' property
     *
     * @param bool $value
     *
     * @return Field
     */
    public function setRequired($value)
    {
        $this->is_required = $value;
        return $this;
    }

    /**
     * Sets the 'numeric' property
     *
     * @param bool $value
     *
     * @return Field
     */
    public function setNumeric($value)
    {
        $this->is_numeric = $value;
        return $this;
    }

    /**
     * Sets the 'date' property
     *
     * @param bool $value
     *
     * @return Field
     */
    public function setDate($value)
    {
        $this->is_date = $value;
        return $this;
    }
}