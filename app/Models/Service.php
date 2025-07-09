<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory;

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
