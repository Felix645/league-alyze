<?php

namespace Artemis\Core\Http\Traits;

use Artemis\Core\Http\Exceptions\RequestException;

trait hasAjaxRedirectionCheck
{
    /**
     * Ajax bypass identifier.
     *
     * @var bool
     */
    private $ajax_bypass = false;

    /**
     * @inheritDoc
     */
    public function forceRedirect()
    {
        $this->ajax_bypass = true;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function bypassAjaxCheck()
    {
        return $this->ajax_bypass;
    }

    /**
     * Checks if the request was made via ajax and throws an exception if true.
     *
     * @throws RequestException
     *
     * @return void
     */
    private function checkAjax()
    {
        if( (request()->isAjax() || request()->needsJson()) && !$this->bypassAjaxCheck() ) {
            throw new RequestException('Redirection attempt on ajax call');
        }
    }
}