<x-admin-layout>
    <x-page-header title="Messages envoyés"
                   :createRoute="route('admin.messages.create')"
                   createLabel="Nouveau message" />

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 text-left">
                <tr>
                    <th class="px-4 py-3">Destinataire</th>
                    <th class="px-4 py-3">Objet</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3">Envoyé le</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($messages as $m)
                    @php $type = \App\Models\Message::TYPES[$m->type_message] ?? ['label'=>'—','color'=>'gray']; @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">
                            {{ optional($m->destinataire)->nom }}
                            {{ optional($m->destinataire)->prenom }}
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.messages.show', $m) }}"
                               class="hover:text-emerald-700 hover:underline">
                                {{ $m->objet }}
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-2 py-0.5 rounded text-xs bg-{{ $type['color'] }}-100 text-{{ $type['color'] }}-700">
                                {{ $type['label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if ($m->valider)
                                <span class="inline-flex items-center gap-1 text-xs text-emerald-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Lu
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs text-amber-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Non lu
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">
                            {{ $m->created_at->isoFormat('D MMM YYYY, HH:mm') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST"
                                  action="{{ route('admin.messages.destroy', $m) }}"
                                  onsubmit="return confirm('Supprimer ce message ?')"
                                  class="inline">
                                @csrf @method('DELETE')
                                <button class="text-rose-600 hover:underline text-xs">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            Aucun message envoyé.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $messages->links() }}</div>
</x-admin-layout>
