<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VisaApplication extends Model
{
    protected $fillable = [
        'application_ref',
        'country_id',
        'payment_status',
        'total_amount',
        'currency',
        'payment_gateway_ref',
        'ip_address',
    ];

    public function travelers(): HasMany
    {
        return $this->hasMany(ApplicationTraveller::class)->orderBy('traveler_index');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /** Generate a human-readable unique reference like VA-20260420-AB12 */
    public static function generateRef(): string
    {
        do {
            $ref = 'VA-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
        } while (self::where('application_ref', $ref)->exists());

        return $ref;
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function visaPayment()
    {
        return $this->hasOne(Payment::class, 'application_id');
    }
}