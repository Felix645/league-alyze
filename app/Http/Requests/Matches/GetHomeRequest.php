<?php

namespace App\Http\Requests\Matches;

use Artemis\Client\Http\FormRequest;

/**
 * @property string $mode
 */
class GetHomeRequest extends FormRequest
{
    /**
     * @inheritDoc
     */
    protected function rules() : array
    {
        return [
            'mode' => 'required',
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