<x-admin-layout>
    <x-page-header title="Emploi du temps" />

    @php
        $jours  = \App\Http\Controllers\Admin\EmploiDuTempsController::JOURS;
        $heures = \App\Http\Controllers\Admin\EmploiDuTempsController::heures();
    @endphp

    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.horaires.index') }}"
           class="inline-flex items-center gap-1.5 px-3 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition">
            Gérer les horaires
        </a>
    </div>

    {{-- Sélecteur de classe --}}
    <form method="GET" class="flex items-end gap-4 mb-6 bg-white rounded-lg shadow-sm p-4">
        <div class="flex-1 max-w-xs">
            <label class="block text-xs font-medium text-gray-500 mb-1">Classe</label>
            <select name="idClasse" onchange="this.form.submit()"
                    class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                @foreach ($classes as $c)
                    <option value="{{ $c->idClasse }}" @selected($c->idClasse == $idClasse)>
                        {{ $c->libelle }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="text-sm text-gray-500">
            {{ $cours->count() }} matière(s) · {{ $grille->flatten(1)->count() }} créneau(x)
        </div>
    </form>

    @if ($cours->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
            Aucune matière associée à cette classe. Ajoutez des cours d'abord.
        </div>
    @else
        {{-- Légende --}}
        <div class="flex flex-wrap gap-2 mb-4">
            @foreach ($cours as $c)
                @php $color = $palette[$c->idCours] ?? 'gray'; @endphp
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs bg-{{ $color }}-100 text-{{ $color }}-800 border border-{{ $color }}-200">
                    <span class="w-2 h-2 rounded-full bg-{{ $color }}-500"></span>
                    {{ $c->libelle }}
                </span>
            @endforeach
        </div>

        {{-- Grille --}}
        <div class="bg-white rounded-lg shadow-sm overflow-auto">
            <table class="min-w-full border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-500 w-20 text-center">
                            Horaire
                        </th>
                        @foreach ($jours as $jour)
                            <th class="border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 text-center min-w-[140px]">
                                {{ $jour }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($heures as $heure)
                        @php $isPause = $heure === '12:00' || ($heure === '11:30'); @endphp
                        <tr class="{{ $heure === '13:00' ? 'border-t-2 border-gray-300' : '' }}">
                            <td class="border border-gray-200 px-3 py-2 text-xs font-medium text-gray-500 text-center bg-gray-50 whitespace-nowrap">
                                {{ $heure }}
                            </td>
                            @foreach ($jours as $jour)
                                @php
                                    $slot  = $grille[$jour][$heure] ?? null;
                                    $color = $slot ? ($palette[$slot->idCours] ?? 'gray') : null;
                                @endphp
                                <td class="border border-gray-200 p-1 h-14 align-middle relative"
                                    x-data="{ open: false }">

                                    @if ($slot)
                                        {{-- Créneau rempli --}}
                                        <div class="h-full flex items-center justify-between gap-1 px-2 py-1 rounded bg-{{ $color }}-100 border border-{{ $color }}-200">
                                            <span class="text-xs font-medium text-{{ $color }}-800 leading-tight">
                                                {{ $slot->cours?->libelle ?? '—' }}
                                            </span>
                                            <form method="POST"
                                                  action="{{ route('admin.emploi-du-temps.destroy', $slot) }}"
                                                  onsubmit="return confirm('Supprimer ce créneau ?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        class="text-{{ $color }}-400 hover:text-{{ $color }}-700 text-xs font-bold leading-none">
                                                    ×
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        {{-- Cellule vide --}}
                                        <div class="h-full flex items-center justify-center">
                                            <button @click="open = !open"
                                                    class="w-6 h-6 flex items-center justify-center rounded-full text-gray-300 hover:bg-emerald-50 hover:text-emerald-600 transition text-lg font-light leading-none">
                                                +
                                            </button>

                                            {{-- Formulaire inline --}}
                                            <div x-show="open" x-cloak
                                                 @click.outside="open = false"
                                                 class="absolute z-20 top-full left-0 mt-1 w-52 bg-white border border-gray-200 rounded-lg shadow-xl p-3">
                                                <form method="POST" action="{{ route('admin.emploi-du-temps.store') }}">
                                                    @csrf
                                                    <input type="hidden" name="idClasse" value="{{ $idClasse }}">
                                                    <input type="hidden" name="jour"     value="{{ $jour }}">
                                                    <input type="hidden" name="heure"    value="{{ $heure }}">
                                                    <p class="text-xs text-gray-500 mb-2 font-medium">
                                                        {{ $jour }} · {{ $heure }}
                                                    </p>
                                                    <select name="idCours" required
                                                            class="w-full border-gray-300 rounded text-xs mb-2">
                                                        <option value="">— Matière —</option>
                                                        @foreach ($cours as $c)
                                                            <option value="{{ $c->idCours }}">{{ $c->libelle }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="flex gap-2">
                                                        <button type="submit"
                                                                class="flex-1 py-1 bg-emerald-600 text-white text-xs rounded hover:bg-emerald-700">
                                                            Enregistrer
                                                        </button>
                                                        <button type="button" @click="open = false"
                                                                class="flex-1 py-1 border border-gray-200 text-gray-600 text-xs rounded hover:bg-gray-50">
                                                            Annuler
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="text-xs text-gray-400 mt-3">
            Cliquez sur <strong>+</strong> pour ajouter un créneau · <strong>×</strong> pour le supprimer.
        </p>
    @endif
</x-admin-layout>
