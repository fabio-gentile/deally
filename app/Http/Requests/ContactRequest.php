<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use TimeHunter\LaravelGoogleReCaptchaV3\Validations\GoogleReCaptchaV3ValidationRule;

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
            'recaptcha' => ['required', new GoogleReCaptchaV3ValidationRule('contact_us')],
        ];
    }
}
