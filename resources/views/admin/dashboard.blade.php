<x-admin-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">Tableau de bord</h1>
    </x-slot>

    @php
        $stats = [
            ['Élèves', \App\Models\Eleve::count(), 'border-emerald-400'],
            ['Enseignants', \App\Models\Enseignant::count(), 'border-sky-400'],
            ['Classes', \App\Models\Classe::count(), 'border-violet-400'],
            ['Cycles', \App\Models\Cycle::count(), 'border-amber-400'],
            ['Cours', \App\Models\Cours::count(), 'border-rose-400'],
            ['Paiements', \App\Models\Paiement::count(), 'border-teal-400'],
            ['Année active', \App\Models\AnneeAcademique::where('actif', true)->value('libelle') ?? '—', 'border-indigo-400'],
            ['Parents', \App\Models\ParentEleve::count(), 'border-pink-400'],
        ];
    @endphp

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800">Bienvenue, {{ auth()->user()->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">Vue d'ensemble de l'établissement.</p>
        </div>

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
                <h3 class="font-semibold text-gray-800 mb-4">Derniers paiements</h3>
                <ul class="divide-y divide-gray-100 text-sm">
                    @forelse (\App\Models\Paiement::with('eleve','mode')->latest('datePaie')->take(5)->get() as $p)
                        <li class="py-2 flex justify-between">
                            <span>{{ optional($p->eleve)->nom }} {{ optional($p->eleve)->prenom }}</span>
                            <span class="font-medium text-gray-700">{{ number_format($p->montant, 0, ',', ' ') }} XAF</span>
                        </li>
                    @empty
                        <li class="py-2 text-gray-500">Aucun paiement enregistré.</li>
                    @endforelse
                </ul>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Classes</h3>
                <ul class="divide-y divide-gray-100 text-sm">
                    @forelse (\App\Models\Classe::with('cycle')->take(5)->get() as $c)
                        <li class="py-2 flex justify-between">
                            <span>{{ $c->libelle }}</span>
                            <span class="text-gray-500 text-xs">{{ optional($c->cycle)->libelle }}</span>
                        </li>
                    @empty
                        <li class="py-2 text-gray-500">Aucune classe.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-admin-layout>
