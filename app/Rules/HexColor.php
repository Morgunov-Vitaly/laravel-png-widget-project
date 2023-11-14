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
            if (!is_string($value)) {
                $fail('The :attribute must be in string HEX format');
            }

            if (strlen($value) < 3) {
                $fail('Too short value. :attribute must be in HEX format.');
            }

            if (strlen($value) > 9) {
                $fail('Too long value. :attribute must be in HEX format.');
            }
        }
    }
}
