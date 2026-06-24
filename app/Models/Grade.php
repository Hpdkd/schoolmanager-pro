<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'student_id', 'subject_id', 'grade',
        'semester', 'academic_year', 'comment', 'recorded_by',
    ];

    protected $casts = ['grade' => 'float'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function getLetterGradeAttribute(): string
    {
        return match (true) {
            $this->grade >= 18 => 'A+',
            $this->grade >= 16 => 'A',
            $this->grade >= 14 => 'B',
            $this->grade >= 12 => 'C',
            $this->grade >= 10 => 'D',
            default            => 'F',
        };
    }
}
