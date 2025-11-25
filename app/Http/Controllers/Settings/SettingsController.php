<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserSettingsRequest;
use App\Models\UserSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SettingsController extends Controller
{

    public function index()
    {
        return Inertia::render('settings/General');
    }

    public function apiStore(StoreUserSettingsRequest $request): JsonResponse
    {
        $user = $request->user();
        $settings = $request->input('settings');

        DB::transaction(function () use ($user, $settings) {
            foreach ($settings as $key => $value) {
                if (is_array($value)) {
                    UserSetting::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'key' => $key,
                        ],
                        [
                            'value' => json_encode($value),
                        ]
                    );
                } else {
                    UserSetting::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'key' => $key,
                        ],
                        [
                            'value' => $value,
                        ]
                    );
                }
            }
        });

        return response()->json([
            'message' => 'configurações salvas com sucesso',
            'settings' => $this->getUserSettings($user->id),
        ]);
    }

    public function apiIndex(Request $request): JsonResponse
    {
        $user = $request->user();
        $settings = $this->getUserSettings($user->id);

        return response()->json(['settings' => $settings]);
    }

    private function getUserSettings(int $userId): array
    {
        $user = \App\Models\User::find($userId);
        return $user ? $user->getSettings() : [];
    }
}

