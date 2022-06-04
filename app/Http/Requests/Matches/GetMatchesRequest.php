<?php

namespace App\Http\Requests\Matches;

use Artemis\Client\Http\FormRequest;

/**
 * @property int $mode
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
            'mode' => 'required',
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
