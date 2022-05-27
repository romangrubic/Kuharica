<?php

namespace App\Http\Requests;

use App\Models\Languages;
use App\Rules\LanguageRule;
use Illuminate\Foundation\Http\FormRequest;

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
        return [
//            https://laravel.com/docs/9.x/validation#rule-integer combine with numeric
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
//            No leading 0 or just 0
            'category' => [
                'regex:/^(?:[1-9][0-9]*|NULL|!NULL)$/'
            ],
//            String with numbers only with comma separator between (no comma after last number)
            'tags' => [
                'regex:/^\d+(?:,\d+)*$/'
            ],
            'with' => [
                'regex:/^\b(tags|ingredients|category)\b/'
            ],
            'lang'  => [
                'required',
                new LanguageRule()
            ],
            'diff_time' => [
                'regex:/^\d{10}$/'
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
            'page' => $this->page ?? 1,
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
//            'per_page.integer' => 'A title is required',
//            'body.required' => 'A message is required',
        ];
    }
}
