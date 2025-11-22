<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'name' => 'required|string|max:30',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'icon' => 'required|string',
            'color' => 'required|string',
            'type' => 'required|in:expense,recept',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'o nome da categoria é obrigatório',
            'name.max' => 'o nome da categoria não pode ter mais de 30 caracteres',
            'slug.required' => 'o slug da categoria é obrigatório',
            'slug.max' => 'o slug da categoria não pode ter mais de 255 caracteres',
            'slug.unique' => 'já existe uma categoria com este nome. por favor, escolha outro nome.',
            'icon.required' => 'o ícone da categoria é obrigatório',
            'color.required' => 'a cor da categoria é obrigatória',
            'type.required' => 'o tipo da categoria é obrigatório',
            'type.in' => 'o tipo da categoria deve ser "despesa" ou "receita"',
        ];
    }
}
