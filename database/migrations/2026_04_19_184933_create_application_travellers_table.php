<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('application_travellers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visa_application_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('traveler_index'); // 0-based, matches JS travelers[]
            $table->string('full_name')->nullable();
 
            // Passport OCR data (null if not scanned / not front uploaded)
            $table->string('passport_number')->nullable();
            $table->string('nationality')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('passport_expiry')->nullable();
            $table->string('gender')->nullable();
            $table->string('mrz_line1')->nullable();
            $table->string('mrz_line2')->nullable();
 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_travellers');
    }
};
