<x-finance-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">Tableau de bord</h1>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800">Bienvenue, {{ auth()->user()->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">
                Gestion financière · Année active : <strong>{{ $annee->libelle ?? '—' }}</strong>
            </p>
        </div>

        @php
            $stats = [
                ['Encaissé (année active)', number_format($totalEncaisse, 0, ',', ' ') . ' XAF', 'border-emerald-400'],
                ['Reste à payer', number_format($resteAPayer, 0, ',', ' ') . ' XAF', 'border-rose-400'],
                ['Paiements', $nbPaiements, 'border-sky-400'],
                ['Élèves débiteurs', $nbDebiteurs, 'border-amber-400'],
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
                    <h3 class="font-semibold text-gray-800">Derniers paiements</h3>
                    <a href="{{ route('finance.paiements.index') }}" class="text-xs text-emerald-700 hover:underline">Tout voir</a>
                </div>
                <ul class="divide-y divide-gray-100 text-sm">
                    @forelse ($derniersPaiements as $p)
                        <li class="py-2 flex justify-between">
                            <span>{{ optional($p->eleve)->nom }} {{ optional($p->eleve)->prenom }}
                                <span class="text-gray-400 text-xs">· {{ optional($p->mode)->libelle ?? '—' }}</span>
                            </span>
                            <span class="font-medium text-emerald-700">{{ number_format($p->montant, 0, ',', ' ') }} XAF</span>
                        </li>
                    @empty
                        <li class="py-2 text-gray-500">Aucun paiement enregistré.</li>
                    @endforelse
                </ul>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Accès rapides</h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <a href="{{ route('finance.paiements.create') }}" class="px-4 py-3 rounded-md bg-emerald-50 text-emerald-800 hover:bg-emerald-100 font-medium">+ Encaisser un paiement</a>
                    <a href="{{ route('finance.soldes.index') }}" class="px-4 py-3 rounded-md bg-rose-50 text-rose-800 hover:bg-rose-100 font-medium">Suivi des soldes</a>
                    <a href="{{ route('finance.scolarites.index') }}" class="px-4 py-3 rounded-md bg-sky-50 text-sky-800 hover:bg-sky-100 font-medium">Grille tarifaire</a>
                    <a href="{{ route('finance.rapports.index') }}" class="px-4 py-3 rounded-md bg-amber-50 text-amber-800 hover:bg-amber-100 font-medium">Rapports & export</a>
                </div>
            </div>
        </div>
    </div>
</x-finance-layout>
