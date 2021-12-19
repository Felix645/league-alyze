<?php


namespace Artemis\Core\DI\Exceptions;


use Throwable;


class InvalidParamException extends \Exception
{
    /**
     * @param \ReflectionParameter $param
     * @param null|\ReflectionNamedType $param_type
     */
    public function __construct($param, $param_type = null)
    {
        $class = $param->getDeclaringClass();

        if( is_null($class) ) {
            $class = '';
        } else {
            $class = " in class '$class->name'";
        }

        if( $param_type instanceof \ReflectionNamedType ) {
            $param_type_name = $param_type->getName();
        } else {
            $param_type_name = "";
        }

        $method = $param->getDeclaringFunction();

        if( $method->isClosure() ) {
            $method_name = "of Closure";
            $class = " in '{$method->getFileName()}'";
        } else {
            $method_name = "of method '{$method->getName()}'";
        }

        $message = "Parameter '$param_type_name \${$param->getName()}' $method_name$class could not be resolved by the Container";

        parent::__construct($message, 500);
    }
}