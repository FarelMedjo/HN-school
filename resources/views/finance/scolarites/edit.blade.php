<x-finance-layout>
    <x-page-header title="Modifier les frais de scolarité" />
    @include('admin.scolarites._form', [
        'action' => route('finance.scolarites.update', $scolarite),
        'method' => 'PUT',
        'cancelUrl' => route('finance.scolarites.index'),
    ])
</x-finance-layout>
