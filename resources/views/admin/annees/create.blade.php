<x-admin-layout>
    <x-page-header title="Nouvelle année académique" />
    @include('admin.annees._form', ['action' => route('admin.annees.store'), 'method' => 'POST'])
</x-admin-layout>
