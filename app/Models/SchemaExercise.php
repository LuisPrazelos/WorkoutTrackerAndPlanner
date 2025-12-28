<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchemaExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'workout_schema_id',
        'exercise_id',
        'target_sets',
        'target_reps',
    ];

    public function workoutSchema(): BelongsTo
    {
        return $this->belongsTo(WorkoutSchema::class);
    }

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }
}
