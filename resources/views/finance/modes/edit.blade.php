<x-finance-layout>
    <x-page-header title="Modifier le mode de paiement" />
    @include('finance.modes._form', [
        'action' => route('finance.modes.update', $mode),
        'method' => 'PUT',
        'cancelUrl' => route('finance.modes.index'),
    ])
</x-finance-layout>
