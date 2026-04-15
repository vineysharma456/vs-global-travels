<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'country_name',
        'flag_emoji',
        'card_image',
        'visa_status',
        'visa_type',
        'visa_fee',
        'processing_days',
        'stay_duration',
        'validity_days',
        'is_published',
        'is_featured',
        'is_visa_free',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured'  => 'boolean',
        'is_visa_free' => 'boolean',
        'visa_fee'     => 'decimal:2',
    ];

    public function documents()
    {
        return $this->belongsToMany(
            VisaTypeDocument::class,
            'country_document',
            'country_id',
            'visa_type_document_id'
        );
    }
}