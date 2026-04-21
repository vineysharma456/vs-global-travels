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
        Schema::create('visa_applications', function (Blueprint $table) {
           
            $table->id();
            $table->string('application_ref')->unique(); // e.g. VA-20260420-AB12
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->string('payment_status')->default('pending'); // pending | paid | failed | refunded
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->string('payment_gateway_ref')->nullable(); // razorpay / stripe order id
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visa_applications');
    }
};
