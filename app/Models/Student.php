<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'class_id', 'registration_number', 'first_name', 'last_name',
        'birth_date', 'gender', 'phone', 'parent_phone', 'address',
        'photo', 'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active'  => 'boolean',
    ];

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAverage(string $semester, string $academicYear = '2024-2025'): float
    {
        $grades = $this->grades()
            ->with('subject')
            ->where('semester', $semester)
            ->where('academic_year', $academicYear)
            ->get();

        if ($grades->isEmpty()) return 0.0;

        $totalWeighted = $grades->sum(fn($g) => $g->grade * $g->subject->coefficient);
        $totalCoeff    = $grades->sum(fn($g) => $g->subject->coefficient);

        return $totalCoeff > 0 ? round($totalWeighted / $totalCoeff, 2) : 0.0;
    }

    public function getMention(float $average): string
    {
        return match (true) {
            $average >= 18 => 'Très Bien',
            $average >= 16 => 'Bien',
            $average >= 14 => 'Assez Bien',
            $average >= 12 => 'Passable',
            $average >= 10 => 'Moyen',
            default        => 'Insuffisant',
        };
    }
}
