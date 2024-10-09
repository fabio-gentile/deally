<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDiscussionRequest extends FormRequest
{
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
            'category' => ['required', 'exists:category_deals,name'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
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
            'category.required' => 'La catégorie est obligatoire',
            'category.exists' => 'La catégorie n\'existe pas',
            'thumbnail.image' => 'Le fichier doit être une image',
            'thumbnail.mimes' => 'Le fichier doit être de type jpg, jpeg, png ou webp',
            'thumbnail.max' => 'Le fichier ne doit pas dépasser 512 Ko',
        ];
    }
}
