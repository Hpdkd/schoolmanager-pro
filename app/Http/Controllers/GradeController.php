<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Affiche le formulaire de saisie de notes
     */
    public function index(Request $request)
    {
        $classes  = SchoolClass::orderBy('name')->get();
        $subjects = collect();
        $students = collect();
        $grades   = collect();

        if ($request->filled('class_id')) {
            $subjects = Subject::where('class_id', $request->class_id)->get();
        }

        if ($request->filled('class_id') && $request->filled('subject_id') && $request->filled('semester')) {
            $students = Student::where('class_id', $request->class_id)
                ->where('is_active', true)
                ->orderBy('last_name')
                ->get();

            $grades = Grade::where('subject_id', $request->subject_id)
                ->where('semester', $request->semester)
                ->where('academic_year', '2024-2025')
                ->pluck('grade', 'student_id');
        }

        return view('grades.index', compact('classes', 'subjects', 'students', 'grades'));
    }

    /**
     * Sauvegarder les notes en lot
     */
    public function storeBulk(Request $request)
    {
        $request->validate([
            'subject_id'    => 'required|exists:subjects,id',
            'semester'      => 'required|in:S1,S2',
            'academic_year' => 'required|string',
            'grades'        => 'required|array',
            'grades.*'      => 'nullable|numeric|min:0|max:20',
        ]);

        $subject = Subject::findOrFail($request->subject_id);
        $saved   = 0;

        foreach ($request->grades as $studentId => $gradeValue) {
            if ($gradeValue === null || $gradeValue === '') {
                continue;
            }

            Grade::updateOrCreate(
                [
                    'student_id'    => $studentId,
                    'subject_id'    => $request->subject_id,
                    'semester'      => $request->semester,
                    'academic_year' => $request->academic_year,
                ],
                [
                    'grade'       => $gradeValue,
                    'recorded_by' => auth()->id(),
                ]
            );
            $saved++;
        }

        return redirect()->back()
            ->with('success', "{$saved} note(s) enregistrée(s) pour « {$subject->name} ».");
    }

    /**
     * Vue des résultats d'une classe (classement)
     */
    public function classResults(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();

        if (!$request->filled('class_id')) {
            return view('grades.results', compact('classes'));
        }

        $class    = SchoolClass::with(['subjects', 'students' => fn($q) => $q->where('is_active', true)])->findOrFail($request->class_id);
        $semester = $request->get('semester', 'S1');

        // Charger les notes sous forme de matrice [student_id][subject_id]
        $allGrades = Grade::whereIn('student_id', $class->students->pluck('id'))
            ->whereIn('subject_id', $class->subjects->pluck('id'))
            ->where('semester', $semester)
            ->where('academic_year', '2024-2025')
            ->get()
            ->groupBy('student_id')
            ->map(fn($g) => $g->keyBy('subject_id'));

        // Calculer les moyennes et classer
        $studentAverages = $class->students->map(function ($student) use ($semester) {
            return [
                'student' => $student,
                'average' => $student->getAverage($semester),
                'mention' => $student->getMention($student->getAverage($semester)),
            ];
        })->sortByDesc('average')->values();

        $classAverage = $studentAverages->avg('average');

        return view('grades.results', compact(
            'classes', 'class', 'semester', 'allGrades', 'studentAverages', 'classAverage'
        ));
    }

    /**
     * Export CSV des résultats d'une classe
     */
    public function export(Request $request)
    {
        $classes  = SchoolClass::orderBy('name')->get();
        $semester = $request->get('semester', 'S1');

        // Si aucune classe choisie → page de sélection
        if (!$request->filled('class_id')) {
            return view('grades.export', compact('classes', 'semester'));
        }

        $class = SchoolClass::with(['subjects', 'students' => fn($q) => $q->where('is_active', true)->with('grades.subject')])->findOrFail($request->class_id);

        // Construction du CSV
        $filename = "resultats_{$class->name}_{$semester}_" . date('Ymd') . ".csv";

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($class, $semester) {
            $handle = fopen('php://output', 'w');

            // BOM pour Excel (UTF-8)
            fwrite($handle, "\xEF\xBB\xBF");

            // En-têtes
            $headerRow = ['Rang', 'Matricule', 'Nom', 'Prénom', 'Classe'];
            foreach ($class->subjects as $subject) {
                $headerRow[] = $subject->name . ' (coeff.' . $subject->coefficient . ')';
            }
            $headerRow[] = 'Moyenne Générale';
            $headerRow[] = 'Mention';
            $headerRow[] = 'Décision';
            fputcsv($handle, $headerRow, ';');

            // Données élèves classés
            $studentAverages = $class->students->map(function ($student) use ($semester) {
                return [
                    'student' => $student,
                    'average' => $student->getAverage($semester),
                    'mention' => $student->getMention($student->getAverage($semester)),
                ];
            })->sortByDesc('average')->values();

            foreach ($studentAverages as $rank => $item) {
                $s = $item['student'];
                $row = [$rank + 1, $s->registration_number, $s->last_name, $s->first_name, $class->name];

                foreach ($class->subjects as $subject) {
                    $grade = $s->grades
                        ->where('subject_id', $subject->id)
                        ->where('semester', $semester)
                        ->first();
                    $row[] = $grade ? number_format($grade->grade, 2, ',', '') : '—';
                }

                $row[] = number_format($item['average'], 2, ',', '');
                $row[] = $item['mention'];
                $row[] = $item['average'] >= 10 ? 'Admis(e)' : 'Non admis(e)';

                fputcsv($handle, $row, ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
