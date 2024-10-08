<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:16',
                Rule::unique('users'),
            ],
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => [
                'required',
                'confirmed',
                $this->isPrecognitive()
                    ? Password::min(8)
                    : Password::min(8)->uncompromised(),
            ],
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
            'name.required' => 'Le nom d\'utilisateur est requis.',
            'name.string' => 'Le nom d\'utilisateur doit être une chaîne de caractères.',
            'name.min' => 'Le nom d\'utilisateur doit contenir au moins 3 caractères.',
            'name.max' => 'Le nom d\'utilisateur ne peut pas dépasser 16 caractères.',
            'name.unique' => 'Le nom d\'utilisateur est déjà pris.',
            'email.required' => 'L\'adresse e-mail est requise.',
            'email.string' => 'L\'adresse e-mail doit être une chaîne de caractères.',
            'email.lowercase' => 'L\'adresse e-mail doit être en minuscules.',
            'email.email' => 'L\'adresse e-mail doit être une adresse e-mail valide.',
            'email.max' => 'L\'adresse e-mail ne peut pas dépasser 255 caractères.',
            'email.unique' => 'L\'adresse e-mail est déjà utilisée.',
            'password.required' => 'Le mot de passe est requis.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.uncompromised' => 'Le mot de passe a été compromis dans une fuite de données.',
        ];
    }
}
