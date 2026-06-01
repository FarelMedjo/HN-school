<x-admin-layout>
    <x-page-header title="Établissement" subtitle="Cachet, signature et identité affichés sur les bulletins" />

    <form method="POST" action="{{ route('admin.etablissement.update') }}" enctype="multipart/form-data"
          class="bg-white p-6 rounded-lg shadow-sm space-y-6 max-w-3xl">
        @csrf

        {{-- Nom du chef d'établissement --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Nom du chef d'établissement</label>
            <input type="text" name="directeur_nom" value="{{ old('directeur_nom', $directeurNom) }}" maxlength="150"
                   placeholder="Ex. M. NKOMO Paul"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <p class="text-xs text-gray-400 mt-1">Affiché sous la signature de la Direction sur le bulletin.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            {{-- Cachet --}}
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Cachet de l'école</h3>
                <div class="h-32 flex items-center justify-center bg-gray-50 rounded mb-3 overflow-hidden">
                    @if ($cachetUrl)
                        <img src="{{ $cachetUrl }}" alt="Cachet" class="max-h-28 object-contain">
                    @else
                        <span class="text-xs text-gray-400">Aucun cachet</span>
                    @endif
                </div>
                <input type="file" name="cachet" accept="image/png,image/jpeg,image/webp"
                       class="block w-full text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-blue-950 file:text-white">
                @if ($cachetUrl)
                    <label class="flex items-center gap-2 mt-2 text-xs text-rose-600">
                        <input type="checkbox" name="supprimer_cachet" value="1" class="rounded border-gray-300">
                        Supprimer le cachet actuel
                    </label>
                @endif
            </div>

            {{-- Signature --}}
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Signature de la direction</h3>
                <div class="h-32 flex items-center justify-center bg-gray-50 rounded mb-3 overflow-hidden">
                    @if ($signatureUrl)
                        <img src="{{ $signatureUrl }}" alt="Signature" class="max-h-28 object-contain">
                    @else
                        <span class="text-xs text-gray-400">Aucune signature</span>
                    @endif
                </div>
                <input type="file" name="signature" accept="image/png,image/jpeg,image/webp"
                       class="block w-full text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-blue-950 file:text-white">
                @if ($signatureUrl)
                    <label class="flex items-center gap-2 mt-2 text-xs text-rose-600">
                        <input type="checkbox" name="supprimer_signature" value="1" class="rounded border-gray-300">
                        Supprimer la signature actuelle
                    </label>
                @endif
            </div>
        </div>

        <p class="text-xs text-gray-400">
            Formats acceptés : PNG, JPG, WEBP (max 2 Mo). Une image de signature au fond transparent (PNG) rend le mieux sur le bulletin.
        </p>

        <div class="flex gap-3">
            <button class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Enregistrer</button>
        </div>
    </form>
</x-admin-layout>
