<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modèle Eloquent pour les créneaux de disponibilité (Availability)
 *
 * @property int $id
 * @property int $service_id
 * @property \Carbon\Carbon $start_datetime
 * @property \Carbon\Carbon $end_datetime
 * @property int $capacity
 * @property \Carbon\Carbon|null $deleted_at
 */
class Availability extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Statuts possibles pour un créneau.
     */
    public const STATUS_AVAILABLE = 'available';
    public const STATUS_RESERVED = 'reserved';

    /**
     * Attributs modifiables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'service_id',
        'user_id',
        'start_datetime',
        'end_datetime',
        'status',
    ];

    /**
     * Casts automatiques des attributs.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime'   => 'datetime',
        'deleted_at'     => 'datetime',
        'status'         => 'string',
    ];

    /**
     * Le service auquel appartient ce créneau de disponibilité.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Les réservations associées à ce créneau de disponibilité.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * L'API (utilisateur) propriétaire de ce créneau.
     */
    public function api()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
