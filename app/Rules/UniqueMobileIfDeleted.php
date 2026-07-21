<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueMobileIfDeleted implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = DB::table('user_registrations')
            ->where('mobile', $value)
            ->where('isDelete', 0)
            ->exists();

        if ($exists) {
            $fail('This mobile number is already registered.');
        }
    }
}
