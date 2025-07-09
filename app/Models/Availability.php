<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    /** @use HasFactory<\Database\Factories\AvailabilityFactory> */
    use HasFactory;

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
}
