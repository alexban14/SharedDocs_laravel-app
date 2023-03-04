<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IntegerArray implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // we loop thru the values array and make sure if every one of them is an integer
        $isInteger = collect($value)->every( fn($element) => is_int($element) );

        if(!$isInteger) {
            $fail(  $attribute . ' field can only be integers.');
        }
    }
}
