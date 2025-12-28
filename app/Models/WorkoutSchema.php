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
}
