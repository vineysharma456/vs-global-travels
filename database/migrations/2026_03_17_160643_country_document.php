<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('country_document', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->foreignId('visa_type_document_id')->constrained('visa_type_documents')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['country_id', 'visa_type_document_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_document');
    }
};