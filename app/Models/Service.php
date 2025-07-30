<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Les attributs modifiables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'duration_minutes',
        'is_active',
    ];

    /**
     * Les attributs castés automatiquement.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'duration_minutes' => 'integer',
        'deleted_at' => 'datetime',
    ];

    /**
     * Les créneaux de disponibilité associés à ce service.
     */
    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    /**
     * Les réservations associées à ce service.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
