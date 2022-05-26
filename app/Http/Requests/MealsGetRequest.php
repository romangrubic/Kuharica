<?php

namespace App\Http\Requests;

use App\Rules\CategoryRule;
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
            'category' => [
                'regex:/^(?:\d+|NULL|!NULL)$/'
            ],


//            'lang'  => [
//                'required',
//                'string',
//                'size:2'
//            ],
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
            'page' => $this->per_page ?? 1,
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
            'per_page.integer' => 'A title is required',
//            'body.required' => 'A message is required',
        ];
    }
}
