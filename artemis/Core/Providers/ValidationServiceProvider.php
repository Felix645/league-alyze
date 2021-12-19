<?php


namespace Artemis\Core\Providers;


use Artemis\Core\Interfaces\ProviderInterface;
use Artemis\Core\Validation\Error;
use Artemis\Core\Validation\Validation;


abstract class ValidationServiceProvider implements ProviderInterface
{
    /**
     * Validation instance
     *
     * @var Validation
     */
    private $validation;

    /**
     * Here you may add your custom rule implemenations.
     * Note that each rule MUST extend from \Artemis\Core\Validation\Rule .
     *
     * @var array
     */
    protected $rules = [];

    /**
     * ValidationServiceProvider constructor.
     */
    public function __construct()
    {
        $this->validation = container('validation');
    }

    /**
     * Registers the specified rules.
     *
     * @return void
     */
    final protected function registerRules()
    {
        foreach( $this->rules as $key => $rule ) {
            $this->validation->setValidator($key, $rule);
        }
    }
}