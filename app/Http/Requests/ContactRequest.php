<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'message' => ['required', 'string', 'max:1024'],
            'subject' => ['required', 'string', 'in:Signaler un contenu inapproprié,Proposer une amélioration,Offre commerciale,Signaler un bug,Problème de connexion,Autre'],
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
            'name.required' => 'Le nom est requis.',
            'name.string' => 'Le nom doit être une chaîne de caractères.',
            'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email doit être valide.',
            'message.required' => 'Le message est requis.',
            'message.string' => 'Le message doit être une chaîne de caractères.',
            'message.max' => 'Le message ne doit pas dépasser 1024 caractères.',
            'subject.required' => 'Le sujet est requis.',
            'subject.string' => 'Le sujet doit être une chaîne de caractères.',
            'subject.in' => 'Le sujet doit être l\'un des suivants : Signaler un contenu inapproprié, Proposer une amélioration, Offre commerciale, Signaler un bug, Problème de connexion, Autre.',
            'recaptcha.required' => 'Le captcha est requis.',
        ];
    }
}
