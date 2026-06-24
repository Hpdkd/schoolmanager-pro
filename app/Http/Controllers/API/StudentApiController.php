<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StudentApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $students = Student::with('schoolClass')
            ->where('is_active', true)
            ->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))
            ->when($request->search, fn($q) => $q->where(fn($sq) => $sq
                ->where('first_name', 'like', "%{$request->search}%")
                ->orWhere('last_name', 'like', "%{$request->search}%")
            ))
            ->orderBy('last_name')->paginate(20);

        return response()->json(['success' => true, 'data' => $students]);
    }

    public function show(Student $student): JsonResponse
    {
        $student->load(['schoolClass', 'grades.subject']);
        return response()->json([
            'success'    => true,
            'data'       => $student,
            'average_s1' => $student->getAverage('S1'),
            'average_s2' => $student->getAverage('S2'),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'class_id'   => 'required|exists:classes,id',
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'birth_date' => 'required|date',
            'gender'     => 'required|in:M,F',
        ]);

        $data['registration_number'] = 'STU-' . date('Y') . '-' . str_pad(Student::count() + 1, 4, '0', STR_PAD_LEFT);
        $student = Student::create($data);

        return response()->json(['success' => true, 'data' => $student], 201);
    }

    public function grades(Student $student, Request $request): JsonResponse
    {
        $semester = $request->get('semester', 'S1');
        $grades   = $student->grades()->with('subject')
            ->where('semester', $semester)->get()
            ->map(fn($g) => [
                'subject'     => $g->subject->name,
                'coefficient' => $g->subject->coefficient,
                'grade'       => $g->grade,
            ]);

        return response()->json([
            'success'  => true,
            'semester' => $semester,
            'average'  => $student->getAverage($semester),
            'mention'  => $student->getMention($student->getAverage($semester)),
            'data'     => $grades,
        ]);
    }

    public function storeGrade(Request $request): JsonResponse
    {
        $data = $request->validate([
            'student_id'    => 'required|exists:students,id',
            'subject_id'    => 'required|exists:subjects,id',
            'grade'         => 'required|numeric|min:0|max:20',
            'semester'      => 'required|in:S1,S2',
            'academic_year' => 'required|string',
        ]);

        $grade = Grade::updateOrCreate(
            ['student_id' => $data['student_id'], 'subject_id' => $data['subject_id'],
             'semester' => $data['semester'], 'academic_year' => $data['academic_year']],
            ['grade' => $data['grade'], 'recorded_by' => auth()->id()]
        );

        return response()->json(['success' => true, 'data' => $grade]);
    }
}
