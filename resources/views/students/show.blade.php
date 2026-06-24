@extends('layouts.app')
@section('title', $student->full_name)

@section('content')

<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <a href="{{ route('students.index') }}" class="text-slate-400 hover:text-slate-600 text-sm">← Retour</a>
        <span class="text-slate-300">/</span>
        <span class="text-sm font-medium text-slate-600">Fiche élève</span>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('students.report-card', [$student, 'S1']) }}"
           class="flex items-center gap-2 border border-rose-200 text-rose-600 bg-rose-50 px-4 py-2 rounded-xl text-xs font-semibold hover:bg-rose-100 transition">
            📄 Bulletin S1
        </a>
        <a href="{{ route('students.report-card', [$student, 'S2']) }}"
           class="flex items-center gap-2 border border-rose-200 text-rose-600 bg-rose-50 px-4 py-2 rounded-xl text-xs font-semibold hover:bg-rose-100 transition">
            📄 Bulletin S2
        </a>
        <a href="{{ route('students.edit', $student) }}"
           class="flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-semibold hover:bg-indigo-700 transition shadow-sm">
            ✏️ Modifier
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

    {{-- ── Profil ────────────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col items-center text-center">
        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-white text-2xl font-bold shadow-lg mb-4">
            {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
        </div>
        <h3 class="text-lg font-bold text-slate-800">{{ $student->full_name }}</h3>
        <p class="text-xs text-slate-400 mt-1 font-mono">{{ $student->registration_number }}</p>
        <span class="mt-2 px-3 py-1 rounded-full text-xs font-semibold
            {{ $student->gender === 'M' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
            {{ $student->gender === 'M' ? '♂ Masculin' : '♀ Féminin' }}
        </span>

        <div class="w-full mt-5 pt-5 border-t border-slate-100 space-y-2.5 text-xs text-left">
            <div class="flex justify-between">
                <span class="text-slate-400">Classe</span>
                <span class="font-semibold text-slate-700">{{ $student->schoolClass->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Naissance</span>
                <span class="font-medium text-slate-700">{{ \Carbon\Carbon::parse($student->birth_date)->format('d/m/Y') }}</span>
            </div>
            @if($student->phone)
            <div class="flex justify-between">
                <span class="text-slate-400">Tél élève</span>
                <span class="font-medium text-slate-700">{{ $student->phone }}</span>
            </div>
            @endif
            @if($student->parent_phone)
            <div class="flex justify-between">
                <span class="text-slate-400">Tél parent</span>
                <span class="font-medium text-slate-700">{{ $student->parent_phone }}</span>
            </div>
            @endif
        </div>

        {{-- Mini stats --}}
        @php
            $avg1 = $student->getAverage('S1');
            $avg2 = $student->getAverage('S2');
        @endphp
        <div class="w-full mt-5 grid grid-cols-2 gap-2">
            <div class="bg-slate-50 rounded-xl p-3 text-center">
                <div class="text-lg font-bold {{ $avg1 >= 10 ? 'text-emerald-600' : 'text-red-500' }}">{{ number_format($avg1, 1) }}</div>
                <div class="text-xs text-slate-400">Moy. S1</div>
            </div>
            <div class="bg-slate-50 rounded-xl p-3 text-center">
                <div class="text-lg font-bold {{ $avg2 >= 10 ? 'text-emerald-600' : ($avg2 == 0 ? 'text-slate-300' : 'text-red-500') }}">{{ $avg2 > 0 ? number_format($avg2, 1) : '—' }}</div>
                <div class="text-xs text-slate-400">Moy. S2</div>
            </div>
        </div>
    </div>

    {{-- ── Notes + Graphique ─────────────────────────────────────────────── --}}
    <div class="lg:col-span-3 space-y-6">

        {{-- Graphique radar / bar par matière --}}
        @php
            $gradesS1 = $student->grades->where('semester', 'S1');
        @endphp
        @if($gradesS1->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h4 class="font-semibold text-slate-700 mb-4">Performance par matière — S1</h4>
            <div class="relative h-52">
                <canvas id="studentChart"></canvas>
            </div>
        </div>
        @endif

        {{-- Tableaux de notes --}}
        @foreach(['S1', 'S2'] as $semester)
        @php
            $semGrades = $student->grades->where('semester', $semester);
            $average   = $student->getAverage($semester);
            $mention   = $student->getMention($average);
            $mentionColor = match($mention) {
                'Très Bien'  => 'bg-emerald-100 text-emerald-800',
                'Bien'       => 'bg-teal-100 text-teal-800',
                'Assez Bien' => 'bg-cyan-100 text-cyan-800',
                'Passable'   => 'bg-yellow-100 text-yellow-800',
                'Moyen'      => 'bg-orange-100 text-orange-800',
                default      => 'bg-red-100 text-red-800',
            };
        @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 bg-indigo-100 text-indigo-700 rounded-lg flex items-center justify-center text-xs font-bold">{{ $semester }}</span>
                    <span class="font-semibold text-slate-700">Notes du {{ $semester }}</span>
                </div>
                @if($average > 0)
                <div class="flex items-center gap-3">
                    <span class="text-xl font-bold {{ $average >= 10 ? 'text-emerald-600' : 'text-red-500' }}">
                        {{ number_format($average, 2) }}/20
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $mentionColor }}">{{ $mention }}</span>
                </div>
                @endif
            </div>

            @if($semGrades->count() > 0)
            <div class="p-5">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-slate-400 uppercase border-b">
                            <th class="pb-3 text-left font-semibold">Matière</th>
                            <th class="pb-3 text-center font-semibold">Coeff.</th>
                            <th class="pb-3 text-center font-semibold">Note</th>
                            <th class="pb-3 font-semibold">Barème</th>
                            <th class="pb-3 text-center font-semibold">Mention</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($semGrades->sortBy('subject.name') as $grade)
                        <tr class="border-b border-slate-50 last:border-0">
                            <td class="py-3 font-medium text-slate-700">{{ $grade->subject->name }}</td>
                            <td class="py-3 text-center text-slate-400 text-xs">{{ $grade->subject->coefficient }}</td>
                            <td class="py-3 text-center font-bold text-base
                                {{ $grade->grade >= 10 ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ number_format($grade->grade, 2) }}
                            </td>
                            <td class="py-3 pr-4">
                                <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden w-32">
                                    <div class="h-full rounded-full {{ $grade->grade >= 14 ? 'bg-emerald-400' : ($grade->grade >= 10 ? 'bg-amber-400' : 'bg-red-400') }}"
                                         style="width: {{ min($grade->grade * 5, 100) }}%"></div>
                                </div>
                            </td>
                            <td class="py-3 text-center">
                                <span class="px-2 py-0.5 rounded text-xs font-bold
                                    {{ $grade->grade >= 16 ? 'bg-emerald-100 text-emerald-700' :
                                       ($grade->grade >= 14 ? 'bg-teal-100 text-teal-700' :
                                       ($grade->grade >= 10 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')) }}">
                                    {{ $grade->letter_grade }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-8 text-center text-slate-400 text-sm">Aucune note saisie pour ce semestre.</div>
            @endif
        </div>
        @endforeach
    </div>
</div>

{{-- ── Chart.js ─────────────────────────────────────────────────────────── --}}
@if($student->grades->where('semester','S1')->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const labels  = @json($student->grades->where('semester','S1')->map(fn($g) => $g->subject->name)->values());
const grades  = @json($student->grades->where('semester','S1')->map(fn($g) => $g->grade)->values());
const colors  = grades.map(v => v >= 14 ? '#6ee7b7' : v >= 10 ? '#93c5fd' : '#fca5a5');

new Chart(document.getElementById('studentChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            label: 'Note /20',
            data: grades,
            backgroundColor: colors,
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { min: 0, max: 20,
                 grid: { color: '#f1f5f9' },
                 ticks: { callback: v => v + '/20' } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endif

@endsection
