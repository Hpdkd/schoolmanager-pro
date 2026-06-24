@extends('layouts.app')
@section('title', 'Résultats de classe')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Résultats de classe</h2>
    <p class="text-gray-500 text-sm mt-1">Classement des élèves par moyenne générale.</p>
</div>

{{-- Filtres --}}
<div class="bg-white rounded-xl shadow p-5 mb-6">
    <form method="GET" action="{{ route('grades.results') }}">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Classe</label>
                <select name="class_id"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">Toutes les classes</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Semestre</label>
                <select name="semester"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="S1" {{ request('semester', 'S1') === 'S1' ? 'selected' : '' }}>Semestre 1</option>
                    <option value="S2" {{ request('semester') === 'S2' ? 'selected' : '' }}>Semestre 2</option>
                </select>
            </div>
            <div>
                <button type="submit"
                        class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-700">
                    Afficher
                </button>
            </div>
        </div>
    </form>
</div>

@isset($class)

{{-- Stats de classe --}}
@php
    $admitted  = $studentAverages->filter(fn($s) => $s['average'] >= 10)->count();
    $total     = $studentAverages->count();
    $topScore  = $studentAverages->first()['average'] ?? 0;
    $lowestScore = $studentAverages->last()['average'] ?? 0;
@endphp

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow p-4 border-l-4 border-indigo-500">
        <div class="text-2xl font-bold text-indigo-700">{{ $total }}</div>
        <div class="text-xs text-gray-500 mt-1">Élèves évalués</div>
    </div>
    <div class="bg-white rounded-xl shadow p-4 border-l-4 border-green-500">
        <div class="text-2xl font-bold text-green-700">{{ number_format($classAverage, 2) }}</div>
        <div class="text-xs text-gray-500 mt-1">Moyenne de classe</div>
    </div>
    <div class="bg-white rounded-xl shadow p-4 border-l-4 border-emerald-500">
        <div class="text-2xl font-bold text-emerald-700">{{ $admitted }}/{{ $total }}</div>
        <div class="text-xs text-gray-500 mt-1">Admis (≥ 10)</div>
    </div>
    <div class="bg-white rounded-xl shadow p-4 border-l-4 border-amber-500">
        <div class="text-2xl font-bold text-amber-700">{{ number_format($topScore, 2) }}</div>
        <div class="text-xs text-gray-500 mt-1">Meilleure moyenne</div>
    </div>
</div>

{{-- Tableau de classement --}}
<div class="bg-white rounded-xl shadow">
    <div class="p-4 border-b flex items-center justify-between">
        <h3 class="font-semibold text-gray-800">
            Classement — {{ $class->name }} · {{ $semester }}
        </h3>
        <span class="text-sm text-gray-400">{{ $studentAverages->count() }} élève(s)</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left text-xs text-gray-500 uppercase tracking-wide">
                    <th class="px-5 py-3 font-medium">Rang</th>
                    <th class="px-5 py-3 font-medium">Élève</th>
                    @foreach($class->subjects as $subject)
                    <th class="px-3 py-3 font-medium text-center" title="{{ $subject->name }}">
                        {{ Str::limit($subject->code ?? $subject->name, 6) }}
                        <br><span class="text-gray-400 font-normal">({{ $subject->coefficient }})</span>
                    </th>
                    @endforeach
                    <th class="px-5 py-3 font-medium text-center">Moyenne</th>
                    <th class="px-5 py-3 font-medium text-center">Mention</th>
                    <th class="px-5 py-3 font-medium text-center">Décision</th>
                </tr>
            </thead>
            <tbody>
                @foreach($studentAverages as $i => $item)
                @php
                    $student = $item['student'];
                    $avg     = $item['average'];
                    $mention = $item['mention'];
                    $rank    = $i + 1;
                    $mentionColors = [
                        'Très Bien'  => 'bg-green-100 text-green-800',
                        'Bien'       => 'bg-emerald-100 text-emerald-800',
                        'Assez Bien' => 'bg-teal-100 text-teal-800',
                        'Passable'   => 'bg-yellow-100 text-yellow-800',
                        'Moyen'      => 'bg-orange-100 text-orange-800',
                        'Insuffisant'=> 'bg-red-100 text-red-800',
                    ];
                @endphp
                <tr class="border-t hover:bg-gray-50 {{ $avg < 10 ? 'bg-red-50/30' : '' }}">

                    {{-- Rang --}}
                    <td class="px-5 py-3">
                        @if($rank === 1)
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-yellow-400 text-white font-bold text-xs">🥇</span>
                        @elseif($rank === 2)
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-300 text-white font-bold text-xs">🥈</span>
                        @elseif($rank === 3)
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-600 text-white font-bold text-xs">🥉</span>
                        @else
                            <span class="text-gray-400 font-medium pl-1">{{ $rank }}</span>
                        @endif
                    </td>

                    {{-- Élève --}}
                    <td class="px-5 py-3">
                        <a href="{{ route('students.show', $student) }}" class="flex items-center gap-2 hover:text-indigo-600">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold">
                                {{ strtoupper(substr($student->first_name,0,1) . substr($student->last_name,0,1)) }}
                            </div>
                            <div>
                                <div class="font-medium">{{ $student->full_name }}</div>
                                <div class="text-xs text-gray-400">{{ $student->registration_number }}</div>
                            </div>
                        </a>
                    </td>

                    {{-- Notes par matière --}}
                    @foreach($class->subjects as $subject)
                    @php
                        $gradeObj = isset($allGrades[$student->id][$subject->id])
                            ? $allGrades[$student->id][$subject->id]->grade
                            : null;
                    @endphp
                    <td class="px-3 py-3 text-center font-mono text-xs
                        {{ $gradeObj !== null ? ($gradeObj >= 10 ? 'text-green-700 font-semibold' : 'text-red-600') : 'text-gray-300' }}">
                        {{ $gradeObj !== null ? number_format($gradeObj, 1) : '—' }}
                    </td>
                    @endforeach

                    {{-- Moyenne --}}
                    <td class="px-5 py-3 text-center">
                        <span class="text-lg font-bold {{ $avg >= 10 ? 'text-green-700' : 'text-red-600' }}">
                            {{ number_format($avg, 2) }}
                        </span>
                    </td>

                    {{-- Mention --}}
                    <td class="px-5 py-3 text-center">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $mentionColors[$mention] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $mention }}
                        </span>
                    </td>

                    {{-- Décision --}}
                    <td class="px-5 py-3 text-center">
                        @if($avg >= 10)
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">✓ Admis</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">✗ Refusé</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>

            {{-- Ligne de moyenne de classe --}}
            <tfoot>
                <tr class="bg-indigo-50 border-t-2 border-indigo-200">
                    <td colspan="{{ 2 + $class->subjects->count() }}" class="px-5 py-3 font-semibold text-indigo-700 text-right">
                        Moyenne de classe
                    </td>
                    <td class="px-5 py-3 text-center font-bold text-indigo-700 text-lg">
                        {{ number_format($classAverage, 2) }}
                    </td>
                    <td colspan="2" class="px-5 py-3 text-center text-indigo-600 text-xs">
                        {{ $admitted }}/{{ $total }} admis ({{ $total > 0 ? round($admitted / $total * 100) : 0 }}%)
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@else
<div class="bg-indigo-50 border border-indigo-100 rounded-xl p-12 text-center">
    <p class="text-4xl mb-3">📊</p>
    <p class="text-gray-600 font-medium">Sélectionnez une classe et un semestre pour afficher le classement.</p>
</div>
@endisset
@endsection
