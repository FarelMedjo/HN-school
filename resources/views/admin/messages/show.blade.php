<x-admin-layout>
    <x-page-header title="Détail du message"
                   subtitle="Envoyé le {{ $message->created_at->isoFormat('D MMMM YYYY à HH:mm') }}" />

    <div class="max-w-2xl bg-white rounded-lg shadow-sm p-6 space-y-5">

        @php $type = \App\Models\Message::TYPES[$message->type_message] ?? ['label'=>'Message','color'=>'gray']; @endphp

        <div class="flex items-center gap-3">
            <span class="inline-block px-3 py-1 rounded-full text-sm bg-{{ $type['color'] }}-100 text-{{ $type['color'] }}-700 font-medium">
                {{ $type['label'] }}
            </span>
            @if ($message->valider)
                <span class="text-xs text-emerald-600 inline-flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Lu par le parent
                </span>
            @else
                <span class="text-xs text-amber-600 inline-flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Non lu
                </span>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-500">Destinataire</span>
                <div class="font-medium mt-0.5">
                    {{ optional($message->destinataire)->nom }}
                    {{ optional($message->destinataire)->prenom }}
                </div>
            </div>
            <div>
                <span class="text-gray-500">Année académique</span>
                <div class="font-medium mt-0.5">{{ $message->AnneeAcade ?? '—' }}</div>
            </div>
        </div>

        <div>
            <div class="text-gray-500 text-sm mb-1">Objet</div>
            <div class="text-base font-semibold text-gray-800">{{ $message->objet }}</div>
        </div>

        <div>
            <div class="text-gray-500 text-sm mb-2">Message</div>
            <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $message->information }}</div>
        </div>

        <div class="flex gap-3 pt-2">
            <a href="{{ route('admin.messages.index') }}"
               class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">← Retour</a>
            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}"
                  onsubmit="return confirm('Supprimer ce message ?')">
                @csrf @method('DELETE')
                <button class="px-4 py-2 text-sm text-rose-600 hover:text-rose-800">Supprimer</button>
            </form>
        </div>
    </div>
</x-admin-layout>
