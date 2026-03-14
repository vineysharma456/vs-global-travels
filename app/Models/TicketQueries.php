<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketQueries extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'trip_type',
        'from_airport',
        'to_airport',
        'departure_date',
        'return_date',
        'class',
        'country_code',
        'contact_number',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date'    => 'date',
    ];

    /**
     * Human-readable class label.
     */
    public function getClassLabelAttribute(): string
    {
        return match ($this->class) {
            'economy'         => 'Economy',
            'premium_economy' => 'Premium Economy',
            'business'        => 'Business',
            'first'           => 'First Class',
            default           => ucfirst($this->class),
        };
    }

    /**
     * Human-readable trip type label.
     */
    public function getTripTypeLabelAttribute(): string
    {
        return match ($this->trip_type) {
            'one-way'    => 'One Way',
            'round-trip' => 'Round Trip',
            default      => ucfirst($this->trip_type),
        };
    }
}