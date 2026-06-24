@extends('layouts.app')
@section('title', 'Export Excel / CSV')

@section('content')
<div class="mb-6">
    <h2 class="text-xl font-bold text-slate-800">Export des résultats</h2>
    <p class="text-slate-500 text-sm mt-1">Téléchargez les résultats d'une classe au format CSV (compatible Excel).</p>
</div>

<div class="max-w-lg bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
    <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center text-3xl mb-5">📤</div>
    <h3 class="text-lg font-semibold text-slate-800 mb-1">Export CSV — Compatible Excel</h3>
    <p class="text-sm text-slate-500 mb-6">Le fichier contiendra le classement complet avec toutes les notes, les moyennes et les mentions.</p>

    <form method="GET" action="{{ route('grades.export') }}">
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Classe</label>
                <select name="class_id" required
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 bg-slate-50">
                    <option value="">Sélectionner une classe</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Semestre</label>
                <select name="semester"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 bg-slate-50">
                    <option value="S1">Semestre 1</option>
                    <option value="S2">Semestre 2</option>
                </select>
            </div>

            <button type="submit"
                    class="w-full bg-emerald-600 text-white py-3 rounded-xl font-semibold hover:bg-emerald-700 transition shadow-sm flex items-center justify-center gap-2">
                ⬇️ Télécharger le fichier CSV
            </button>
        </div>
    </form>

    <p class="text-xs text-slate-400 mt-4 text-center">Format : CSV encodé UTF-8 · Séparateur point-virgule · Compatible Excel / LibreOffice</p>
</div>
@endsection
