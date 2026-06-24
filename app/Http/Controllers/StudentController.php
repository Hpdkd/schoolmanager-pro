<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('schoolClass')->where('is_active', true);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q
                ->where('first_name', 'like', "%$s%")
                ->orWhere('last_name', 'like', "%$s%")
                ->orWhere('registration_number', 'like', "%$s%")
            );
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        $students = $query->orderBy('last_name')->paginate(15);
        $classes  = SchoolClass::orderBy('name')->get();

        return view('students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes = SchoolClass::orderBy('name')->get();
        return view('students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'class_id'     => 'required|exists:classes,id',
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'birth_date'   => 'required|date|before:today',
            'gender'       => 'required|in:M,F',
            'phone'        => 'nullable|string|max:20',
            'parent_phone' => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:255',
            'photo'        => 'nullable|image|max:2048',
        ]);

        $year = date('Y');
        $data['registration_number'] = 'STU-' . $year . '-' . str_pad(Student::count() + 1, 4, '0', STR_PAD_LEFT);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        Student::create($data);

        return redirect()->route('students.index')->with('success', 'Élève ajouté avec succès.');
    }

    public function show(Student $student)
    {
        $student->load(['schoolClass', 'grades.subject']);
        $averageS1 = $student->getAverage('S1');
        $averageS2 = $student->getAverage('S2');

        return view('students.show', compact('student', 'averageS1', 'averageS2'));
    }

    public function edit(Student $student)
    {
        $classes = SchoolClass::orderBy('name')->get();
        return view('students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'class_id'     => 'required|exists:classes,id',
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'birth_date'   => 'required|date|before:today',
            'gender'       => 'required|in:M,F',
            'phone'        => 'nullable|string|max:20',
            'parent_phone' => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:255',
            'photo'        => 'nullable|image|max:2048',
        ]);

        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('photo')) {
            if ($student->photo) Storage::disk('public')->delete($student->photo);
            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        $student->update($data);

        return redirect()->route('students.show', $student)->with('success', 'Informations mises à jour.');
    }

    public function destroy(Student $student)
    {
        $student->update(['is_active' => false]);
        return redirect()->route('students.index')->with('success', 'Élève désactivé.');
    }

    public function reportCard(Student $student, string $semester)
    {
        $student->load(['schoolClass', 'grades.subject']);
        $grades       = $student->grades->where('semester', $semester)->values();
        $average      = $student->getAverage($semester);
        $mention      = $student->getMention($average);
        $academicYear = '2024-2025';

        $pdf = Pdf::loadView('students.report-card', compact(
            'student', 'grades', 'average', 'mention', 'semester', 'academicYear'
        ))->setPaper('A4', 'portrait');

        return $pdf->download("bulletin_{$student->registration_number}_{$semester}.pdf");
    }
}
