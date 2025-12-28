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
        // Adds the 'scheduled_at' column to the workout_schemas table
        Schema::table('workout_schemas', function (Blueprint $table) {
            $table->date('scheduled_at')->nullable()->after('difficulty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workout_schemas', function (Blueprint $table) {
            $table->dropColumn('scheduled_at');
        });
    }
};
