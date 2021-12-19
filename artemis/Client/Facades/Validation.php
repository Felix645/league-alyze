<?php


namespace Artemis\Client\Facades;


use Artemis\Core\Validation\Error;


/**
 * Class Validation
 * @package Artemis\Client\Facades
 *
 * @method static void validate(array $haystack, array $validation_set)
 * @method static bool fails()
 * @method static array errors()
 * @method static Error[] errorsObject()
 *
 * @uses \Artemis\Core\Validation\Validation::validate()
 * @uses \Artemis\Core\Validation\Validation::fails()
 * @uses \Artemis\Core\Validation\Validation::errors()
 * @uses \Artemis\Core\Validation\Validation::errorsObject()
 */
class Validation extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getAccessor()
    {
        return 'validation';
    }
}