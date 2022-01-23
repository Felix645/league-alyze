<?php


namespace Artemis\Core\Validation;


use Artemis\Core\Validation\Exceptions\ValidationException;
use Artemis\Support\Str;

class Error
{
    /**
     * Error field name
     * 
     * @var string
     */
    private $field_name;

    /**
     * Field params
     * 
     * @var
     */
    private $field_param;

    /**
     * Rule at which the field failed at
     * 
     * @var string
     */
    private $failed_rule;

    /**
     * The error key
     * 
     * @var string
     */
    private $error_key;

    /**
     * The error message
     * 
     * @var string
     */
    private $error_message;

    /**
     * Error constructor.
     *
     * @throws ValidationException
     *
     * @param Field $field
     */
    public function __construct($field)
    {
        $this->field_name = $field->getFieldName();
        $this->failed_rule = $field->failedAt();

        if( $this->failed_rule instanceof Rule ) {
            $this->field_param = null;
        } elseif( is_string($this->failed_rule) ) {
            $this->field_param = $field->getAttribute($this->failed_rule);
        } else {
            throw new ValidationException('Invalid rule specified');
        }

        $this->error_key = $this->field_name;
        $this->error_message = $this->buildErrorMessage();
    }

    /**
     * Gets the field name
     * 
     * @return string
     */
    public function getFieldName()
    {
        return $this->field_name;
    }

    /**
     * Gets the error key
     * 
     * @return string
     */
    public function getErrorKey()
    {
        return $this->error_key;
    }

    /**
     * Gets the error message
     * 
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }

    /**
     * Gets the failed rule.
     *
     * @return string
     */
    public function failedAt()
    {
        return $this->failed_rule;
    }

    /**
     * Builds the error message
     *
     * @throws ValidationException
     *
     * @return string
     */
    private function buildErrorMessage()
    {
        if( $this->failed_rule instanceof Rule ) {
            $message = $this->failed_rule->message();

            if( is_string($message) ) {
                return $message;
            }
        }

        if( !is_string($this->failed_rule) ) {
            throw new ValidationException('Invalid rule specified');
        }

        $config = require ROOT_PATH . 'config/validation.php';

        $message_template = $config['field'][$this->field_name]
            ?? $config['field_rule'][$this->failed_rule][$this->field_name]
            ?? $config['default'][$this->failed_rule]
            ?? 'Validation error';

        $replacement_key = ':param';

        return Str::replace($replacement_key, $this->field_param, $message_template);
    }
}