<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'status',
        'course_tags',
        'reapply_cooldown_days',
    ];

    protected $casts = [
        'course_tags' => 'array',
        'reapply_cooldown_days' => 'integer',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(CourseApplication::class, 'course_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}