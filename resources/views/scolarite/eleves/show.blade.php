<x-scolarite-layout>
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
            <a href="{{ route('scolarite.eleves.edit', $eleve) }}" class="inline-block mt-2 text-sky-600 hover:underline">Modifier la fiche</a>
        </div>

        {{-- Affectation --}}
        <div class="bg-white rounded-lg shadow-sm p-6 lg:col-span-2">
            <h3 class="font-semibold text-gray-800 mb-4">Affectation classe</h3>
            <form method="POST" action="{{ route('scolarite.eleves.affecter', $eleve) }}" class="space-y-3">
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
    </div>
</x-scolarite-layout>
