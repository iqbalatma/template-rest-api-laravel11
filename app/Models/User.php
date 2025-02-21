<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Table;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Iqbalatma\LaravelJwtAuthentication\Interfaces\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

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
 * @property Profile profile
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasUuids, HasRoles;

    protected $table = Table::USERS->value;

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
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->firstname . ' ' . $this->lastname,
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


    /**
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, "id", "id");
    }
}
