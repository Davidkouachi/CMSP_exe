<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class user extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'api_token',
        'user_username',
        'user_first_name',
        'user_last_name',
        'user_phone',
        'user_profil_id',
        'user_email',
        'user_password',
        'user_rights',
        'user_make_date',
        'user_revised_date',
        'user_ip',
        'user_history',
        'user_logs',
        'user_lang',
        'user_photo',
        'user_actif',
        'user_actions',
        'code_personnel',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_password',
        // 'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // 'email_verified_at' => 'datetime',
            'user_password' => 'hashed',
        ];
    }

}
