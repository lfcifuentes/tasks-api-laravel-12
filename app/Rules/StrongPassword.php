<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $hasNumber = preg_match('/[0-9]/', $value);
        $hasSpecialChar = preg_match('/[^A-Za-z0-9]/', $value);
        $isValidLength = strlen($value) >= 8 && strlen($value) <= 16;

        if (!$hasNumber) {
            $fail(trans('validation.custom.password.strong_password.number'));
        }

        if (!$hasSpecialChar) {
            $fail(trans('validation.custom.password.strong_password.special'));
        }

        if (!$isValidLength) {
            $fail(trans('validation.custom.password.strong_password.length'));
        }
    }
}
