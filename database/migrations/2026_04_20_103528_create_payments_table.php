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
       Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('application_id');

            // Razorpay details
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_signature')->nullable();

            // Amounts
            $table->decimal('amount', 10, 2);
            $table->decimal('service_fee', 10, 2)->default(0);

            // Status
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');

            $table->timestamps();

            // Optional FK
            $table->foreign('application_id')->references('id')->on('visa_applications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
