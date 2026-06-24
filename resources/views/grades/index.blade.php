@extends('layouts.app')
@section('title', 'Saisie des notes')

@section('content')

<div class="mb-6">
    <h2 class="text-xl font-bold text-slate-800">Saisie des notes</h2>
    <p class="text-slate-500 text-sm mt-1">Sélectionnez une classe, une matière et un semestre.</p>
</div>

{{-- ── Filtres ──────────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">
    <form method="GET" action="{{ route('grades.index') }}" id="filterForm">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Classe</label>
                <select name="class_id" id="classSelect"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-slate-50">
                    <option value="">Choisir une classe</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Matière</label>
                <select name="subject_id" id="subjectSelect"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-slate-50">
                    <option value="">Choisir une matière</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" data-coeff="{{ $subject->coefficient }}"
                                {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }} (coeff. {{ $subject->coefficient }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Semestre</label>
                <select name="semester"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-slate-50">
                    <option value="">Choisir</option>
                    <option value="S1" {{ request('semester') === 'S1' ? 'selected' : '' }}>Semestre 1</option>
                    <option value="S2" {{ request('semester') === 'S2' ? 'selected' : '' }}>Semestre 2</option>
                </select>
            </div>

            <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2.5 rounded-xl font-semibold text-sm hover:bg-indigo-700 transition shadow-sm">
                Charger les élèves
            </button>
        </div>
    </form>
</div>

{{-- ── Tableau de saisie ────────────────────────────────────────────────── --}}
@if($students->count() > 0)
@php $subject = \App\Models\Subject::find(request('subject_id')); @endphp

{{-- Barre de progression en temps réel --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-4 flex items-center gap-6">
    <div class="flex-1">
        <div class="flex justify-between text-xs text-slate-500 mb-1">
            <span>Progression de saisie</span>
            <span id="enteredCount">0</span>/{{ $students->count() }} élèves
        </div>
        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
            <div id="progressBar" class="h-full bg-indigo-500 rounded-full transition-all" style="width:0%"></div>
        </div>
    </div>
    <div class="text-center border-l pl-6">
        <div id="liveAvgDisplay" class="text-2xl font-bold text-slate-300">—</div>
        <div class="text-xs text-slate-400">Moy. classe live</div>
    </div>
    <div class="text-center border-l pl-6">
        <div id="livePassCount" class="text-2xl font-bold text-emerald-600">0</div>
        <div class="text-xs text-slate-400">≥ 10 (admis)</div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="font-semibold text-slate-800">{{ request('semester') }} — {{ $subject->name ?? '' }}</h3>
            <p class="text-xs text-slate-400 mt-0.5">{{ $students->count() }} élèves · Coefficient {{ $subject->coefficient ?? '' }}</p>
        </div>
        <div class="text-sm text-slate-500">
            Notes existantes affichées en gris
        </div>
    </div>

    <form method="POST" action="{{ route('grades.store-bulk') }}" id="gradesForm">
        @csrf
        <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
        <input type="hidden" name="semester" value="{{ request('semester') }}">
        <input type="hidden" name="academic_year" value="2024-2025">

        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs text-slate-400 uppercase tracking-wide border-b">
                    <th class="px-5 py-3 text-left font-semibold">#</th>
                    <th class="px-5 py-3 text-left font-semibold">Élève</th>
                    <th class="px-5 py-3 text-center font-semibold w-36">Note /20</th>
                    <th class="px-5 py-3 text-center font-semibold">Note actuelle</th>
                    <th class="px-5 py-3 text-center font-semibold">Statut</th>
                </tr>
            </thead>
            <tbody id="gradesTable">
                @foreach($students as $i => $student)
                @php $existing = $grades->get($student->id); @endphp
                <tr class="border-b border-slate-50 hover:bg-indigo-50/30 transition" data-row>
                    <td class="px-5 py-3 text-slate-400 text-xs">{{ $i + 1 }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center flex-shrink-0">
                                {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-medium text-slate-700">{{ $student->full_name }}</div>
                                <div class="text-xs text-slate-400">{{ $student->registration_number }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <input type="number"
                               name="grades[{{ $student->id }}]"
                               value="{{ old("grades.{$student->id}", $existing) }}"
                               min="0" max="20" step="0.25"
                               placeholder="—"
                               class="grade-input w-24 text-center border-2 border-slate-200 rounded-xl px-2 py-1.5 font-mono font-bold text-base focus:outline-none focus:border-indigo-400 transition"
                               oninput="updateLive(this)">
                    </td>
                    <td class="px-5 py-3 text-center">
                        @if($existing !== null)
                            <span class="font-semibold text-slate-400">{{ number_format($existing, 2) }}</span>
                        @else
                            <span class="text-slate-200">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="status-badge text-xs px-2 py-0.5 rounded-full font-medium bg-slate-100 text-slate-400">En attente</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-5 bg-slate-50 border-t flex items-center justify-between">
            <p class="text-xs text-slate-400">Les notes vides ne seront pas modifiées.</p>
            <button type="submit"
                    class="bg-emerald-600 text-white px-8 py-2.5 rounded-xl font-semibold text-sm hover:bg-emerald-700 transition shadow-sm flex items-center gap-2">
                💾 Enregistrer
            </button>
        </div>
    </form>
</div>

@elseif(request()->filled('class_id') && request()->filled('subject_id') && request()->filled('semester'))
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-16 text-center">
    <p class="text-4xl mb-3">🎓</p>
    <p class="text-slate-500">Aucun élève actif dans cette classe.</p>
</div>
@else
<div class="bg-gradient-to-br from-indigo-50 to-slate-50 border border-indigo-100 rounded-2xl p-16 text-center">
    <p class="text-5xl mb-4">📝</p>
    <p class="text-slate-700 font-semibold text-lg">Sélectionnez les filtres ci-dessus</p>
    <p class="text-slate-400 text-sm mt-1">Choisissez une classe, une matière et un semestre pour commencer la saisie.</p>
</div>
@endif

{{-- ── Script calcul temps réel ─────────────────────────────────────────── --}}
<script>
function updateLive(input) {
    const val = parseFloat(input.value);
    const row = input.closest('tr');
    const badge = row.querySelector('.status-badge');

    // Couleur de l'input
    if (isNaN(val) || input.value === '') {
        input.classList.remove('border-green-400','border-red-400','text-green-700','text-red-600');
        input.classList.add('border-slate-200');
        badge.textContent = 'En attente';
        badge.className = 'status-badge text-xs px-2 py-0.5 rounded-full font-medium bg-slate-100 text-slate-400';
    } else if (val >= 10) {
        input.classList.remove('border-slate-200','border-red-400','text-red-600');
        input.classList.add('border-green-400','text-green-700');
        badge.textContent = '✓ Admis';
        badge.className = 'status-badge text-xs px-2 py-0.5 rounded-full font-medium bg-emerald-100 text-emerald-700';
    } else {
        input.classList.remove('border-slate-200','border-green-400','text-green-700');
        input.classList.add('border-red-400','text-red-600');
        badge.textContent = '✗ Insuffisant';
        badge.className = 'status-badge text-xs px-2 py-0.5 rounded-full font-medium bg-red-100 text-red-600';
    }

    // Recalculer les stats globales
    recalcLive();
}

function recalcLive() {
    const inputs = document.querySelectorAll('.grade-input');
    let total = 0, count = 0, passCount = 0;

    inputs.forEach(inp => {
        const v = parseFloat(inp.value);
        if (!isNaN(v) && inp.value !== '') {
            total += v;
            count++;
            if (v >= 10) passCount++;
        }
    });

    const totalInputs = inputs.length;
    document.getElementById('enteredCount').textContent = count;
    document.getElementById('progressBar').style.width = (count / totalInputs * 100) + '%';
    document.getElementById('livePassCount').textContent = passCount;

    const avgEl = document.getElementById('liveAvgDisplay');
    if (count > 0) {
        const avg = total / count;
        avgEl.textContent = avg.toFixed(2);
        avgEl.className = 'text-2xl font-bold ' + (avg >= 10 ? 'text-emerald-600' : 'text-red-500');
    } else {
        avgEl.textContent = '—';
        avgEl.className = 'text-2xl font-bold text-slate-300';
    }
}

// Initialiser avec les valeurs existantes
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.grade-input').forEach(inp => {
        if (inp.value) updateLive(inp);
    });
});

// Auto-submit class change
document.getElementById('classSelect')?.addEventListener('change', () => {
    document.getElementById('filterForm').submit();
});
</script>

@endsection
