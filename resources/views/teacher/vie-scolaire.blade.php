<x-teacher-layout>
    <x-page-header title="Convocations & appréciations" subtitle="Pour les élèves de vos classes" />

    @if ($eleves->isEmpty())
        <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-md p-4">
            Aucun élève rattaché à vos cours pour l'année en cours.
        </div>
    @else
        @php
            $eleveOptions = $eleves->map(fn ($e) => ['v' => $e->matricule, 'l' => $e->nom . ' ' . $e->prenom]);
        @endphp

        <div class="grid gap-5 lg:grid-cols-3">
            {{-- Appréciation --}}
            <div class="bg-white rounded-lg shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Appréciation (bulletin)</h3>
                <form method="POST" action="{{ route('teacher.vie-scolaire.appreciations') }}" class="space-y-3">
                    @csrf
                    <select name="matricule" required class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <option value="">— Élève —</option>
                        @foreach ($eleveOptions as $o)
                            <option value="{{ $o['v'] }}">{{ $o['l'] }}</option>
                        @endforeach
                    </select>
                    <select name="idTrimes" required class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <option value="">— Trimestre —</option>
                        @foreach ($trimestres as $t)
                            <option value="{{ $t->idTrimes }}">{{ $t->libelle }}{{ $t->annee ? ' — ' . $t->annee->libelle : '' }}</option>
                        @endforeach
                    </select>
                    <textarea name="contenu" rows="3" required placeholder="Appréciation du trimestre"
                              class="w-full border-gray-300 rounded-md shadow-sm text-sm"></textarea>
                    <button class="w-full py-2 bg-indigo-700 text-white text-sm rounded-md hover:bg-indigo-800">Enregistrer</button>
                </form>
            </div>

            {{-- Remarque --}}
            <div class="bg-white rounded-lg shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Remarque (journal)</h3>
                <form method="POST" action="{{ route('teacher.vie-scolaire.remarques') }}" class="space-y-3">
                    @csrf
                    <select name="matricule" required class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <option value="">— Élève —</option>
                        @foreach ($eleveOptions as $o)
                            <option value="{{ $o['v'] }}">{{ $o['l'] }}</option>
                        @endforeach
                    </select>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="date" name="date" value="{{ now()->toDateString() }}" required
                               class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <select name="categorie" required class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                            @foreach (\App\Models\Remarque::CATEGORIES as $key => $cat)
                                <option value="{{ $key }}">{{ $cat['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <textarea name="contenu" rows="3" required placeholder="Observation"
                              class="w-full border-gray-300 rounded-md shadow-sm text-sm"></textarea>
                    <button class="w-full py-2 bg-indigo-700 text-white text-sm rounded-md hover:bg-indigo-800">Enregistrer</button>
                </form>
            </div>

            {{-- Convocation --}}
            <div class="bg-white rounded-lg shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Convocation</h3>
                <form method="POST" action="{{ route('teacher.vie-scolaire.convocations') }}" class="space-y-3">
                    @csrf
                    <select name="matricule" required class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <option value="">— Élève —</option>
                        @foreach ($eleveOptions as $o)
                            <option value="{{ $o['v'] }}">{{ $o['l'] }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="objet" required maxlength="255" placeholder="Objet"
                           class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                    <input type="datetime-local" name="dateRdv" value="{{ now()->addDays(3)->format('Y-m-d\TH:i') }}" required
                           class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                    <input type="text" name="lieu" value="Secrétariat de l'établissement" maxlength="255"
                           class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                    <textarea name="motif" rows="2" placeholder="Motif détaillé (optionnel)"
                              class="w-full border-gray-300 rounded-md shadow-sm text-sm"></textarea>
                    <button class="w-full py-2 bg-indigo-700 text-white text-sm rounded-md hover:bg-indigo-800">Enregistrer & imprimer</button>
                </form>
            </div>
        </div>

        {{-- Récents --}}
        <div class="grid gap-5 lg:grid-cols-3 mt-6">
            <div class="bg-white rounded-lg shadow-sm p-5">
                <h4 class="text-xs font-semibold uppercase text-gray-400 mb-2">Mes appréciations</h4>
                <ul class="divide-y divide-gray-100 text-sm">
                    @forelse ($appreciations as $a)
                        <li class="py-2">
                            <span class="font-medium">{{ optional($a->eleve)->nom }} {{ optional($a->eleve)->prenom }}</span>
                            <span class="text-xs text-gray-400">· {{ optional($a->trimestre)->libelle }}</span>
                            <p class="text-gray-600">{{ \Illuminate\Support\Str::limit($a->contenu, 90) }}</p>
                        </li>
                    @empty
                        <li class="py-2 text-gray-400">Aucune.</li>
                    @endforelse
                </ul>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <h4 class="text-xs font-semibold uppercase text-gray-400 mb-2">Mes remarques</h4>
                <ul class="divide-y divide-gray-100 text-sm">
                    @forelse ($remarques as $r)
                        <li class="py-2">
                            <span class="px-1.5 py-0.5 rounded text-xs bg-{{ $r->categorie_color }}-100 text-{{ $r->categorie_color }}-700">{{ $r->categorie_label }}</span>
                            <span class="font-medium">{{ optional($r->eleve)->nom }} {{ optional($r->eleve)->prenom }}</span>
                            <span class="text-xs text-gray-400">· {{ $r->date->format('d/m/Y') }}</span>
                            <p class="text-gray-600">{{ \Illuminate\Support\Str::limit($r->contenu, 90) }}</p>
                        </li>
                    @empty
                        <li class="py-2 text-gray-400">Aucune.</li>
                    @endforelse
                </ul>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <h4 class="text-xs font-semibold uppercase text-gray-400 mb-2">Mes convocations</h4>
                <ul class="divide-y divide-gray-100 text-sm">
                    @forelse ($convocations as $c)
                        <li class="py-2 flex items-center justify-between gap-2">
                            <div>
                                <span class="font-medium">{{ optional($c->eleve)->nom }} {{ optional($c->eleve)->prenom }}</span>
                                <p class="text-gray-600">{{ \Illuminate\Support\Str::limit($c->objet, 60) }} · {{ $c->dateRdv->format('d/m/Y H:i') }}</p>
                            </div>
                            <a href="{{ route('teacher.convocations.show', $c) }}" target="_blank" class="shrink-0 text-blue-600 hover:underline text-xs">Imprimer</a>
                        </li>
                    @empty
                        <li class="py-2 text-gray-400">Aucune.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    @endif
</x-teacher-layout>
