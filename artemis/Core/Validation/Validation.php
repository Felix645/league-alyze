<?php


namespace Artemis\Core\Validation;


use Artemis\Support\Arr;
use Artemis\Core\Validation\Rules as Rules;
use Artemis\Core\Validation\Exceptions\ValidationException;


class Validation
{
    /**
     * Haystack is to be validated.
     *
     * @var array
     */
    private $haystack = [];

    /**
     * Collection of all validators
     * 
     * @var string[]
     */
    private $validators = [];

    /**
     * Collection of each input field
     * 
     * @var Field[]
     */
    private $fields = [];

    /**
     * Collection of errors
     * 
     * @var Error[]
     */
    private $errors = [];

    /**
     * Identifier if the validation was successful or not
     * 
     * @var bool
     */
    private $valid = false;

    /**
     * Validation Constructor
     */
    public function __construct()
    {
        $this->registerValidatorMap();
    }

    /**
     * Starts the validation process
     *
     * @param array $haystack
     * @param array $validation_set
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function validate($haystack, $validation_set)
    {
        $this->fields = [];
        $this->errors = [];
        $this->valid = false;

        $this->haystack = $haystack;
        $this->buildFields($validation_set);

        try {
            $this->setValidationState($this->validateFields());
        } catch(ValidationException $e) {
            report($e);
        }
    }

    /**
     * Returns true if the validation failed, false when validation succeeded
     * 
     * @return bool
     */
    public function fails()
    {
        return !$this->valid;
    }

    /**
     * Validates each field
     * 
     * @throws ValidationException
     * 
     * @return bool
     */
    private function validateFields()
    {
        $validFields = 0;

        foreach( $this->fields as $field ) {
            if( $field->validate($this) ) {
                $validFields++;

                $key = $field->getFieldName();
                $body = container('request')->all();

                if( Arr::exists($key, $body) )
                    container('request')->addToValidated($key, $body[$key]);
            } else {
                $error = new Error($field);
                $this->errors[] = $error;
            }
        }

        return Arr::length($this->fields) <= $validFields;
    }

    /**
     * Gets the errors as an array
     * 
     * @return array
     */
    public function errors()
    {
        $errors = [];

        foreach( $this->errors as $error ) {
            $field_name = $error->getFieldName();
            $errors[$field_name]["key"] = $error->getErrorKey();
            $errors[$field_name]["message"] = $error->getErrorMessage();
        }

        return $errors;
    }

    /**
     * Gets the errors as an array of error objects
     * 
     * @return Error[]
     */
    public function errorsObject()
    {
        return $this->errors;
    }

    /**
     * Builds the Field Collection
     *
     * @param array $validation_set
     *
     * @throws ValidationException
     *
     * @return void
     */
    private function buildFields($validation_set)
    {
        foreach( $validation_set as $field_name => $rule_set ) {
            $InputParser = new InputParser($rule_set);
            $InputParser->parse();

            $rules = $InputParser->getRules();
            $attributes = $InputParser->getAttributes();

            unset($InputParser);

            $this->setField($field_name, $rules, $attributes);
        }
    }

    /**
     * Attaches a new Field to the fields property
     * 
     * @param string $field_name
     * @param array $rules
     * @param array $attributes
     * 
     * @return void
     */
    private function setField($field_name, $rules, $attributes)
    {
        $Field = new Field($field_name);
        $Field->addRules($rules);
        $Field->addAttributes($attributes);

        $this->fields[] = $Field;
    }

    /**
     * Sets the validation state of the current validation
     * 
     * @param bool $input
     * 
     * @return void
     */
    private function setValidationState($input)
    {
        $this->valid = $input;
    }

    /**
     * Gets a specified Validator
     * 
     * @param string $key
     * @throws ValidationException
     * 
     * @return Rule
     */
    public function getValidator($key)
    {
        if( isset($this->validators[$key]) ) {
            $validator_class = $this->validators[$key];

            if( $validator_class instanceof Rule ) {
                $validator = $validator_class;
            } elseif( is_string($validator_class) ) {
                $validator = new $validator_class();
            } else {
                throw new ValidationException('Invalid rule specified');
            }

            /** @var Rule $validator */
            $validator->setHaystack($this->haystack);

            return $validator;
        } else {
            $message = "Invalid rule specified: $key";
            throw new ValidationException($message);
        }
    }

    /**
     * Sets a validator
     * 
     * @param string $key
     * @param string|Rule $validator
     * 
     * @return void
     */
    public function setValidator($key, $validator)
    {
        $this->validators[$key] = $validator;
    }

    /**
     * Gets the validation haystack.
     * 
     * @return array
     */
    public function getHaystack()
    {
        return $this->haystack;
    }

    /**
     * Updates the current haystack.
     *
     * @param array $new_haystack
     *
     * @return void
     */
    public function updateHaystack($new_haystack)
    {
        $this->haystack = $new_haystack;
    }

    /**
     * Registers all validators
     * 
     * @return void
     */
    private function registerValidatorMap()
    {
        $map = [
            'after'                     => Rules\After::class,
            'alpha'                     => Rules\Alpha::class,
            'alphaNum'                  => Rules\AlphaNum::class,
            'alphaSpaces'               => Rules\AlphaSpaces::class,
            'before'                    => Rules\Before::class,
            'confirm'                   => Rules\Confirm::class,
            'date'                      => Rules\Date::class,
            'default'                   => Rules\Defaults::class,
            'email'                     => Rules\Email::class,
            'int'                       => Rules\Integer::class,
            'max'                       => Rules\Max::class,
            'min'                       => Rules\Min::class,
            'numeric'                   => Rules\Numeric::class,
            'required'                  => Rules\Required::class,
            'requiredIf'                => Rules\RequiredIf::class,
            'requiredUnless'            => Rules\RequiredUnless::class,
            'requiredWith'              => Rules\RequiredWith::class,
            'requiredWithAll'           => Rules\RequiredWithAll::class,
            'requiredWithout'           => Rules\RequiredWithout::class,
            'requiredWithoutAll'        => Rules\RequiredWithoutAll::class,
            'requiredIfMultiple'        => Rules\RequiredIfMultiple::class,
            'requiredUnlessMultiple'    => Rules\RequiredUnlessMultiple::class,
            'regex'                     => Rules\Regex::class,
            'notRegex'                  => Rules\NotRegex::class,
            'same'                      => Rules\Same::class,
            'different'                 => Rules\Different::class,
        ];

        foreach( $map as $key => $validator ) {
            $this->setValidator($key, $validator);
        }
    }
}