<x-parent-layout>
    <x-page-header title="Mes messages" />

    @if ($messages->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
            Aucun message reçu.
        </div>
    @else
        <div class="space-y-3">
            @foreach ($messages as $m)
                @php $type = \App\Models\Message::TYPES[$m->type_message] ?? ['label'=>'Message','color'=>'gray']; @endphp
                <a href="{{ route('parent.messages.show', $m) }}"
                   class="block bg-white rounded-lg shadow-sm px-5 py-4 hover:shadow-md transition
                          {{ !$m->valider ? 'border-l-4 border-teal-500' : '' }}">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="inline-block px-2 py-0.5 rounded text-xs bg-{{ $type['color'] }}-100 text-{{ $type['color'] }}-700 font-medium">
                                    {{ $type['label'] }}
                                </span>
                                @if (!$m->valider)
                                    <span class="inline-block w-2 h-2 rounded-full bg-teal-500"></span>
                                @endif
                            </div>
                            <div class="font-{{ !$m->valider ? 'semibold' : 'normal' }} text-gray-800 truncate">
                                {{ $m->objet }}
                            </div>
                            <div class="text-sm text-gray-500 mt-0.5 truncate">
                                {{ \Illuminate\Support\Str::limit($m->information, 80) }}
                            </div>
                        </div>
                        <div class="text-xs text-gray-400 whitespace-nowrap shrink-0">
                            {{ $m->created_at->isoFormat('D MMM YYYY') }}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-4">{{ $messages->links() }}</div>
    @endif
</x-parent-layout>
