<x-finance-layout>
    <x-page-header title="Nouveaux frais de scolarité" />
    @include('admin.scolarites._form', [
        'action' => route('finance.scolarites.store'),
        'method' => 'POST',
        'cancelUrl' => route('finance.scolarites.index'),
    ])
</x-finance-layout>
