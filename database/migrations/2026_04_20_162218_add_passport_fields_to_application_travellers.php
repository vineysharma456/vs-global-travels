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
        Schema::table('application_travellers', function (Blueprint $table) {
            $table->string('mobile')->nullable()->after('passport_number');
            $table->string('email')->nullable()->after('mobile');
            $table->string('place_of_issue')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('application_travellers', function (Blueprint $table) {

            $table->dropColumn([
                'mobile',
                'email',
                'place_of_issue'
            ]);

        });
    }
};
