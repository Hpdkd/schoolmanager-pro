@extends('layouts.app')
@section('title', 'Élèves')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Élèves</h2>
        <p class="text-gray-500 text-sm">{{ $students->total() }} élève(s) actif(s)</p>
    </div>
    <a href="{{ route('students.create') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
        + Ajouter un élève
    </a>
</div>

{{-- Filtres --}}
<form method="GET" class="bg-white rounded-xl shadow p-4 mb-6 flex gap-4 items-end">
    <div class="flex-1">
        <label class="block text-xs text-gray-500 mb-1">Recherche</label>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Nom, prénom ou matricule..."
               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
    </div>
    <div class="w-48">
        <label class="block text-xs text-gray-500 mb-1">Classe</label>
        <select name="class_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            <option value="">Toutes les classes</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                    {{ $class->name }}
                </option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
        Filtrer
    </button>
    @if(request()->hasAny(['search','class_id']))
        <a href="{{ route('students.index') }}" class="text-sm text-gray-500 hover:text-gray-800 py-2">✕ Effacer</a>
    @endif
</form>

{{-- Table --}}
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr class="text-left text-gray-500 text-xs uppercase tracking-wide">
                <th class="px-5 py-3">Matricule</th>
                <th class="px-5 py-3">Nom complet</th>
                <th class="px-5 py-3">Classe</th>
                <th class="px-5 py-3">Genre</th>
                <th class="px-5 py-3">Téléphone</th>
                <th class="px-5 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($students as $student)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $student->registration_number }}</td>
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center">
                            {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                        </div>
                        <span class="font-medium text-gray-800">{{ $student->full_name }}</span>
                    </div>
                </td>
                <td class="px-5 py-3 text-gray-600">{{ $student->schoolClass->name }}</td>
                <td class="px-5 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $student->gender === 'M' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                        {{ $student->gender === 'M' ? '♂ Garçon' : '♀ Fille' }}
                    </span>
                </td>
                <td class="px-5 py-3 text-gray-500">{{ $student->phone ?? '—' }}</td>
                <td class="px-5 py-3">
                    <div class="flex gap-2">
                        <a href="{{ route('students.show', $student) }}"
                           class="text-indigo-600 hover:underline text-xs">Voir</a>
                        <a href="{{ route('students.edit', $student) }}"
                           class="text-amber-600 hover:underline text-xs">Modifier</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-12 text-gray-400">
                    Aucun élève trouvé.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($students->hasPages())
    <div class="px-5 py-4 border-t">
        {{ $students->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
