<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Widgets;

use App\Rules\HexColor;
use Illuminate\Foundation\Http\FormRequest;

class WidgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'width' => [
                'integer',
                'min:100',
                'max:500',
            ],
            'height' => [
                'integer',
                'min:100',
                'max:500',
            ],
            'color' => [new HexColor('color')],
            'bgcolor' => [new HexColor('bgcolor')],
        ];
    }
}
