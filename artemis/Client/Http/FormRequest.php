<?php


namespace Artemis\Client\Http;


use Artemis\Core\Exception\ControllerException;
use Artemis\Core\Exception\ForbiddenException;
use Artemis\Core\Http\Exceptions\RequestException;
use Artemis\Core\Http\Request;
use Artemis\Core\Http\RequestContract;
use Artemis\Core\Http\ResponseHandler;
use Artemis\Core\Validation\Validation;


abstract class FormRequest extends RequestContract
{
    /**
     * Core Request object
     *
     * @var Request
     */
    private $request;

    /**
     * FormRequest constructor.
     */
    public function __construct()
    {
        $this->request = container('request');
    }

    /**
     * Retrieves the defines rules array
     *
     * @return array
     */
    abstract protected function rules();

    /**
     * Executes when the validation fails.
     *
     * @return mixed
     */
    abstract protected function fails();

    /**
     * @inheritDoc
     */
    public function getRequestURL()
    {
        return $this->request->getRequestURL();
    }

    /**
     * @inheritDoc
     */
    public function getRequestURI()
    {
        return $this->request->getRequestURI();
    }

    /**
     * @inheritDoc
     */
    public function getRequestMethod()
    {
        return $this->request->getRequestMethod();
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->request->getHeaders();
    }

    /**
     * @inheritDoc
     *
     * @return bool True if the header is present, false if not.
     */
    public function hasHeader($header_key)
    {
        return $this->request->hasHeader($header_key);
    }

    /**
     * @inheritDoc
     *
     * @return array|null List of the header values. Default value if the header does not exist.
     */
    public function header($header_key, $default = null)
    {
        return $this->request->header($header_key, $default);
    }

    /**
     * @inheritDoc
     *
     * @return string|mixed|null First header key if header is present. Otherwise returns null or the specified default.
     */
    public function headerFirst($header_key, $default = null)
    {
        return $this->request->headerFirst($header_key, $default);
    }

    /**
     * @inheritDoc
     *
     * @return string Bearer token if it is present on the request. Empty string when it is not.
     */
    public function bearerToken()
    {
        return $this->request->bearerToken();
    }

    /**
     * @inheritDoc
     *
     * @return bool True if the header exists and has the given values. False otherwise.
     */
    public function headerContains($header, $value)
    {
        return $this->request->headerContains($header, $value);
    }

    /**
     * @inheritDoc
     *
     * @return bool True if any of content-types are accepted by the request. False otherwise.
     */
    public function accepts($value, ...$values)
    {
        return $this->request->accepts($value, ...$values);
    }

    /**
     * @inheritDoc
     *
     * @return bool True if text/html is present in accept header. False otherwise.
     */
    public function needsHtml()
    {
        return $this->request->needsHtml();
    }

    /**
     * @inheritDoc
     *
     * @return bool True if application/json is present in accept header. False otherwise.
     */
    public function needsJson()
    {
        return $this->request->needsJson();
    }

    /**
     * @inheritDoc
     *
     * @return bool True if text/xml is present in accept header. False otherwise.
     */
    public function needsXml()
    {
        return $this->request->needsXml();
    }

    /**
     * @inheritDoc
     *
     * @return bool True if request was made via an ajax call, false otherwise.
     */
    public function isAjax()
    {
        return $this->request->isAjax();
    }

    /**
     * @inheritDoc
     */
    public function all()
    {
        return $this->request->all();
    }

    /**
     * @inheritDoc
     */
    public function validated()
    {
        return $this->request->validated();
    }

    /**
     * @inheritDoc
     */
    public function getURLParam($key)
    {
        return $this->request->getURLParam($key);
    }

    /**
     * @inheritDoc
     */
    public function getURLBits()
    {
        return $this->request->getURLBits();
    }

    /**
     * @inheritDoc
     */
    public function getLastPage()
    {
        return $this->request->getLastPage();
    }

    /**
     * @inheritDoc
     */
    public function getCSRFToken()
    {
        return $this->request->getCSRFToken();
    }

    /**
     * @inheritDoc
     */
    public function files($key)
    {
        return $this->request->files($key);
    }

    /**
     * Gets all files of the request. Empty array if no files are present.
     *
     * @return array
     */
    public function filesAll()
    {
        return $this->request->filesAll();
    }

    /**
     * Provides some additional authorization if desired. If the FormRequest is always valid just return true.
     *
     * @return bool
     */
    protected function authorize()
    {
        return true;
    }

    /**
     * Defines the body for validation
     *
     * @return array
     */
    protected function validationBody()
    {
        return [];
    }

    /**
     * Validates a request based on the given FormRequest implementation.
     *
     * @param array $rules = []
     * @param array $body
     *
     * @return void
     */
    public function validate($rules = [], $body = [])
    {
        try {
            if( !$this->authorize() ) {
                ResponseHandler::new($this->unauthorized());
                exit;
            }

            if( !empty($rules) ) {
                $message = "Do not place parameters into validate() method when using FormRequest Request Class. Put them in the rules() method of your FormRequest implementation instead.";
                throw new RequestException($message);
            }

            $body = $this->validationBody();

            if( empty($body) )
                $body = $this->all();

            $validation = container('validation');

            $validation->validate($body, $this->rules());

            if( $validation->fails() ) {
                $this->errors($validation);
                ResponseHandler::new($this->fails());
                exit;
            }
        } catch(RequestException | ControllerException | ForbiddenException $e) {
            report($e);
        }
    }

    /**
     * @inheritDoc
     *
     * @return $this|FormRequest
     */
    public function addToValidated($key, $value)
    {
        $this->request->addToValidated($key, $value);

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @return $this
     */
    public function addToBody($key, $value)
    {
        $this->request->addToBody($key, $value);

        return $this;
    }

    /**
     * @inheritDoc
     *
     * @return $this
     */
    public function addURLParam($key, $value)
    {
        $this->request->addURLParam($key, $value);

        return $this;
    }

    /**
     * Handler when the request is deemed to be unauthorized.
     *
     * @throws ForbiddenException
     *
     * @return mixed
     */
    protected function unauthorized()
    {
        throw new ForbiddenException();
    }

    /**
     * Adds errors based on the standard validation errors if the validation fails.
     *
     * @param Validation $validation
     *
     * @return void
     */
    protected function errors($validation)
    {
        if( !container('session')->isActive() ) {
            return;
        }

        $errors = $validation->errors();

        foreach( $errors as $error ) {
            container('response')->addValidationError($error["key"], $error["message"]);
        }
    }
}