<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationDocument extends Model
{
    protected $fillable = [
        'application_travellers',
        'visa_type_document_id',
        'doc_type',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size',
    ];

    public function traveller(): BelongsTo
    {
        // Explicit foreign key matches the actual column name
        return $this->belongsTo(ApplicationTraveller::class, 'application_travellers');
    }

    /** Return a URL for the stored file (private disk — serve via signed route) */
    public function getUrlAttribute(): string
    {
        return route('visa.document.serve', $this->id);
    }
}