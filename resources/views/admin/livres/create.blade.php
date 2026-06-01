<x-admin-layout>
    <x-page-header title="Nouveau livre" />
    @include('admin.livres._form', ['action' => route('admin.livres.store'), 'method' => 'POST'])
</x-admin-layout>
