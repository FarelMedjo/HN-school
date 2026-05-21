<x-admin-layout>
    <x-page-header title="{{ $eleve->nom }} {{ $eleve->prenom }}"
                   subtitle="Matricule {{ str_pad($eleve->matricule, 5, '0', STR_PAD_LEFT) }}" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Infos --}}
        <div class="bg-white rounded-lg shadow-sm p-6 lg:col-span-1 space-y-2 text-sm">
            <h3 class="font-semibold text-gray-800 mb-2">Informations</h3>
            <div><span class="text-gray-500">Date naissance :</span> {{ optional($eleve->dateNaissance)->format('d/m/Y') ?? '—' }}</div>
            <div><span class="text-gray-500">Lieu :</span> {{ $eleve->lieuNaissance ?? '—' }}</div>
            <div><span class="text-gray-500">Ville :</span> {{ optional($eleve->villeNaissance)->libelle ?? '—' }}</div>
            <div><span class="text-gray-500">Sexe :</span> {{ $eleve->sexe == 0 ? 'Masculin' : 'Féminin' }}</div>
            <div><span class="text-gray-500">Langue :</span> {{ $eleve->langue ?? '—' }}</div>
        </div>

        {{-- Affectation --}}
        <div class="bg-white rounded-lg shadow-sm p-6 lg:col-span-2">
            <h3 class="font-semibold text-gray-800 mb-4">Affectation classe</h3>
            <form method="POST" action="{{ route('admin.eleves.affecter', $eleve) }}" class="space-y-3">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <select name="idAcademi" required class="border-gray-300 rounded-md shadow-sm">
                        <option value="">— Année académique —</option>
                        @foreach ($annees as $a)
                            <option value="{{ $a->idAnnee }}" @selected($a->actif)>{{ $a->libelle }} {{ $a->actif ? '(active)' : '' }}</option>
                        @endforeach
                    </select>
                    <select name="idSalle" required class="border-gray-300 rounded-md shadow-sm">
                        <option value="">— Salle / Classe —</option>
                        @foreach ($salles as $s)
                            <option value="{{ $s->idSalle }}">{{ $s->libelle }} — {{ optional($s->classe)->libelle }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="text" name="commentaire" placeholder="Commentaire (facultatif)"
                       class="w-full border-gray-300 rounded-md shadow-sm">
                <button class="px-4 py-2 bg-emerald-600 text-white rounded-md text-sm">Enregistrer l'affectation</button>
            </form>

            <h4 class="font-semibold text-gray-700 mt-6 mb-2 text-sm">Historique des affectations</h4>
            <ul class="text-sm divide-y divide-gray-100">
                @forelse ($eleve->frequentations as $f)
                    <li class="py-2 flex justify-between">
                        <span>{{ optional(optional($f->salle)->classe)->libelle ?? '—' }} ({{ optional($f->salle)->libelle }})</span>
                        <span class="text-gray-500 text-xs">Année #{{ $f->idAcademi }}</span>
                    </li>
                @empty
                    <li class="py-2 text-gray-500">Aucune affectation.</li>
                @endforelse
            </ul>
        </div>

        {{-- Paiements --}}
        <div class="bg-white rounded-lg shadow-sm p-6 lg:col-span-3">
            <h3 class="font-semibold text-gray-800 mb-3">Paiements</h3>
            @php
                $total = $eleve->paiements->sum('montant');
            @endphp
            <div class="text-sm mb-3">Total payé : <strong>{{ number_format($total, 0, ',', ' ') }} XAF</strong></div>
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-3 py-2">Date</th>
                        <th class="px-3 py-2">Montant</th>
                        <th class="px-3 py-2">Mode</th>
                        <th class="px-3 py-2">Commentaire</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($eleve->paiements as $p)
                        <tr>
                            <td class="px-3 py-2">{{ optional($p->datePaie)->format('d/m/Y') }}</td>
                            <td class="px-3 py-2">{{ number_format($p->montant, 0, ',', ' ') }} XAF</td>
                            <td class="px-3 py-2">{{ optional($p->mode)->libelle ?? '—' }}</td>
                            <td class="px-3 py-2 text-gray-600">{{ $p->commentaire }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-3 py-4 text-center text-gray-500">Aucun paiement.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
