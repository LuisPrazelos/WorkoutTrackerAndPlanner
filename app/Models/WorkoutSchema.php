<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkoutSchema extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'difficulty',
        'scheduled_at',
        'is_template',
        'source_schema_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'scheduled_at' => 'date',
        'is_template' => 'boolean',
    ];

    /**
     * De gebruiker waartoe dit schema behoort.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * De exercise logs die bij dit schema horen.
     */
    public function exerciseLogs(): HasMany
    {
        return $this->hasMany(ExerciseLog::class);
    }

    /**
     * De geplande oefeningen voor dit schema.
     */
    public function schemaExercises(): HasMany
    {
        return $this->hasMany(SchemaExercise::class);
    }
}
