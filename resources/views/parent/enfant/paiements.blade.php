<x-parent-layout>
    <x-page-header
        title="{{ $eleve->prenom }} {{ $eleve->nom }}"
        subtitle="Paiements · {{ $annee?->libelle ?? 'Année en cours' }}" />

    @include('parent.enfant._tabs', ['active' => 'paiements', 'eleve' => $eleve])

    <div class="mt-6">
        {{-- Résumé total --}}
        <div class="bg-white rounded-lg shadow-sm p-5 mb-6 flex items-center justify-between">
            <div>
                <div class="text-sm text-gray-500">Total versé — {{ $annee?->libelle ?? 'année active' }}</div>
                <div class="text-3xl font-bold text-emerald-600 mt-1">
                    {{ number_format($totalPaye, 0, ',', ' ') }} <span class="text-base font-normal text-gray-500">FCFA</span>
                </div>
            </div>
            <div class="text-5xl opacity-20">💳</div>
        </div>

        {{-- Liste des paiements --}}
        @if ($paiements->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
                Aucun paiement enregistré pour cette année.
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 text-left">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3 text-right">Montant</th>
                            <th class="px-4 py-3">Mode</th>
                            <th class="px-4 py-3">Réf. opération</th>
                            <th class="px-4 py-3">Commentaire</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($paiements as $p)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    {{ optional($p->datePaie)->isoFormat('D MMM YYYY') ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-right font-semibold text-emerald-700">
                                    {{ number_format($p->montant, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-block px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs">
                                        {{ optional($p->mode)->libelle ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-500 font-mono text-xs">
                                    {{ $p->operation_ID ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-500 text-xs">{{ $p->commentaire ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-700">Total</td>
                            <td class="px-4 py-3 text-right font-bold text-emerald-700">
                                {{ number_format($totalPaye, 0, ',', ' ') }} FCFA
                            </td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
</x-parent-layout>
