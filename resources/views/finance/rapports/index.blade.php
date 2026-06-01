<x-finance-layout>
    <x-page-header title="Rapports & export" subtitle="Année active : {{ $annee->libelle ?? '—' }}" />

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-sky-400">
            <div class="text-xs uppercase tracking-wider text-gray-500">Total dû</div>
            <div class="text-2xl font-bold text-gray-800 mt-2">{{ number_format($totalDu, 0, ',', ' ') }} XAF</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-emerald-400">
            <div class="text-xs uppercase tracking-wider text-gray-500">Total encaissé</div>
            <div class="text-2xl font-bold text-gray-800 mt-2">{{ number_format($totalPaye, 0, ',', ' ') }} XAF</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-rose-400">
            <div class="text-xs uppercase tracking-wider text-gray-500">Reste à payer</div>
            <div class="text-2xl font-bold text-gray-800 mt-2">{{ number_format($resteAPayer, 0, ',', ' ') }} XAF</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-amber-400">
            <div class="text-xs uppercase tracking-wider text-gray-500">Taux de recouvrement</div>
            <div class="text-2xl font-bold text-gray-800 mt-2">{{ $tauxRecouvrement }} %</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Encaissements par mode</h3>
            </div>
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Mode</th>
                        <th class="px-4 py-3 text-right">Nb</th>
                        <th class="px-4 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($parMode as $row)
                        <tr>
                            <td class="px-4 py-3">{{ $row->libelle }}</td>
                            <td class="px-4 py-3 text-right">{{ $row->nb }}</td>
                            <td class="px-4 py-3 text-right font-medium">{{ number_format($row->total, 0, ',', ' ') }} XAF</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-6 text-center text-gray-500">Aucun encaissement.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-2">Export des données</h3>
            <p class="text-sm text-gray-500 mb-4">Téléchargez l'état des soldes par élève (format CSV, compatible Excel).</p>
            <a href="{{ route('finance.rapports.export') }}"
               class="inline-block px-5 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700">
                Exporter les soldes (CSV)
            </a>
        </div>
    </div>
</x-finance-layout>
