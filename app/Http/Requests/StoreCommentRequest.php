<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:512', 'min:1'],
            'parent_id' => ['nullable', 'integer'],
            'content_id' => ['required', 'integer'],
            'answer_to' => ['nullable', 'integer'],
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
            'content.required' => 'Le contenu est requis.',
            'content.string' => 'Le contenu doit être une chaîne de caractères.',
            'content.max' => 'Le contenu ne doit pas dépasser 512 caractères.',
            'content.min' => 'Le contenu doit contenir au moins 1 caractère.',
            'parent_id.integer' => 'L\'identifiant du parent doit être un entier.',
            'content_id.required' => 'L\'identifiant du contenu est requis.',
            'content_id.integer' => 'L\'identifiant du contenu doit être un entier.',
            'answer_to.integer' => 'L\'identifiant de la réponse doit être un entier.',
        ];
    }
}
