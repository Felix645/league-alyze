<?php


namespace Artemis\Client\Facades;


use Artemis\Utils\SoapResponse;


/**
 * Class Soap
 * @package Artemis\Client\Facades
 *
 * @method static \Artemis\Utils\Soap wsdl(string $wsdl) Sets the wsdl path for the SAP request
 * @method static \Artemis\Utils\Soap addParam(string $key, $value) Sets parameters for the SAP function
 * @method static \Artemis\Utils\Soap addOption(string $key, $value) Sets an option for the SoapClient
 * @method static SoapResponse call(string $function) Calls the given SAP function
 *
 * @uses \Artemis\Utils\Soap::wsdl()
 * @uses \Artemis\Utils\Soap::addParam()
 * @uses \Artemis\Utils\Soap::addOption()
 * @uses \Artemis\Utils\Soap::call()
 */
class Soap extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getAccessor()
    {
        return 'soap';
    }
}