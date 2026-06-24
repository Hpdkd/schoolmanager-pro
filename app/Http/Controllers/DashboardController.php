<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Statistiques générales ─────────────────────────────────────────────
        $stats = [
            'total_students' => Student::where('is_active', true)->count(),
            'total_classes'  => SchoolClass::count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_subjects' => Subject::count(),
            'total_grades'   => Grade::count(),
        ];

        // ── Top 5 élèves (S1) ─────────────────────────────────────────────────
        $topStudents = Student::with(['grades.subject', 'schoolClass'])
            ->where('is_active', true)
            ->get()
            ->map(fn($s) => ['student' => $s, 'average' => $s->getAverage('S1')])
            ->sortByDesc('average')
            ->take(5)
            ->values();

        // ── Distribution des notes (pour graphique doughnut) ──────────────────
        $gradeDistribution = [
            'Excellent (≥16)'    => Grade::where('grade', '>=', 16)->count(),
            'Bien (14-16)'       => Grade::whereBetween('grade', [14, 15.99])->count(),
            'Assez bien (12-14)' => Grade::whereBetween('grade', [12, 13.99])->count(),
            'Moyen (10-12)'      => Grade::whereBetween('grade', [10, 11.99])->count(),
            'Insuffisant (<10)'  => Grade::where('grade', '<', 10)->count(),
        ];

        // ── Moyenne par classe (pour graphique barres) ────────────────────────
        $classes = SchoolClass::with([
            'students' => fn($q) => $q->where('is_active', true)->with('grades.subject'),
        ])->get();

        $classAverages = $classes->map(function ($class) {
            $averages = $class->students->map(fn($s) => $s->getAverage('S1'))->filter(fn($a) => $a > 0);
            return [
                'name'    => $class->name,
                'average' => $averages->count() > 0 ? round($averages->avg(), 2) : 0,
                'count'   => $class->students->count(),
            ];
        });

        // ── Moyenne par matière ───────────────────────────────────────────────
        $subjectAverages = Subject::with('grades')->get()->map(function ($subject) {
            $avg = $subject->grades->avg('grade');
            return [
                'name'    => $subject->name,
                'average' => $avg ? round($avg, 2) : 0,
                'coeff'   => $subject->coefficient,
            ];
        })->sortByDesc('average')->values();

        // ── Admis vs Refusés ──────────────────────────────────────────────────
        $allStudents = Student::with('grades.subject')->where('is_active', true)->get();
        $admitted  = $allStudents->filter(fn($s) => $s->getAverage('S1') >= 10)->count();
        $rejected  = $allStudents->count() - $admitted;

        // ── Taux de remplissage des notes ─────────────────────────────────────
        $totalExpected = Student::where('is_active', true)->count()
                       * Subject::count()
                       * 2; // S1 + S2
        $totalEntered  = Grade::count();
        $fillRate      = $totalExpected > 0 ? round($totalEntered / $totalExpected * 100) : 0;

        return view('dashboard', compact(
            'stats', 'topStudents', 'gradeDistribution', 'classAverages',
            'subjectAverages', 'admitted', 'rejected', 'fillRate'
        ));
    }
}
