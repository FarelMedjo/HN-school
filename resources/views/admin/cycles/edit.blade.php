<x-admin-layout>
    <x-page-header title="Modifier cycle" />
    @include('admin.cycles._form', ['action' => route('admin.cycles.update', $cycle), 'method' => 'PUT'])
</x-admin-layout>
