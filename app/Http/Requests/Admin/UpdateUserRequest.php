<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use App\Rules\NoSpaces;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:16',
                'regex:/^[a-zA-Z0-9 ]+$/',
                new NoSpaces(),
                Rule::unique('users')->ignore($this->user()->id),
            ],
            'biography' => [
                'nullable',
                'string',
                'max:512',
            ],
            'isAvatarRemoved' => [
                'nullable',
                'boolean',
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
            'name.required' => 'Le nom d\'utilisateur est obligatoire.',
            'name.string' => 'Le nom d\'utilisateur doit être une chaîne de caractères.',
            'name.min' => 'Le nom d\'utilisateur doit contenir au moins 3 caractères.',
            'name.max' => 'Le nom d\'utilisateur ne peut pas dépasser 16 caractères.',
            'name.no_spaces' => 'Le nom d\'utilisateur ne peut pas contenir d\'espaces.',
            'name.unique' => 'Ce nom d\'utilisateur est déjà pris.',
            'biography.string' => 'La biographie doit être une chaîne de caractères.',
            'biography.max' => 'La biographie ne peut pas dépasser 512 caractères.',
            'regex' => 'Le nom d\'utilisateur ne doit contenir que des lettres et des chiffres.',
        ];
    }
}
