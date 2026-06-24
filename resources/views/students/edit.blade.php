@extends('layouts.app')
@section('title', 'Modifier ' . $student->full_name)

@section('content')
<div class="mb-6 flex items-center gap-3">
    <a href="{{ route('students.show', $student) }}" class="text-gray-400 hover:text-gray-600">← Retour</a>
    <h2 class="text-2xl font-bold text-gray-800">Modifier l'élève</h2>
</div>

<div class="bg-white rounded-xl shadow p-8 max-w-2xl">
    <form method="POST" action="{{ route('students.update', $student) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                <input type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('first_name') border-red-400 @enderror">
                @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                <input type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('last_name') border-red-400 @enderror">
                @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Classe *</label>
                <select name="class_id" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}"
                            {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance *</label>
                <input type="date" name="birth_date"
                       value="{{ old('birth_date', \Carbon\Carbon::parse($student->birth_date)->format('Y-m-d')) }}"
                       required class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Genre *</label>
                <select name="gender" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="M" {{ old('gender', $student->gender) === 'M' ? 'selected' : '' }}>♂ Masculin</option>
                    <option value="F" {{ old('gender', $student->gender) === 'F' ? 'selected' : '' }}>♀ Féminin</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                <input type="text" name="phone" value="{{ old('phone', $student->phone) }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone parent</label>
                <input type="text" name="parent_phone" value="{{ old('parent_phone', $student->parent_phone) }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                <input type="text" name="address" value="{{ old('address', $student->address) }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Photo (optionnel)</label>
                <input type="file" name="photo" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            <div class="md:col-span-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $student->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-indigo-600">
                    <span class="text-sm text-gray-700">Élève actif</span>
                </label>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <button type="submit"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-indigo-700">
                Enregistrer les modifications
            </button>
            <a href="{{ route('students.show', $student) }}"
               class="px-6 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
