<?php


namespace Artemis\Core\ErrorHandling;


use Artemis\Core\ErrorHandling\Renderers\ConsoleRenderer;
use Artemis\Core\ErrorHandling\Renderers\HtmlRenderer;
use Artemis\Core\ErrorHandling\Renderers\JsonRenderer;
use Artemis\Core\Http\Response;


class ExceptionRenderer
{
    /**
     * Prepared exception instance for better rendering.
     *
     * @var Renderable
     */
    private $renderable;

    /**
     * Identifier if application is in production or not.
     *
     * @var bool
     */
    private $in_production;

    /**
     * Identifier if the request needs json as a response.
     *
     * @var bool
     */
    private $needs_json;

    /**
     * Identifier if the application was called from a command line interface.
     *
     * @var bool
     */
    private $from_cli;

    /**
     * ExceptionRenderer constructor.
     *
     * @param Renderable $renderable
     * @param bool $needs_json
     *
     * @param bool $from_cli
     */
    public function __construct(Renderable $renderable, $needs_json = false, $from_cli = false)
    {
        $this->renderable = $renderable;
        $this->in_production = !app()->debug();
        $this->needs_json = $needs_json;
        $this->from_cli = $from_cli;
    }

    /**
     * Gets the right renderer for the current request.
     *
     * @return RendererInterface
     */
    public function getRenderer()
    {
        if( $this->needs_json ) {
            return new JsonRenderer();
        }

        if( $this->from_cli ) {
            return new ConsoleRenderer();
        }

        return new HtmlRenderer();
    }

    /**
     * Renders the exception.
     *
     * @return Response
     */
    public function render()
    {
        return $this->getRenderer()->render($this->renderable, $this->in_production);
    }
}