<?php


namespace Artemis\Core\Validation;


use Artemis\Core\Validation\Exceptions\ValidationException;

class InputParser
{
    /**
     * The given rule set
     * 
     * @var string|array
     */
    private $rule_set;

    /**
     * The processed rules
     * 
     * @var array
     */
    private $rules = [];

    /**
     * The processed attributes for each rule if any are present
     * 
     * @var array
     */
    private $attributes = [];

    /**
     * InputParser Constructor
     * 
     * @param string|array $rule_set
     */
    public function __construct($rule_set)
    {
        $this->rule_set = $rule_set;
    }

    /**
     * Gets the parsed rules
     * 
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Gets the parsed attributes
     * 
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Parses the given rule set
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function parse()
    {
        if( is_string($this->rule_set) ) {
            $this->parseString();
            return;
        }

        if( is_array($this->rule_set) ) {
            $this->parseArray();
            return;
        }

        throw new ValidationException('Invalid ruleset type provided');
    }

    /**
     * Parses the string rule set.
     *
     * @return void
     */
    private function parseString()
    {
        $parts = explode('|', $this->rule_set);

        foreach( $parts as $rule )
        {
            $this->parseRule($rule);
        }
    }

    /**
     * Parses the array rule set.
     *
     * @return void
     */
    private function parseArray()
    {
        foreach( $this->rule_set as $rule )
        {
            $this->parseRule($rule);
        }
    }

    /**
     * Parses a given rule for attributes
     * 
     * @param string $input
     * 
     * @return void
     */
    private function parseRule($input)
    {
        if( $input instanceof Rule ) {
            $this->setRule($input);

            return;
        }

        $exploded_rule = explode(':', $input);
        $this->setRule($exploded_rule[0]);

        if( isset($exploded_rule[1]) && $exploded_rule[1] !== '' )
            $this->setAttribute($exploded_rule[0], $exploded_rule[1]);
    }

    /**
     * Adds a rule to the collection
     * 
     * @param string|Rule $rule
     * 
     * @return void
     */
    private function setRule($rule)
    {
        $this->rules[] = $rule;
    }

    /**
     * Adds an attribute to the collection
     * 
     * @param string $rule
     * @param string $attribute
     * 
     * @return void
     */
    private function setAttribute($rule, $attribute)
    {
        $this->attributes[$rule] = $attribute;
    }
}