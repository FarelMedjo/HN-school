<x-finance-layout>
    <x-page-header title="Nouveau mode de paiement" />
    @include('finance.modes._form', [
        'action' => route('finance.modes.store'),
        'method' => 'POST',
        'cancelUrl' => route('finance.modes.index'),
    ])
</x-finance-layout>
