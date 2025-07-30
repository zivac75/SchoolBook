<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory;

    /**
     * Attributs modifiables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'service_id',
        'availability_id',
        'status',
    ];

    /**
     * L'utilisateur ayant effectué la réservation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Le service réservé.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Le créneau de disponibilité réservé.
     */
    public function availability()
    {
        return $this->belongsTo(Availability::class);
    }

    /**
     * Les notifications associées à cette réservation.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
