<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationTraveller extends Model
{
    protected $fillable = [
            'visa_application_id',
            'traveler_index',
            'full_name',
            'passport_number',
            'nationality',
            'date_of_birth',
            'passport_expiry',
            'gender',
            'mrz_line1',
            'mrz_line2',

            // ✅ NEW FIELDS
            'mobile',
            'email',
            'place_of_issue',
        ];

    protected $casts = [
        'date_of_birth'   => 'date',
        'passport_expiry' => 'date',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(VisaApplication::class, 'visa_application_id');
    }

    public function documents(): HasMany
    {
        // Explicit foreign key matches the actual column name in the migration
        return $this->hasMany(ApplicationDocument::class, 'application_travellers');
    }
}