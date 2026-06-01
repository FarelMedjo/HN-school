<x-scolarite-layout>
    <x-page-header title="Emploi du temps" subtitle="Consultation par classe" />

    {{-- Sélecteur de classe --}}
    <form method="GET" action="{{ route('scolarite.emploi-du-temps.index') }}"
          class="bg-white rounded-lg shadow-sm p-4 mb-6 flex flex-wrap items-end gap-4">
        <div class="flex-1 min-w-[200px]">
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
        <button type="submit"
                class="px-4 py-2 bg-amber-800 text-white text-sm rounded-md hover:bg-amber-900 transition">
            Afficher
        </button>
    </form>

    @if ($idClasse)
        <x-grille-edt
            :grille="$grille"
            :palette="$palette"
            :classeLibelle="$classeLibelle" />
    @else
        <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
            Sélectionnez une classe pour afficher son emploi du temps.
        </div>
    @endif
</x-scolarite-layout>
