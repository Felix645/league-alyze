<?php


namespace Artemis\Core\Middleware\Middlewares;


use Artemis\Core\Http\Request;
use Artemis\Core\Interfaces\MiddlewareInterface;


class FormDataMiddleware implements MiddlewareInterface
{
    /**
     * @inheritDoc
     */
    public function execute()
    {
        if( !session()->isActive() )
            return;

        /* @var Request $request */
        $request = container('request');

        $request_body = $request->all();
        $protected_keys = session()->config();

        if( empty($request_body) )
            return;

        session()->preventClear();

        foreach( $request_body as $key => $value ) {
            if( !in_array($key, $protected_keys) )
                session()->push('form_data', $key, $value);
        }
    }
}

