<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HN-School') }} - Enseignant</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
<div class="min-h-screen flex">

    {{-- Sidebar --}}
    @php
        $pers = auth()->user()->personne;
        $myCours = $pers ? $pers->coursEnseignes()->with('classe')->get() : collect();
        $navItems = [
            ['Tableau de bord', 'teacher.dashboard', null],
        ];
    @endphp
    <aside class="w-64 bg-indigo-950 text-indigo-50 flex flex-col">
        <div class="px-6 py-5 border-b border-indigo-900">
            <a href="{{ route('teacher.dashboard') }}" class="text-xl font-bold tracking-tight text-white">HN-School</a>
            <div class="text-xs text-indigo-300 mt-1">Espace Enseignant</div>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1 text-sm overflow-y-auto">
            {{-- Dashboard --}}
            @php
                $active = request()->routeIs('teacher.dashboard');
                $coursParam = optional(request()->route('cours'))->idCours;
            @endphp
            <a href="{{ route('teacher.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-md transition
                      {{ $active ? 'bg-indigo-900 text-white' : 'text-indigo-100 hover:bg-indigo-900 hover:text-white' }}">
                <span class="w-2 h-2 rounded-full {{ $active ? 'bg-indigo-400' : 'bg-indigo-700' }}"></span>
                Tableau de bord
            </a>

            {{-- Mes cours --}}
            @if ($myCours->isNotEmpty())
                <div class="pt-3 pb-1 px-3 text-xs font-semibold uppercase tracking-wider text-indigo-500">
                    Mes cours
                </div>
                @foreach ($myCours as $c)
                    @php
                        $coursActive = request()->routeIs('teacher.cours.*') &&
                                       $coursParam == $c->idCours;
                    @endphp
                    <div>
                        <div class="px-3 py-2 text-sm font-medium {{ $coursActive ? 'text-white' : 'text-indigo-100' }}">
                            {{ $c->libelle }}
                            <span class="block text-xs text-indigo-300">{{ optional($c->classe)->libelle ?? '—' }}</span>
                        </div>
                        <div class="ml-5 space-y-0.5">
                            @foreach ([
                                ['Élèves', 'teacher.cours.eleves'],
                                ['Présences', 'teacher.cours.presences'],
                                ['Notes', 'teacher.cours.notes'],
                            ] as [$label, $routeName])
                                @php $sub = request()->routeIs($routeName) && $coursParam == $c->idCours; @endphp
                                <a href="{{ route($routeName, $c) }}"
                                   class="flex items-center gap-2 px-3 py-1.5 rounded text-xs transition
                                          {{ $sub ? 'bg-indigo-800 text-white' : 'text-indigo-200 hover:bg-indigo-900 hover:text-white' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $sub ? 'bg-indigo-400' : 'bg-indigo-700' }}"></span>
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="px-3 py-2 text-xs text-indigo-400 italic">
                    Aucun cours assigné.
                </div>
            @endif

            <div class="pt-3 pb-1 px-3 text-xs font-semibold uppercase tracking-wider text-indigo-500">Vie scolaire</div>
            @php $vieActive = request()->routeIs('teacher.vie-scolaire') || request()->routeIs('teacher.convocations.*'); @endphp
            <a href="{{ route('teacher.vie-scolaire') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-md transition
                      {{ $vieActive ? 'bg-indigo-900 text-white' : 'text-indigo-100 hover:bg-indigo-900 hover:text-white' }}">
                <span class="w-2 h-2 rounded-full {{ $vieActive ? 'bg-indigo-400' : 'bg-indigo-700' }}"></span>
                <span>Convocations & appréciations</span>
            </a>
        </nav>

        <div class="px-3 py-4 border-t border-indigo-900">
            @if ($pers)
                <div class="px-3 py-2 text-xs text-indigo-300">
                    {{ $pers->nom }} {{ $pers->prenom }}
                </div>
            @endif
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-indigo-100 hover:text-white">Mon profil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-3 py-2 text-sm text-rose-300 hover:text-rose-200">
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col min-h-screen">
        <header class="bg-white border-b shadow-sm">
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    @isset($header)
                        {{ $header }}
                    @else
                        <h1 class="text-lg font-semibold text-gray-800">Espace Enseignant</h1>
                    @endisset
                </div>
                <div class="text-sm text-gray-600">
                    <span class="font-medium">{{ auth()->user()->name }}</span>
                    <span class="text-gray-400 mx-2">·</span>
                    <span class="px-2 py-0.5 bg-indigo-100 text-indigo-800 rounded">Enseignant</span>
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

        <main class="flex-1 p-6">
            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>
