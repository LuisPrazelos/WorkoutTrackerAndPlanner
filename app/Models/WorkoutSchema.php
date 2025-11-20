<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
