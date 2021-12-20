<?php

namespace App\Http\Requests\Matches;

use Artemis\Client\Facades\Validation;
use Artemis\Client\Http\FormRequest;
use Artemis\Core\Interfaces\RedirectionInterface;

/**
 * @property int $is_win
 * @property int $role_id
 * @property string $played_as
 * @property string $played_against
 * @property int $kills
 * @property int $deaths
 * @property int $assists
 * @property int $creep_score
 * @property int $minutes
 * @property int $seconds
 */
class CreateMatchRequest extends FormRequest
{
    /**
     * @inheritDoc
     */
    protected function rules() : array
    {
        return [
            'is_win'            => ['int', 'min:0', 'max:1', 'default:0'],
            'role_id'           => ['required', 'int', 'min:0'],
            'played_as'         => ['required', 'int', 'min:0'],
            'played_against'    => ['required', 'int', 'min:0', 'different:played_as'],
            'kills'             => ['required', 'int', 'min:0'],
            'deaths'            => ['required', 'int', 'min:0'],
            'assists'           => ['required', 'int', 'min:0'],
            'creep_score'       => ['required', 'int', 'min:0'],
            'minutes'           => ['required', 'int', 'min:0'],
            'seconds'           => ['required', 'int', 'min:0']
        ];
    }

    /**
     * @inheritDoc
     */
    protected function fails() : RedirectionInterface
    {
        return redirect()->back();
    }
}
