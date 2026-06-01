@props([
    'convocations' => collect(),
    'appreciations' => collect(),
    'remarques' => collect(),
    'printRouteName' => null,
])

<div class="space-y-6">
    {{-- Convocations --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b bg-amber-50 text-amber-900 font-semibold text-sm">Convocations</div>
        @if ($convocations->isEmpty())
            <div class="p-5 text-sm text-gray-500">Aucune convocation.</div>
        @else
            <ul class="divide-y divide-gray-100">
                @foreach ($convocations as $c)
                    <li class="px-5 py-3 flex items-start justify-between gap-4">
                        <div>
                            <div class="font-medium text-gray-800">{{ $c->objet }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">
                                Rendez-vous : {{ $c->dateRdv->isoFormat('dddd D MMMM YYYY [à] HH[h]mm') }}
                                @if ($c->lieu) · {{ $c->lieu }} @endif
                            </div>
                            @if ($c->motif)
                                <p class="text-sm text-gray-600 mt-1 whitespace-pre-line">{{ $c->motif }}</p>
                            @endif
                        </div>
                        @if ($printRouteName)
                            <a href="{{ route($printRouteName, $c) }}" target="_blank"
                               class="shrink-0 px-3 py-1.5 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Voir / Imprimer
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Appréciations par trimestre --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b bg-emerald-50 text-emerald-900 font-semibold text-sm">Appréciations</div>
        @if ($appreciations->isEmpty())
            <div class="p-5 text-sm text-gray-500">Aucune appréciation.</div>
        @else
            <ul class="divide-y divide-gray-100">
                @foreach ($appreciations as $a)
                    <li class="px-5 py-3">
                        <div class="text-xs font-semibold text-emerald-700">{{ optional($a->trimestre)->libelle ?? 'Trimestre' }}</div>
                        <p class="text-sm text-gray-700 mt-1 whitespace-pre-line">{{ $a->contenu }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Journal / Remarques --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b bg-gray-50 text-gray-700 font-semibold text-sm">Journal de suivi</div>
        @if ($remarques->isEmpty())
            <div class="p-5 text-sm text-gray-500">Aucune remarque.</div>
        @else
            <ul class="divide-y divide-gray-100">
                @foreach ($remarques as $r)
                    <li class="px-5 py-3 flex items-start gap-3">
                        <span class="shrink-0 px-2 py-0.5 rounded text-xs font-medium bg-{{ $r->categorie_color }}-100 text-{{ $r->categorie_color }}-700">
                            {{ $r->categorie_label }}
                        </span>
                        <div>
                            <div class="text-xs text-gray-400">{{ $r->date->format('d/m/Y') }}</div>
                            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $r->contenu }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
