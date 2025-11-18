<?php

namespace App\Http\Requests;

use App\Models\Wallet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWalletRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Gera o slug a partir do nome se fornecido
        if ($this->has('name')) {
            $slug = \Illuminate\Support\Str::slug($this->input('name'));
            $this->merge([
                'slug' => $slug,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $wallet = $this->route('wallet');

        return [
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:2',
            'description' => 'nullable|string|max:255',
            'balance' => 'nullable|numeric|min:0',
            'active' => 'boolean',
            'is_default' => 'boolean',
            'type' => 'required|in:generic,saving,investment',
            'color' => 'required|in:indigo,emerald,purple,gray,pink',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Wallet::class)->ignore($wallet->id),
            ],
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
            'balance.min' => 'O saldo não pode ser negativo',
            'balance.numeric' => 'O saldo deve ser um valor numérico',
            'type.required' => 'O tipo da carteira é obrigatório',
            'type.in' => 'O tipo da carteira deve ser: geral, poupança ou investimento',
            'color.required' => 'A cor da carteira é obrigatória',
            'color.in' => 'A cor selecionada é inválida',
            'slug.unique' => 'Já existe uma carteira com este nome. Por favor, escolha outro nome.',
        ];
    }
}

