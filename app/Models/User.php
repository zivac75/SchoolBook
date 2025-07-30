<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'code_utilise_id',
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

    /**
     * Les réservations effectuées par cet utilisateur.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Les créneaux (availabilities) de cet API.
     */
    public function availabilities()
    {
        return $this->hasMany(\App\Models\Availability::class, 'user_id');
    }

    /**
     * Vérifie si l'utilisateur est admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
