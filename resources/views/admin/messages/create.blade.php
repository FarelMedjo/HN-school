<x-admin-layout>
    <x-page-header title="Nouveau message" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <form method="POST" action="{{ route('admin.messages.store') }}"
              class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6 space-y-5">
            @csrf

            {{-- Objet --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Objet *</label>
                <input type="text" name="objet" value="{{ old('objet') }}" required maxlength="255"
                       class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                       placeholder="Ex : Réunion de parents, Résultats T1…">
                @error('objet') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Type --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                <div class="flex gap-4 flex-wrap">
                    @foreach (\App\Models\Message::TYPES as $val => $t)
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="type_message" value="{{ $val }}"
                                   @checked(old('type_message', 1) == $val)
                                   class="text-emerald-600 border-gray-300">
                            <span class="text-sm px-3 py-1 rounded-full bg-{{ $t['color'] }}-100 text-{{ $t['color'] }}-700">
                                {{ $t['label'] }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Corps --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Message *</label>
                <textarea name="information" rows="7" required
                          class="w-full border-gray-300 rounded-md shadow-sm text-sm"
                          placeholder="Rédigez votre message ici…">{{ old('information') }}</textarea>
                @error('information') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition">
                    Envoyer le message
                </button>
                <a href="{{ route('admin.messages.index') }}"
                   class="px-5 py-2 text-sm text-gray-600 hover:text-gray-800">
                    Annuler
                </a>
            </div>
        </form>

        {{-- Sélection des destinataires --}}
        <div class="bg-white rounded-lg shadow-sm p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">
                Destinataires *
                <span class="text-xs font-normal text-gray-400 ml-1" id="sel-count"></span>
            </h3>

            <button type="button" onclick="toggleAll(true)"
                    class="text-xs text-emerald-600 hover:underline mr-3">Tous</button>
            <button type="button" onclick="toggleAll(false)"
                    class="text-xs text-gray-500 hover:underline">Aucun</button>

            <div class="mt-3 space-y-1 max-h-80 overflow-y-auto pr-1" id="parent-list">
                @forelse ($parents as $p)
                    <label class="flex items-center gap-2 px-2 py-1.5 rounded hover:bg-gray-50 cursor-pointer text-sm">
                        <input type="checkbox"
                               form="msg-form-hidden"
                               name="destinataires[]"
                               value="{{ $p->idPers }}"
                               class="dest-cb text-emerald-600 border-gray-300 rounded"
                               @checked(in_array($p->idPers, old('destinataires', [])))>
                        <span>{{ $p->nom }} {{ $p->prenom }}</span>
                        <span class="text-xs text-gray-400">{{ $p->mobile }}</span>
                    </label>
                @empty
                    <p class="text-sm text-gray-500 italic">Aucun parent enregistré.</p>
                @endforelse
            </div>
            @error('destinataires') <p class="text-xs text-rose-600 mt-2">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Les checkboxes sont dans un form séparé pour être soumises avec le form principal --}}
    {{-- On les déplace dans le form principal via JS --}}
    <form id="msg-form-hidden" style="display:none"></form>

    <script>
        // Déplacer les checkboxes dans le form principal au submit
        document.querySelector('form[method=POST]').addEventListener('submit', function (e) {
            const cbs = document.querySelectorAll('.dest-cb:checked');
            if (cbs.length === 0) {
                e.preventDefault();
                alert('Veuillez sélectionner au moins un destinataire.');
                return;
            }
            cbs.forEach(cb => {
                const input = document.createElement('input');
                input.type  = 'hidden';
                input.name  = 'destinataires[]';
                input.value = cb.value;
                this.appendChild(input);
            });
        });

        function toggleAll(checked) {
            document.querySelectorAll('.dest-cb').forEach(cb => cb.checked = checked);
            updateCount();
        }

        function updateCount() {
            const n = document.querySelectorAll('.dest-cb:checked').length;
            document.getElementById('sel-count').textContent = n > 0 ? n + ' sélectionné(s)' : '';
        }

        document.getElementById('parent-list').addEventListener('change', updateCount);
    </script>
</x-admin-layout>
