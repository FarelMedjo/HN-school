@props(['title', 'subtitle' => null, 'createRoute' => null, 'createLabel' => 'Nouveau'])

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
        @if ($subtitle)
            <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    @if ($createRoute)
        <a href="{{ $createRoute }}"
           class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-md hover:bg-emerald-700 transition">
            + {{ $createLabel }}
        </a>
    @endif
</div>
