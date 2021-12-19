<?php


namespace Artemis\Core\ErrorHandling\Renderers;


use Artemis\Core\ErrorHandling\RendererInterface;
use Artemis\Support\FileSystem;
use Artemis\Core\ErrorHandling\DebugData\Request;
use Artemis\Core\ErrorHandling\Renderable;
use Artemis\Core\Http\Response;
use Artemis\Resource\Extensions\CustomBladeExtension;
use eftec\bladeone\BladeOne;


class HtmlRenderer implements RendererInterface
{
    /**
     * Core view path.
     *
     * @var string
     */
    public const VIEW_PATH = ROOT_PATH . 'artemis/Core/Views';

    /**
     * View cache path.
     *
     * @var string
     */
    public const CACHE_PATH = ROOT_PATH . 'cache/views';

    /**
     * Path to production view css.
     *
     * @var string
     */
    public const PRODUCTION_CSS = self::VIEW_PATH . '/css/production-template.css';

    /**
     * Path to debug view css.
     *
     * @var string
     */
    private const DEBUG_CSS = self::VIEW_PATH . '/css/debug-template.css';

    /**
     * Path to debug view js.
     *
     * @var string
     */
    private const DEBUG_JS = self::VIEW_PATH . '/js/errors.js';

    /**
     * Collection of custom error views to be rendered if they are present in the project.
     *
     * @var array
     */
    private const CUSTOM_ERROR_VIEWS = [
        400 => [
            'path' => ROOT_PATH . 'app/Views/errors/_400.blade.php',
            'view' => 'errors._400',
        ],
        401 => [
            'path' => ROOT_PATH . 'app/Views/errors/_401.blade.php',
            'view' => 'errors._401',
        ],
        403 => [
            'path' => ROOT_PATH . 'app/Views/errors/_403.blade.php',
            'view' => 'errors._403',
        ],
        404 => [
            'path' => ROOT_PATH . 'app/Views/errors/_404.blade.php',
            'view' => 'errors._404',
        ],
        419 => [
            'path' => ROOT_PATH . 'app/Views/errors/_419.blade.php',
            'view' => 'errors._419',
        ],
        429 => [
            'path' => ROOT_PATH . 'app/Views/errors/_429.blade.php',
            'view' => 'errors._429',
        ],
        500 => [
            'path' => ROOT_PATH . 'app/Views/errors/_500.blade.php',
            'view' => 'errors._500',
        ],
        503 => [
            'path' => ROOT_PATH . 'app/Views/errors/_503.blade.php',
            'view' => 'errors._503',
        ],
    ];

    /**
     * Default custom error view to be rendered if it is present in the project.
     *
     * @var array
     */
    private const CUSTOM_ERROR_VIEW_DEFAULT = [
        'path' => ROOT_PATH . 'app/Views/errors/_error.blade.php',
        'view' => 'errors._error',
    ];

    /**
     * @inheritDoc
     *
     * @return Response
     */
    public function render(Renderable $renderable, $production = false) : Response
    {
        \ob_get_clean();
        $code = $renderable->code();

        if( $view_path = self::CUSTOM_ERROR_VIEWS[$code] ?? null) {
            if( FileSystem::exists($view_path['path']) ) {
                return response()->view($view_path['view'], ['exception' => $renderable], $code);
            }
        }

        if( FileSystem::exists(self::CUSTOM_ERROR_VIEW_DEFAULT['path']) ) {
            return response()->view(self::CUSTOM_ERROR_VIEW_DEFAULT['view'], ['exception' => $renderable], $code);
        }

        if( $production ) {
            return $this->renderProduction($renderable);
        }

        return $this->renderDebug($renderable);
    }

    /**
     * Renders the error for production mode.
     *
     * @param Renderable $renderable
     *
     * @return Response
     */
    private function renderProduction(Renderable $renderable) : Response
    {
        $allowed_codes = [
            Response::HTTP_BAD_REQUEST => 'Bad Request',
            Response::HTTP_UNAUTHORIZED => 'Unauthorized',
            Response::HTTP_FORBIDDEN => 'Forbidden',
            Response::HTTP_NOT_FOUND => 'Resource not found',
            Response::HTTP_METHOD_NOT_ALLOWED => 'Method not allowed',
            Response::HTTP_UNSUPPORTED_MEDIA_TYPE => 'Unsupported media type',
            Response::HTTP_UNPROCESSABLE_ENTITY => 'Unprocessable entity',
            Response::HTTP_INTERNAL_ERROR => 'Internal error',
            Response::HTTP_SERVICE_UNAVAILABLE => 'Service unavailable'
        ];

        $code = array_key_exists($renderable->code(), $allowed_codes)
            ? $renderable->code()
            : Response::HTTP_INTERNAL_ERROR;

        $message = $allowed_codes[$renderable->code()] ?? $allowed_codes[Response::HTTP_INTERNAL_ERROR];
        $css = FileSystem::getContents(self::PRODUCTION_CSS);

        try {
            $blade = new CustomBladeExtension(self::VIEW_PATH, self::CACHE_PATH);

            $html = $blade->run('errors.production-template', compact('code', 'message', 'css'));
        } catch(\Throwable $ex) {
            die("Fatal error: Error while handling exception");
        }

        return \response()->text($html)->header(Response::HEADER_CONTENT_TYPE, Response::CONTENT_HTML);
    }

    /**
     * Renders the error page for debug mode.
     *
     * @param Renderable $renderable
     *
     * @return Response
     */
    private function renderDebug(Renderable $renderable) : Response
    {
        $data = $this->getDebugData($renderable);

        try {
            $blade = new CustomBladeExtension(self::VIEW_PATH, self::CACHE_PATH, BladeOne::MODE_DEBUG);

            $html = $blade->run('errors.debug-template', $data);
        } catch(\Throwable $ex) {
            die("Fatal error: Error while handling exception");
        }

        return \response()->text($html)->header(Response::HEADER_CONTENT_TYPE, Response::CONTENT_HTML);
    }

    /**
     * Gets the debug data needed for debug error page.
     *
     * @param Renderable $renderable
     *
     * @return array
     */
    private function getDebugData(Renderable $renderable)
    {
        $route_collection = app()->router()->getRoutes();

        return [
            'css' => FileSystem::getContents(self::DEBUG_CSS),
            'js' => FileSystem::getContents(self::DEBUG_JS),
            'favicon' => asset('favicon.ico'),
            'exception' => $renderable,
            'request' => new Request(),
            'stack_trace' => $renderable->trace(),
            'routes' => [
                'get' => $route_collection->getByMethod('get'),
                'post' => $route_collection->getByMethod('post'),
                'put' => $route_collection->getByMethod('put'),
                'patch' => $route_collection->getByMethod('patch'),
                'delete' => $route_collection->getByMethod('delete'),
            ]
        ];
    }
}