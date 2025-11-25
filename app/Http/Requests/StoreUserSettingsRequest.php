<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserSettingsRequest extends FormRequest
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
            'settings' => 'required|array',
            'settings.theme' => 'nullable|in:light,dark,system',
            'settings.accent_color' => 'nullable|in:indigo,lime,red,orange,cyan,purple',
            'settings.notifications' => 'nullable|array',
            'settings.notifications.account_alerts' => 'nullable|boolean',
            'settings.notifications.weekly_summary' => 'nullable|boolean',
            'settings.notifications.goals_achieved' => 'nullable|boolean',
        ];
    }
}
