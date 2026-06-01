<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HN-School') }} - Espace Parent</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
<div class="min-h-screen flex">

    @php
        $pers    = auth()->user()->personne;
        $enfants = collect();
        if ($pers) {
            $anneeActive = \App\Models\AnneeAcademique::where('actif', true)->first();
            $enfants = \App\Models\ParentEleve::where('idPers', $pers->idPers)
                ->with(['eleve'])
                ->get()->pluck('eleve')->filter();
        }
    @endphp

    @php
        $nbNonLus = $pers ? \App\Models\Message::where('idParent', $pers->idPers)->where('valider', false)->count() : 0;
    @endphp
    <aside class="w-64 bg-teal-950 text-teal-50 flex flex-col">
        <div class="px-6 py-5 border-b border-teal-900">
            <a href="{{ route('parent.dashboard') }}" class="text-xl font-bold tracking-tight text-white">HN-School</a>
            <div class="text-xs text-teal-300 mt-1">Espace Parent</div>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1 text-sm overflow-y-auto">
            {{-- Dashboard --}}
            @php $active = request()->routeIs('parent.dashboard'); @endphp
            <a href="{{ route('parent.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-md transition
                      {{ $active ? 'bg-teal-900 text-white' : 'text-teal-100 hover:bg-teal-900 hover:text-white' }}">
                <span class="w-2 h-2 rounded-full {{ $active ? 'bg-teal-400' : 'bg-teal-700' }}"></span>
                Tableau de bord
            </a>

            {{-- Messages --}}
            @php $msgActive = request()->routeIs('parent.messages*'); @endphp
            <a href="{{ route('parent.messages') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-md transition
                      {{ $msgActive ? 'bg-teal-900 text-white' : 'text-teal-100 hover:bg-teal-900 hover:text-white' }}">
                <span class="w-2 h-2 rounded-full {{ $msgActive ? 'bg-teal-400' : 'bg-teal-700' }}"></span>
                <span class="flex-1">Messages</span>
                @if ($nbNonLus > 0)
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-teal-400 text-teal-950 text-xs font-bold">
                        {{ $nbNonLus }}
                    </span>
                @endif
            </a>

            {{-- Mes enfants --}}
            @if ($enfants->isNotEmpty())
                <div class="pt-3 pb-1 px-3 text-xs font-semibold uppercase tracking-wider text-teal-500">
                    Mes enfants
                </div>
                @foreach ($enfants as $e)
                    @php $enfantActif = optional(request()->route('eleve'))->matricule == $e->matricule; @endphp
                    <div>
                        <div class="px-3 py-2 text-sm font-medium {{ $enfantActif ? 'text-white' : 'text-teal-100' }}">
                            {{ $e->prenom }} {{ $e->nom }}
                        </div>
                        <div class="ml-5 space-y-0.5">
                            @foreach ([
                                ['Profil',    'parent.enfant.show'],
                                ['Notes',     'parent.enfant.notes'],
                                ['Absences',  'parent.enfant.presences'],
                                ['Paiements', 'parent.enfant.paiements'],
                                ['Emprunts',  'parent.enfant.emprunts'],
                            ] as [$label, $routeName])
                                @php $sub = request()->routeIs($routeName) && optional(request()->route('eleve'))->matricule == $e->matricule; @endphp
                                <a href="{{ route($routeName, $e) }}"
                                   class="flex items-center gap-2 px-3 py-1.5 rounded text-xs transition
                                          {{ $sub ? 'bg-teal-800 text-white' : 'text-teal-200 hover:bg-teal-900 hover:text-white' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $sub ? 'bg-teal-400' : 'bg-teal-700' }}"></span>
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="px-3 py-2 text-xs text-teal-400 italic">Aucun enfant enregistré.</div>
            @endif
        </nav>

        <div class="px-3 py-4 border-t border-teal-900">
            @if ($pers)
                <div class="px-3 py-2 text-xs text-teal-300">
                    {{ $pers->nom }} {{ $pers->prenom }}
                </div>
            @endif
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-teal-100 hover:text-white">Mon profil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-3 py-2 text-sm text-rose-300 hover:text-rose-200">Déconnexion</button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-h-screen">
        <header class="bg-white border-b shadow-sm">
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    @isset($header)
                        {{ $header }}
                    @else
                        <h1 class="text-lg font-semibold text-gray-800">Espace Parent</h1>
                    @endisset
                </div>
                <div class="text-sm text-gray-600">
                    <span class="font-medium">{{ auth()->user()->name }}</span>
                    <span class="text-gray-400 mx-2">·</span>
                    <span class="px-2 py-0.5 bg-teal-100 text-teal-800 rounded">Parent</span>
                </div>
            </div>
        </header>

        @if (session('success'))
            <div class="mx-6 mt-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mx-6 mt-4 px-4 py-3 bg-rose-50 border border-rose-200 text-rose-800 rounded">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <main class="flex-1 p-6">{{ $slot }}</main>
    </div>
</div>
</body>
</html>
