<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiscussionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required',  'min:3', 'string', 'max:255'],
            'content' => ['required', 'min:5', 'string'],
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
            'title.required' => 'Le titre est obligatoire',
            'title.min' => 'Le titre doit comporter au moins 3 caractères',
            'title.max' => 'Le titre ne doit pas dépasser 255 caractères',
            'content.required' => 'Le contenu est obligatoire',
            'content.min' => 'Le contenu doit comporter au moins 5 caractères',
        ];
    }
}
