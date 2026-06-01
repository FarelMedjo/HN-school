<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HN-School') }} - Finance</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
<div class="min-h-screen flex">

    {{-- Sidebar --}}
    <aside class="w-64 bg-emerald-950 text-emerald-50 flex flex-col">
        <div class="px-6 py-5 border-b border-emerald-900">
            <a href="{{ route('finance.dashboard') }}" class="text-xl font-bold tracking-tight text-white">HN-School</a>
            <div class="text-xs text-emerald-300 mt-1">Espace Finance</div>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1 text-sm overflow-y-auto">
            @php
                $groups = [
                    '' => [
                        ['Tableau de bord', 'finance.dashboard'],
                    ],
                    'Encaissements' => [
                        ['Paiements', 'finance.paiements.index'],
                    ],
                    'Grille tarifaire' => [
                        ['Scolarités (frais)', 'finance.scolarites.index'],
                        ['Modes de paiement', 'finance.modes.index'],
                    ],
                    'Suivi' => [
                        ['Soldes élèves', 'finance.soldes.index'],
                    ],
                    'Rapports' => [
                        ['Synthèse & export', 'finance.rapports.index'],
                    ],
                ];
            @endphp
            @foreach ($groups as $section => $items)
                @if ($section !== '')
                    <div class="pt-3 pb-1 px-3 text-xs font-semibold uppercase tracking-wider text-emerald-500">{{ $section }}</div>
                @endif
                @foreach ($items as [$label, $route])
                    @php $active = request()->routeIs($route) || request()->routeIs(str_replace('.index', '.*', $route)); @endphp
                    <a href="{{ Route::has($route) ? route($route) : '#' }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-md transition
                              {{ $active ? 'bg-emerald-900 text-white' : 'text-emerald-100 hover:bg-emerald-900 hover:text-white' }}">
                        <span class="w-2 h-2 rounded-full {{ $active ? 'bg-emerald-400' : 'bg-emerald-700' }}"></span>
                        <span>{{ $label }}</span>
                    </a>
                @endforeach
            @endforeach
        </nav>

        <div class="px-3 py-4 border-t border-emerald-900">
            <div class="px-3 py-2 text-xs text-emerald-300">{{ auth()->user()->name }}</div>
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-emerald-100 hover:text-white">Mon profil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-3 py-2 text-sm text-rose-300 hover:text-rose-200">Déconnexion</button>
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
                        <h1 class="text-lg font-semibold text-gray-800">Finance</h1>
                    @endisset
                </div>
                <div class="text-sm text-gray-600">
                    <span class="font-medium">{{ auth()->user()->name }}</span>
                    <span class="text-gray-400 mx-2">·</span>
                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded">Finance</span>
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
