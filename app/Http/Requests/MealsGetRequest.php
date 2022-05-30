<?php

/**
 * This file contains form request for route /api/meals.
 */

namespace App\Http\Requests;

use App\Rules\LanguageRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * MealsGetRequest is a FormRequest class for route /api/meals.
 */
class MealsGetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        /**
         * If one of the parameters is not correct, user is redirected to start with lang='en'.
         */
        $this->redirect = 'api/meals?lang=en';

        /**
         * 'per_page' & 'page' - https://laravel.com/docs/9.x/validation#rule-integer combine with numeric
         * 'category' - No leading 0 or 0 (only choice is int greater than 0, NULL and !NULL (case sensitive))
         * 'tags' - String with numbers only with comma separator between (no comma after last number)
         * 'with' - One of those words (category|tags|ingredients) repeated 1-3 times with comma separation
         * 'lang' - special rule that checks if value exists in languages table
         * 'diff_time' - UNIX timestamp greater than 0
         */
        return [
            'per_page' => [
                'integer',
                'numeric',
                'min:1'
            ],
            'page' => [
                'integer',
                'numeric',
                'min:1'
            ],
            'category' => [
                'regex:/^(?:[1-9][0-9]*|NULL|!NULL)$/'
            ],
            'tags' => [
                'regex:/^\d+(?:,\d+)*$/'
            ],
            'with' => [
                'regex:/^((tags|ingredients|category)+,?){1,3}$/'
            ],
            'lang'  => [
                'required',
                new LanguageRule()
            ],
            'diff_time' => [
                'regex:/^\d+$/'
            ]
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'per_page' => $this->per_page ?? 10,
            'page' => $this->page ?? 1
        ]);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
        ];
    }
}
