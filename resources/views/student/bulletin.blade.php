<x-student-layout>
    <x-page-header
        title="Mon bulletin"
        subtitle="Classe {{ $classe ?? '—' }}" />

    <div class="mt-6">
        @if ($trimestres->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
                Aucun trimestre disponible pour cette année.
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm p-6">
                <p class="text-sm text-gray-600 mb-4">
                    Sélectionnez un trimestre pour afficher et imprimer votre bulletin de notes.
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    @foreach ($trimestres as $t)
                        <a href="{{ route('student.bulletin.show', $t) }}"
                           target="_blank"
                           class="inline-flex items-center gap-2 px-5 py-3 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700 transition">
                            📄 {{ $t->libelle }}
                        </a>
                    @endforeach
                </div>
                <p class="text-xs text-gray-400 mt-4">
                    Le bulletin s'ouvre dans un nouvel onglet. Utilisez la fonction d'impression de votre navigateur (Ctrl+P) pour l'enregistrer en PDF.
                </p>
            </div>
        @endif
    </div>
</x-student-layout>
