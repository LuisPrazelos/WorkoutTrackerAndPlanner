<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workout_schemas', function (Blueprint $table) {
            $table->string('category')->default('general')->after('difficulty');
            $table->string('goal')->default('general')->after('category');
        });
    }

    public function down(): void
    {
        Schema::table('workout_schemas', function (Blueprint $table) {
            $table->dropColumn(['category', 'goal']);
        });
    }
};
