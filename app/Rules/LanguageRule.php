<?php

/**
 * This file contains rule for GET parameter lang.
 */

namespace App\Rules;

use App\Models\Languages;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;

/**
 * LanguageRule is a rule class for GET parameter lang.
 */
class LanguageRule implements Rule
{
    /**
     * Contains available languages codes from languages table
     *
     * @var Collection
     */
    protected Collection $languages;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->languages = Languages::readCode();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        /**
         * Get all languages from DB to check if GET['lang'] exists or not!
         * Putting code values in array codeList to perform check
         */
        $codeList = [];
        foreach ($this->languages as $code) {
            $codeList[] = $code->code;
        }

        /**
         * Check if $lang in array. If not, default to 'en' -> English
         * Currently, throw an error.
         */
        if (!in_array($value, $codeList)) {
//            $value = 'en';
            return false;
        }

        /**
         * Setting locale to the $lang and return true
         */
        App::setLocale($value);
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Default language is English (en)';
    }
}
