<x-scolarite-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">Tableau de bord</h1>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800">Bienvenue, {{ auth()->user()->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">
                Gestion de la scolarité · Année active : <strong>{{ $annee->libelle ?? '—' }}</strong>
            </p>
        </div>

        @php
            $stats = [
                ['Élèves', $nbEleves, 'border-amber-400'],
                ['Inscrits (année active)', $nbInscrits, 'border-emerald-400'],
                ['Classes', $nbClasses, 'border-sky-400'],
                ['Absences aujourd\'hui', $absencesJour, 'border-rose-400'],
            ];
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($stats as [$label, $value, $color])
                <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 {{ $color }}">
                    <div class="text-xs uppercase tracking-wider text-gray-500">{{ $label }}</div>
                    <div class="text-2xl font-bold text-gray-800 mt-2">{{ $value }}</div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-800">Derniers élèves inscrits</h3>
                    <a href="{{ route('scolarite.eleves.index') }}" class="text-xs text-amber-700 hover:underline">Tout voir</a>
                </div>
                <ul class="divide-y divide-gray-100 text-sm">
                    @forelse ($derniersInscrits as $e)
                        <li class="py-2 flex justify-between">
                            <a href="{{ route('scolarite.eleves.show', $e) }}" class="hover:underline">{{ $e->nom }} {{ $e->prenom }}</a>
                            <span class="text-gray-400 text-xs">Mat. {{ str_pad($e->matricule, 5, '0', STR_PAD_LEFT) }}</span>
                        </li>
                    @empty
                        <li class="py-2 text-gray-500">Aucun élève enregistré.</li>
                    @endforelse
                </ul>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Accès rapides</h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <a href="{{ route('scolarite.eleves.create') }}" class="px-4 py-3 rounded-md bg-amber-50 text-amber-800 hover:bg-amber-100 font-medium">+ Inscrire un élève</a>
                    <a href="{{ route('scolarite.presences.index') }}" class="px-4 py-3 rounded-md bg-sky-50 text-sky-800 hover:bg-sky-100 font-medium">Pointer les présences</a>
                    <a href="{{ route('scolarite.classes.index') }}" class="px-4 py-3 rounded-md bg-emerald-50 text-emerald-800 hover:bg-emerald-100 font-medium">Gérer les classes</a>
                    <a href="{{ route('scolarite.bulletins.index') }}" class="px-4 py-3 rounded-md bg-violet-50 text-violet-800 hover:bg-violet-100 font-medium">Éditer un bulletin</a>
                </div>
            </div>
        </div>
    </div>
</x-scolarite-layout>
