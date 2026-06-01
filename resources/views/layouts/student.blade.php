<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HN-School') }} - Élève</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
<div class="min-h-screen flex">

    {{-- Sidebar --}}
    @php
        $eleve = auth()->user()->eleve;
        $navItems = [
            ['Tableau de bord',   'student.dashboard',      'dashboard'],
            ['Mes notes',         'student.notes',          'notes'],
            ['Mes absences',      'student.absences',       'absences'],
            ['Emploi du temps',   'student.emploi-du-temps','edt'],
            ['Mon bulletin',      'student.bulletin',       'bulletin'],
            ['Mes emprunts',      'student.emprunts',       'emprunts'],
            ['Vie scolaire',      'student.vie-scolaire',   'vie'],
        ];
    @endphp
    <aside class="w-64 bg-purple-950 text-purple-50 flex flex-col">
        <div class="px-6 py-5 border-b border-purple-900">
            <a href="{{ route('student.dashboard') }}" class="text-xl font-bold tracking-tight text-white">HN-School</a>
            <div class="text-xs text-purple-300 mt-1">Espace Élève</div>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
            @foreach ($navItems as [$label, $route, $key])
                @php $active = request()->routeIs($route); @endphp
                <a href="{{ Route::has($route) ? route($route) : '#' }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-md transition
                          {{ $active ? 'bg-purple-900 text-white' : 'text-purple-100 hover:bg-purple-900 hover:text-white' }}">
                    <span class="w-2 h-2 rounded-full {{ $active ? 'bg-purple-400' : 'bg-purple-700' }}"></span>
                    <span>{{ $label }}</span>
                </a>
            @endforeach
        </nav>

        <div class="px-3 py-4 border-t border-purple-900">
            @if ($eleve)
                <div class="px-3 py-2 text-xs text-purple-300">
                    {{ $eleve->prenom }} {{ $eleve->nom }}
                </div>
            @endif
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-purple-100 hover:text-white">Mon profil</a>
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
                        <h1 class="text-lg font-semibold text-gray-800">Espace Élève</h1>
                    @endisset
                </div>
                <div class="text-sm text-gray-600">
                    <span class="font-medium">{{ auth()->user()->name }}</span>
                    <span class="text-gray-400 mx-2">·</span>
                    <span class="px-2 py-0.5 bg-purple-100 text-purple-800 rounded">Élève</span>
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
