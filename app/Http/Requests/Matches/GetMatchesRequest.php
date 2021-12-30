<?php

namespace App\Http\Requests\Matches;

use Artemis\Client\Http\FormRequest;

/**
 * @property int $page
 */
class GetMatchesRequest extends FormRequest
{
    /**
     * @inheritDoc
     */
    protected function rules() : array
    {
        return [
            'page' => 'required|int|min:1'
        ];
    }

    /**
     * @inheritDoc
     */
    protected function fails() : string
    {
        return api()->badRequest();
    }
}
