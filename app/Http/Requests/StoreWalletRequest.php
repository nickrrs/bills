<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWalletRequest extends FormRequest
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
            'name' => 'required|string|max:20',
            'icon' => 'nullable|string',
            'user_id' => 'sometimes|required|exists:users,id',
            'active' => 'required|boolean',
            'slug' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'is_default' => 'required|boolean',
            'balance' => 'required|numeric|min:0',
            'color' => 'nullable|string|max:255',
            'type' => 'required|in:generic,saving,investment',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome da carteira é obrigatório',
            'name.max' => 'O nome da carteira não pode ter mais de 24 caracteres',
            'balance.min' => 'O saldo não pode ser negativo',
            'balance.required' => 'O saldo é obrigatório',
            'balance.numeric' => 'O saldo deve ser um valor numérico',
        ];
    }
}
