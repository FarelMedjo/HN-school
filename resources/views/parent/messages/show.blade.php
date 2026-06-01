<x-parent-layout>
    <x-page-header
        title="{{ $message->objet }}"
        subtitle="{{ $message->created_at->isoFormat('dddd D MMMM YYYY à HH:mm') }}" />

    @php $type = \App\Models\Message::TYPES[$message->type_message] ?? ['label'=>'Message','color'=>'gray']; @endphp

    <div class="max-w-2xl bg-white rounded-lg shadow-sm p-6 space-y-5">

        <div class="flex items-center gap-3">
            <span class="inline-block px-3 py-1 rounded-full text-sm bg-{{ $type['color'] }}-100 text-{{ $type['color'] }}-700 font-medium">
                {{ $type['label'] }}
            </span>
            @if ($message->AnneeAcade)
                <span class="text-xs text-gray-500">Année {{ $message->AnneeAcade }}</span>
            @endif
        </div>

        <div class="text-sm text-gray-500">
            De : <span class="font-medium text-gray-700">
                {{ optional($message->expediteur)->nom
                    ? optional($message->expediteur)->nom . ' ' . optional($message->expediteur)->prenom
                    : 'Administration' }}
            </span>
        </div>

        <div class="bg-gray-50 rounded-lg p-5 text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">
            {{ $message->information }}
        </div>

        <a href="{{ route('parent.messages') }}"
           class="inline-block text-sm text-teal-600 hover:underline">← Retour à la boîte de réception</a>
    </div>
</x-parent-layout>
