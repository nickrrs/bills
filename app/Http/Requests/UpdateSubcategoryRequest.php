<?php

namespace App\Http\Requests;

use App\Models\Subcategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubcategoryRequest extends FormRequest
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
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Subcategory::class)->ignore($this->subcategory->id),
            ],
            'icon' => 'required|string',
            'color' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'o nome da subcategoria é obrigatório',
            'name.max' => 'o nome da subcategoria não pode ter mais de 30 caracteres',
            'slug.required' => 'o slug da subcategoria é obrigatório',
            'slug.max' => 'o slug da subcategoria não pode ter mais de 255 caracteres',
            'slug.unique' => 'já existe uma subcategoria com este nome. por favor, escolha outro nome.',
            'icon.required' => 'o ícone da subcategoria é obrigatório',
            'color.required' => 'a cor da subcategoria é obrigatória',
            'category_id.required' => 'a categoria da subcategoria é obrigatória',
            'category_id.exists' => 'a categoria da subcategoria não foi encontrada',
        ];
    }
}
