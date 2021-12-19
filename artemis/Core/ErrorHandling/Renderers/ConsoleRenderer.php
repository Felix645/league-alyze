<?php

namespace Artemis\Core\ErrorHandling\Renderers;


use Artemis\Core\ErrorHandling\RendererInterface;
use Artemis\Core\ErrorHandling\Renderable;
use Artemis\Core\Http\Response;


class ConsoleRenderer implements RendererInterface
{
    /**
     * @inheritDoc
     *
     * @return Response
     */
    public function render(Renderable $renderable, $production = false) : Response
    {
        $message = "{$renderable->exception()}: {$renderable->message()} in {$renderable->file()} on line {$renderable->line()}";

        return response()->text($message);
    }
}