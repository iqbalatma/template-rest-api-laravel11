<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Iqbalatma\LaravelJwtAuthentication\Interfaces\JWTSubject;

/**
 * @property string id
 * @property string first_name
 * @property string last_name
 * @property string full_name
 * @property string email
 * @property string phone_number
 * @property string password
 * @property string email_verified_at
 * @property string remember_token
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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


    /**
     * @return Attribute
     */
    protected function full_name(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes["first_name"] . " " . $attributes["last_name"] ?? ""
        );
    }

    /**
     * @return int|string
     */
    public function getJWTIdentifier(): int|string
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims():array
    {
        return [];
    }
}
