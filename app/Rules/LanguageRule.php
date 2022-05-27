<?php

namespace App\Rules;

use App\Models\Languages;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\App;

class LanguageRule implements Rule
{
    protected array $languages;

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
//        Get all languages from DB to check if GET['lang'] exists or not!
//        Putting code values in array codeList to perform check
        $codeList = [];
        foreach ($this->languages as $code) {
            $codeList[] = $code->code;
        }
//        Check if $lang in array. If not, default to 'en' -> English
//        Currently, throw an error.
        if (!in_array($value, $codeList)) {
//            $value = 'en';
            return false;
        }
//        dd(App::getLocale());
//        Setting locale to the $lang and return true
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
