<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExerciseLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'exercise_id',
        'workout_schema_id',
        'sets',
        'reps',
        'weight',
        'notes',
    ];

    /**
     * De gebruiker die deze log heeft aangemaakt.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * De oefening die is gelogd.
     */
    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    /**
     * Het schema waartoe deze log behoort.
     */
    public function workoutSchema(): BelongsTo
    {
        return $this->belongsTo(WorkoutSchema::class);
    }
}
