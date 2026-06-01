@props(['grille', 'palette', 'classeLibelle' => null])

@php
    $jours  = \App\Http\Controllers\Admin\EmploiDuTempsController::JOURS;
    $heures = \App\Http\Controllers\Admin\EmploiDuTempsController::heures();
    $total  = collect($grille)->flatten(1)->count();
@endphp

@if ($total === 0)
    <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
        Aucun créneau enregistré{{ $classeLibelle ? ' pour la classe ' . $classeLibelle : '' }}.
    </div>
@else
    <div class="bg-white rounded-lg shadow-sm overflow-auto">
        <table class="min-w-full border-collapse text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-500 w-20 text-center">
                        Horaire
                    </th>
                    @foreach ($jours as $jour)
                        <th class="border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 text-center min-w-[120px]">
                            {{ $jour }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($heures as $heure)
                    <tr class="{{ $heure === '13:00' ? 'border-t-2 border-gray-300' : '' }}">
                        <td class="border border-gray-200 px-3 py-2 text-xs font-medium text-gray-500 text-center bg-gray-50 whitespace-nowrap">
                            {{ $heure }}
                        </td>
                        @foreach ($jours as $jour)
                            @php
                                $slot  = $grille[$jour][$heure] ?? null;
                                $color = $slot ? ($palette[$slot->idCours] ?? 'gray') : null;
                            @endphp
                            <td class="border border-gray-200 p-1 h-12 align-middle">
                                @if ($slot)
                                    <div class="h-full flex items-center px-2 py-1 rounded bg-{{ $color }}-100 border border-{{ $color }}-200">
                                        <span class="text-xs font-medium text-{{ $color }}-800 leading-tight">
                                            {{ $slot->cours?->libelle ?? '—' }}
                                        </span>
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
