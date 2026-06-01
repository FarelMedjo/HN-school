<x-finance-layout>
    <x-page-header title="Suivi des soldes" subtitle="Année active : {{ $annee->libelle ?? '—' }}" />

    @php
        $totDu = $soldes->sum('du');
        $totPaye = $soldes->sum('paye');
        $totReste = $soldes->sum('reste');
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-sky-400">
            <div class="text-xs uppercase tracking-wider text-gray-500">Total dû</div>
            <div class="text-2xl font-bold text-gray-800 mt-2">{{ number_format($totDu, 0, ',', ' ') }} XAF</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-emerald-400">
            <div class="text-xs uppercase tracking-wider text-gray-500">Total payé</div>
            <div class="text-2xl font-bold text-gray-800 mt-2">{{ number_format($totPaye, 0, ',', ' ') }} XAF</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-rose-400">
            <div class="text-xs uppercase tracking-wider text-gray-500">Reste à payer</div>
            <div class="text-2xl font-bold text-gray-800 mt-2">{{ number_format($totReste, 0, ',', ' ') }} XAF</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
            <span class="text-sm text-gray-600"><strong>{{ $soldes->count() }}</strong> élève(s) inscrit(s)</span>
            <a href="{{ route('finance.rapports.export') }}"
               class="text-xs px-3 py-1.5 rounded bg-emerald-600 text-white hover:bg-emerald-700">Exporter CSV</a>
        </div>
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Élève</th>
                    <th class="px-4 py-3">Classe</th>
                    <th class="px-4 py-3 text-right">Dû</th>
                    <th class="px-4 py-3 text-right">Payé</th>
                    <th class="px-4 py-3 text-right">Reste</th>
                    <th class="px-4 py-3">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($soldes as $s)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">{{ $s['nom'] }} {{ $s['prenom'] }}</td>
                        <td class="px-4 py-3">{{ $s['classe'] }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($s['du'], 0, ',', ' ') }}</td>
                        <td class="px-4 py-3 text-right text-emerald-700">{{ number_format($s['paye'], 0, ',', ' ') }}</td>
                        <td class="px-4 py-3 text-right font-medium {{ $s['reste'] > 0 ? 'text-rose-600' : 'text-gray-400' }}">
                            {{ number_format($s['reste'], 0, ',', ' ') }}
                        </td>
                        <td class="px-4 py-3">
                            @if ($s['reste'] <= 0)
                                <span class="px-2 py-0.5 rounded text-xs bg-emerald-100 text-emerald-700">Soldé</span>
                            @elseif ($s['paye'] > 0)
                                <span class="px-2 py-0.5 rounded text-xs bg-amber-100 text-amber-700">Partiel</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-xs bg-rose-100 text-rose-700">Impayé</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucun élève inscrit sur l'année active.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-finance-layout>
