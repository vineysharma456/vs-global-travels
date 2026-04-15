<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('country_name');
            $table->string('flag_emoji')->nullable();

            // Card image
            $table->string('card_image')->nullable(); // stored file path

            // Visa Info
            
            $table->unsignedInteger('visa_type')->nullable();
            $table->decimal('visa_fee', 8, 2)->default(0);   // USD
            $table->unsignedInteger('processing_days')->nullable();
            $table->unsignedInteger('stay_duration')->nullable();  // days
            $table->unsignedInteger('validity_days')->nullable();   // days (NOT months)

            // Publish flags
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_visa_free')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};