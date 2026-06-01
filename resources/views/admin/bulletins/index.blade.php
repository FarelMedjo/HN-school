<x-admin-layout>
    <x-page-header title="Bulletins de notes" />

    <div class="bg-white rounded-lg shadow-sm p-6 max-w-xl">
        <p class="text-sm text-gray-600 mb-5">
            Sélectionnez un élève et un trimestre pour générer et imprimer le bulletin.
        </p>
        <form method="GET" id="bulletin-form" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Élève *</label>
                <select name="eleve" required
                        class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                    <option value="">— Choisir un élève —</option>
                    @foreach ($eleves as $e)
                        <option value="{{ $e->matricule }}">
                            {{ $e->nom }} {{ $e->prenom }}
                            (Mat. {{ str_pad($e->matricule, 5, '0', STR_PAD_LEFT) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Trimestre *</label>
                <select name="trimestre" required
                        class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                    <option value="">— Choisir un trimestre —</option>
                    @foreach ($trimestres as $t)
                        <option value="{{ $t->idTrimes }}">
                            {{ $t->libelle }}
                            @if ($t->annee) · {{ $t->annee->libelle }} @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                    class="px-5 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition">
                Générer le bulletin →
            </button>
        </form>
    </div>

    <script>
        document.getElementById('bulletin-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const eleve     = this.querySelector('[name=eleve]').value;
            const trimestre = this.querySelector('[name=trimestre]').value;
            if (!eleve || !trimestre) return;
            window.location.href = '{{ url("admin/bulletins") }}/' + eleve + '/' + trimestre;
        });
    </script>
</x-admin-layout>
