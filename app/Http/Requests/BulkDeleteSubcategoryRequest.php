<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkDeleteSubcategoryRequest extends FormRequest
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
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:subcategories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'ids.required' => 'os IDs das subcategorias são obrigatórios',
            'ids.array' => 'os IDs devem ser um array',
            'ids.*.required' => 'cada ID é obrigatório',
            'ids.*.integer' => 'cada ID deve ser um número inteiro',
            'ids.*.exists' => 'uma ou mais subcategorias não foram encontradas',
        ];
    }
}

