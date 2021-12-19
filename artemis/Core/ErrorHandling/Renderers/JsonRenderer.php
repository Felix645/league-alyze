<?php


namespace Artemis\Core\ErrorHandling\Renderers;


use Artemis\Core\ErrorHandling\Renderable;
use Artemis\Core\ErrorHandling\RendererInterface;
use Artemis\Core\Http\Response;


class JsonRenderer implements RendererInterface
{
    /**
     * @inheritDoc
     *
     * @return Response
     */
    public function render(Renderable $renderable, $production = false) : Response
    {
        if( $production ) {
            return $this->renderProduction($renderable);
        }

        return $this->renderDebug($renderable);
    }

    /**
     * Renders the json for production mode.
     *
     * @param Renderable $renderable
     *
     * @return Response
     */
    private function renderProduction(Renderable $renderable) : Response
    {
        $json = [];

        $default_message = 'Internal server error';
        $default_code = Response::HTTP_INTERNAL_ERROR;

        $messages_map = [
            Response::HTTP_BAD_REQUEST => 'Request has bad format',
            Response::HTTP_FORBIDDEN => 'Access Forbidden',
            Response::HTTP_METHOD_NOT_ALLOWED => 'Request method is not allowed',
            Response::HTTP_UNAUTHORIZED => 'Unauthorized',
            Response::HTTP_NOT_FOUND => 'Resource not found',
            Response::HTTP_UNPROCESSABLE_ENTITY => 'Request has bad format',
            Response::HTTP_UNSUPPORTED_MEDIA_TYPE => 'Request delivers unsupported media type',
            Response::HTTP_INTERNAL_ERROR => $default_message,
            Response::HTTP_SERVICE_UNAVAILABLE => 'Service currently unavailable'
        ];

        $code = array_key_exists($renderable->code(), $messages_map) ? $renderable->code() : $default_code;
        $message = $messages_map[$renderable->code()] ?? $default_message;

        $json['status'] = $code;
        $json['message'] = $message;

        return \response()->json($json)->code($code);
    }

    /**
     * Renders the json for debug mode.
     *
     * @param Renderable $renderable
     *
     * @return Response
     */
    private function renderDebug(Renderable $renderable) : Response
    {
        $json = [];

        $json['status'] = $renderable->code();
        $json['message'] = $renderable->message();
        $json['file'] = $renderable->file();
        $json['line'] = $renderable->line();

        foreach( $renderable->trace() as $key => $trace_item ) {
            $json['trace'][$key]['file'] = $trace_item->file();
            $json['trace'][$key]['line'] = $trace_item->line();
            $json['trace'][$key]['function'] = $trace_item->function();
            $json['trace'][$key]['class'] = $trace_item->class();
            $json['trace'][$key]['type'] = $trace_item->type();
            $json['trace'][$key]['args'] = $trace_item->args()->argString();
        }

        return \response()->json($json)->code($renderable->code());
    }
}