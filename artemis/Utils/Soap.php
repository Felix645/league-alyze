<?php


namespace Artemis\Utils;


use SoapClient;
use SoapFault;


class Soap
{
    /**
     * SAP function to be called
     *
     * @var string
     */
    private $sap_function;

    /**
     * SAP function parameters
     *
     * @var array
     */
    private $params = [];

    /**
     * Options for Soap request
     *
     * @var null|array
     */
    private $options = null;

    /**
     * Path to the wsdl file
     *
     * @var string
     */
    private $wsdl;

    /**
     * Sets the wsdl path for the SAP request
     *
     * @param string $wsdl
     *
     * @return Soap
     */
    public function wsdl($wsdl)
    {
        $this->wsdl = $wsdl;
        return $this;
    }

    /**
     * Sets parameters for the SAP function
     *
     * @param string $key
     * @param mixed $value
     *
     * @return Soap
     */
    public function addParam($key, $value)
    {
        $this->params[$key] = $value;
        return $this;
    }

    /**
     * Sets an option for the SoapClient
     *
     * @param string $key
     * @param $value
     *
     * @return Soap
     */
    public function addOption($key, $value)
    {
        $this->options[$key] = $value;
        return $this;
    }

    /**
     * Calls the given SAP function
     *
     * @param string $function
     * @throws SoapFault
     *
     * @return SoapResponse
     */
    public function call($function)
    {
        $this->sap_function = $function;
        return $this->buildSAPRequest();
    }

    /**
     * Builds the SAP request and returns the response
     *
     * @throws SoapFault
     *
     * @return SoapResponse
     */
    private function buildSAPRequest()
    {
        header('Access-Control-Allow-Origin: *');

        $client = new SoapClient($this->wsdl, $this->options);
        $function = $this->sap_function;

        if( empty($this->params) )
            $response = $client->$function();
        else
            $response = $client->$function($this->params);

        return new SoapResponse($response);
    }
}