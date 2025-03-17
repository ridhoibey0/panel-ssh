<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    protected $connection = 'mysql';
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
 
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'google_id',
        'status',
        'balance',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
     protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
     protected $appends = [
        'profile_photo_url',
    ];

    public function giftCodes()
    {
        return $this->hasMany(GiftCode::class);
    }
    
    public function licenses()
    {
        return $this->setConnection('mysql2')->hasMany(License::class);
    }
    
    public function getProfilePhotoUrlAttribute()
{
    // Logika untuk mengembalikan URL foto profil pengguna
    // Misalnya, jika foto profil disimpan dalam direktori publik "profile-photos",
    // Anda dapat mengembalikan URL dengan menggunakan:
    // return asset('profile-photos/' . $this->profile_photo);
}

}
