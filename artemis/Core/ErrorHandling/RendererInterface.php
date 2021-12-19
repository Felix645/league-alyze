<?php


namespace Artemis\Core\ErrorHandling;


use Artemis\Core\Http\Response;


interface RendererInterface
{
    /**
     * Renders the given renderable
     *
     * @param Renderable $renderable    Renderable instance.
     * @param bool $production          Identifier if the application is in production mode or not.
     *
     * @return Response
     */
    public function render(Renderable $renderable, $production = false) : Response;
}