<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'is_published' => ['required', 'boolean'],
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
            'title.string' => 'Le titre doit être une chaîne de caractères',
            'title.max' => 'Le titre ne doit pas dépasser 255 caractères',
            'description.required' => 'Le contenu est obligatoire',
            'description.string' => 'Le contenu doit être une chaîne de caractères',
            'image.required' => 'L\'image est obligatoire',
            'image.image' => 'Le fichier doit être une image',
            'image.mimes' => 'Le fichier doit être de type jpg, jpeg, png ou webp',
            'image.max' => 'Le fichier ne doit pas dépasser 2048 Ko',
            'meta_title.string' => 'Méta titre doit être une chaîne de caractères',
            'meta_title.max' => 'Méta titre ne doit pas dépasser 255 caractères',
            'meta_description.string' => 'La méta description doit être une chaîne de caractères',
            'meta_description.max' => 'La méta description ne doit pas dépasser 255 caractères',
            'meta_keywords.string' => 'Les mots-clés de la méta description doivent être une chaîne de caractères',
            'meta_keywords.max' => 'Les mots-clés de la méta description ne doivent pas dépasser 255 caractères',
            'is_published.required' => 'La publication est obligatoire',
            'is_published.boolean' => 'La publication doit être un vrai ou un faux',
        ];
    }
}
