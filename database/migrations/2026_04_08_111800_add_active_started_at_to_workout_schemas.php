<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workout_schemas', function (Blueprint $table) {
            $table->timestamp('active_started_at')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('workout_schemas', function (Blueprint $table) {
            $table->dropColumn('active_started_at');
        });
    }
};
