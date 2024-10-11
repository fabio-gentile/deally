<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDealRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255', 'min:5'],
            'description' => ['required', 'string', 'max:1024', 'min:20'],
            'original_price' => ['nullable', 'numeric', 'min:0', 'gt:price'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'expiration_date' => ['required', 'date', 'after:start_date'],
            'start_date' => ['required', 'date'],
            'promo_code' => ['nullable', 'string', 'max:255'],
            'deal_url' => ['required', 'string', 'max:255'],
            'category' => ['required', 'exists:category_deals,name'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'uploaded_images' => ['nullable', 'array'], // Valide chaque image
        ];
    }

    /**
     * Configure the validator instance.
     */
    protected function withValidator($validator): void
    {
        $validator->sometimes('original_price', 'required|numeric|min:0|gt:price', function ($input) {
            return !is_null($input->price); // if price is not null, original_price is required and must be greater than price
        });
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'title.string' => 'Le titre doit être une chaîne de caractères.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'title.min' => 'Le titre doit contenir au moins 5 caractères.',
            'description.required' => 'La description est obligatoire.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.max' => 'La description ne peut pas dépasser 1024 caractères.',
            'description.min' => 'La description doit contenir au moins 20 caractères.',
            'original_price.numeric' => 'Le prix original doit être un nombre.',
            'original_price.min' => 'Le prix original doit être au moins 0.',
            'original_price.gt' => 'Le prix original doit être supérieur au prix actuel.',
            'original_price.required' => 'Le prix original est requis.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix doit être au moins 0€.',
            'expiration_date.required' => 'La date d\'expiration est obligatoire.',
            'expiration_date.date' => 'La date d\'expiration doit être une date valide.',
            'expiration_date.after' => 'La date d\'expiration doit être postérieure à la date de début.',
            'start_date.required' => 'La date de début est obligatoire.',
            'start_date.date' => 'La date de début doit être une date valide.',
            'promo_code.string' => 'Le code promotionnel doit être une chaîne de caractères.',
            'promo_code.max' => 'Le code promotionnel ne peut pas dépasser 255 caractères.',
            'deal_url.required' => 'Le lien du deal est obligatoire.',
            'deal_url.string' => 'Le lien du deal doit être une chaîne de caractères.',
            'deal_url.max' => 'Le lien du deal ne peut pas dépasser 255 caractères.',
            'category.required' => 'La catégorie est obligatoire.',
            'category.exists' => 'La catégorie sélectionnée est invalide.',
            'images.*.image' => 'Le fichier doit être une image.',
            'images.*.mimes' => 'L\'image doit être au format jpg, jpeg, png ou webp.',
            'images.*.max' => 'L\'image ne peut pas dépasser 2 Mo.',
        ];
    }
}
