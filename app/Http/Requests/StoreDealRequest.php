<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDealRequest extends FormRequest
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
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // Valide chaque image
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
            'expiration_date.after' => 'La :attribute doit être postérieure à la date de début.',
            'original_price.gt' => 'Le prix original doit être supérieur au prix actuel.',
        ];
    }
}
