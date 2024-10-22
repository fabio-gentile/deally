<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserForYouHomeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'categories' => 'array',
            'categories.*' => 'integer', 'exists:category_deals,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'categories.array' => 'Les catégories doivent être un tableau.',
            'categories.*.integer' => 'Les catégories doivent être des entiers.',
            'categories.*.exists' => 'Les catégories sélectionnées ne sont pas valides.',
        ];
    }
}
