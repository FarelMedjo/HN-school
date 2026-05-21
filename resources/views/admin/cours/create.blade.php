<x-admin-layout>
    <x-page-header title="Nouveau cours" />
    @include('admin.cours._form', ['action' => route('admin.cours.store'), 'method' => 'POST'])
</x-admin-layout>
