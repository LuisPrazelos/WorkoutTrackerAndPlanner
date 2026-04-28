<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

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
        'category',
        'goal',
        'scheduled_at',
        'is_template',
        'is_active',
        'active_started_at',
        'source_schema_id',
        'is_public',
        'share_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'scheduled_at' => 'date',
        'is_template' => 'boolean',
        'is_active' => 'boolean',
        'active_started_at' => 'datetime',
        'is_public' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::creating(function ($schema) {
            if (empty($schema->share_token)) {
                $schema->share_token = \Illuminate\Support\Str::random(64);
            }
        });
    }

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

    /**
     * Maak een persoonlijke kopie van dit schema voor een gebruiker.
     */
    public function assignToUser(User $user, array $attributes = []): self
    {
        $existingSchema = static::where('user_id', $user->id)
            ->where('source_schema_id', $this->id)
            ->where('is_template', false)
            ->first();

        if ($existingSchema) {
            return $existingSchema;
        }

        return DB::transaction(function () use ($user, $attributes) {
            $assignedSchema = $this->replicate([
                'share_token',
                'is_active',
                'active_started_at',
                'is_template',
                'is_public',
            ])->fill(array_merge([
                'user_id' => $user->id,
                'is_template' => false,
                'is_public' => false,
                'is_active' => false,
                'active_started_at' => null,
                'source_schema_id' => $this->id,
                'scheduled_at' => null,
            ], $attributes));

            $assignedSchema->save();

            foreach ($this->schemaExercises as $exercise) {
                $assignedSchema->schemaExercises()->create([
                    'exercise_id' => $exercise->exercise_id,
                    'target_sets' => $exercise->target_sets,
                    'target_reps' => $exercise->target_reps,
                ]);
            }

            return $assignedSchema;
        });
    }
}
