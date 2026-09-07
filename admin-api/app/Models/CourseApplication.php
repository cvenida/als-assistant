<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseApplication extends Model
{
    protected $primaryKey = 'application_id';

    protected $fillable = [
        'course_id',
        'user_id',
        'status',
        'reapply_eligible_at',
    ];

    protected $casts = [
        'reapply_eligible_at' => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}