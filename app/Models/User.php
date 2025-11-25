<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function category()
    {
        return $this->hasMany(Category::class);
    }

    public function wallet()
    {
        return $this->hasMany(Wallet::class);
    }

    public function settings()
    {
        return $this->hasMany(UserSetting::class);
    }

    /**
     * Obtém as configurações do usuário formatadas
     */
    public function getSettings(): array
    {
        $userSettings = $this->settings()->get();

        $settings = [
            'theme' => 'system',
            'accent_color' => 'indigo',
            'notifications' => [
                'account_alerts' => false,
                'weekly_summary' => false,
                'goals_achieved' => false,
            ],
        ];

        foreach ($userSettings as $setting) {
            if ($setting->key === 'notifications') {
                $settings['notifications'] = json_decode($setting->value, true) ?? $settings['notifications'];
            } else {
                $settings[$setting->key] = $setting->value;
            }
        }

        return $settings;
    }
}
