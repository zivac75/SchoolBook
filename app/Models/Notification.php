<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /** @use HasFactory<\Database\Factories\NotificationFactory> */
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'type',
        'sent_at',
    ];

    /**
     * La réservation associée à cette notification.
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
