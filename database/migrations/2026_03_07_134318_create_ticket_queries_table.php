<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_queries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->enum('trip_type', ['one-way', 'round-trip']);
            $table->string('from_airport', 10);
            $table->string('to_airport', 10);
            $table->date('departure_date');
            $table->date('return_date')->nullable();
            $table->enum('class', ['economy', 'premium_economy', 'business', 'first']);
            $table->string('country_code', 10);
            $table->string('contact_number', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};