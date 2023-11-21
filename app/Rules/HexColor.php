<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HexColor implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (isset($value, $attribute)) {

            $pattern = '/^#?([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6}|[A-Fa-f0-9]{8})$/';
            // fff #fff #ffffff #4559a4b5 (последнее содержит информацию о прозрачности)
            if (!preg_match($pattern, $value)) {
                $fail('Incorrect value. :attribute must be in HEX format.');
            }
        }
    }
}
