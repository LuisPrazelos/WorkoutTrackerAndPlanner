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
        Schema::table('workout_schemas', function (Blueprint $table) {
            $table->boolean('is_template')->default(false)->after('difficulty');
            $table->foreignId('source_schema_id')->nullable()->constrained('workout_schemas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workout_schemas', function (Blueprint $table) {
            $table->dropForeign(['source_schema_id']);
            $table->dropColumn(['is_template', 'source_schema_id']);
        });
    }
};
