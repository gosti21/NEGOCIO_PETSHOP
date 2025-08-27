<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
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
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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
     * Filtrar usuarios por empleados y clientes
     */
    public function scopeSelectRole($query, $selectBy)
    {
        $query->when($selectBy == 1, function ($query) {
            $query->doesntHave('roles');
        })
            ->when($selectBy == 2, function ($query) {
                $query->whereHas('roles', function ($query) {
                    $query->where('name', 'admin');
                });
            });
    }

    public function scopeSearch($query, $search)
    {
        $query->where(function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
            $query->orWhere('last_name', 'like', '%' . $search . '%');
            $query->orWhere('email', 'like', '%' . $search . '%');
        });
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class)->chaperone();
    }

    public function documents()
    {
        return $this->morphOne(DocumentType::class, 'documentable');
    }

    public function phones(): MorphOne
    {
        return $this->morphOne(Phone::class, 'phoneable');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class)->chaperone();
    }
}
