<x-admin-layout>
    <x-page-header title="Utilisateurs & accès"
                   subtitle="Gérez le rôle et l'accès de chaque compte." />

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Nom</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Personne liée</th>
                    <th class="px-4 py-3">Rôle</th>
                    <th class="px-4 py-3">Statut</th>
                    <th class="px-4 py-3 text-right">Accès</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($users as $u)
                    @php
                        $isSelf = $u->id === auth()->id();
                        $liee = $u->personne
                            ? trim($u->personne->nom.' '.$u->personne->prenom)
                            : ($u->eleve ? trim(($u->eleve->nom ?? '').' '.($u->eleve->prenom ?? '')) : null);
                    @endphp
                    <tr class="{{ $u->actif ? '' : 'bg-rose-50/50' }}">
                        <td class="px-4 py-3 font-medium">
                            {{ $u->name }}
                            @if ($isSelf)
                                <span class="ml-1 text-xs text-gray-400">(vous)</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $u->email }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $liee ?: '—' }}</td>
                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('admin.utilisateurs.role', $u) }}" class="inline">
                                @csrf @method('PATCH')
                                <select name="role" onchange="this.form.submit()" @disabled($isSelf)
                                        class="border-gray-300 rounded-md shadow-sm text-sm py-1 disabled:bg-gray-100 disabled:text-gray-400">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" @selected($u->hasRole($role))>{{ ucfirst($role) }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            @if ($u->actif)
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-xs">Actif</span>
                            @else
                                <span class="px-2 py-0.5 bg-rose-100 text-rose-700 rounded text-xs">Désactivé</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if ($isSelf)
                                <span class="text-xs text-gray-400">—</span>
                            @else
                                <form method="POST" action="{{ route('admin.utilisateurs.actif', $u) }}" class="inline"
                                      onsubmit="return confirm('{{ $u->actif ? 'Désactiver' : 'Activer' }} l\'accès de {{ $u->name }} ?')">
                                    @csrf @method('PATCH')
                                    @if ($u->actif)
                                        <button class="text-rose-600 hover:underline">Désactiver</button>
                                    @else
                                        <button class="text-emerald-600 hover:underline">Activer</button>
                                    @endif
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucun utilisateur.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->links() }}</div>
</x-admin-layout>
