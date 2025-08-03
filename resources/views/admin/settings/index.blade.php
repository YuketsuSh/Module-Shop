@extends('admin.layouts.admin')

@section('title', 'Paramètres de la boutique')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6"
         x-data="{
            tab: 'general',
            stripeEnabled: {{ $stripe['enabled'] ?? false ? 'true' : 'false' }},
            paypalEnabled: {{ $paypal['enabled'] ?? false ? 'true' : 'false' }},
            taxEnabled: {{ $general['tax_enabled'] ?? false ? 'true' : 'false' }},
            shippingEnabled: {{ $general['shipping_enabled'] ?? false ? 'true' : 'false' }}
         }">

        @if (session('success'))
            <div class="p-4 bg-green-100 text-green-800 rounded-lg border border-green-300 shadow">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-red-100 text-red-800 rounded-lg border border-red-300 shadow">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle mr-1"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.shop.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <div class="flex flex-wrap gap-2 border-b pb-2">
                <template x-for="tabInfo in [
                    { key: 'general', label: 'Général', icon: 'fas fa-cog' },
                    { key: 'payment', label: 'Paiement', icon: 'fas fa-credit-card' },
                    { key: 'taxes', label: 'Taxes', icon: 'fas fa-percent' },
                    { key: 'shipping', label: 'Livraison', icon: 'fas fa-truck' }
                ]">
                    <button type="button"
                            @click="tab = tabInfo.key"
                            :class="{ 'bg-primary text-white': tab === tabInfo.key, 'bg-muted text-muted-foreground hover:bg-muted/80': tab !== tabInfo.key }"
                            class="flex items-center space-x-2 px-4 py-2 rounded-md text-sm font-medium transition">
                        <i :class="tabInfo.icon"></i>
                        <span x-text="tabInfo.label"></span>
                    </button>
                </template>
            </div>

            {{-- Général --}}
            <div x-show="tab === 'general'" class="space-y-4">
                <x-setting.title icon="fas fa-cog" label="Paramètres généraux" />
                <x-setting.input name="shop[general][name]" label="Nom de la boutique" :value="$general['name'] ?? ''" />
                <x-setting.input name="shop[general][email]" label="Email" :value="$general['email'] ?? ''" />
                <x-setting.input name="shop[general][phone]" label="Téléphone" :value="$general['phone'] ?? ''" />
                <x-setting.textarea name="shop[general][address]" label="Adresse" :value="$general['address'] ?? ''" />
                <x-setting.input name="shop[general][currency]" label="Devise" :value="$general['currency'] ?? 'EUR'" />
            </div>

            {{-- Paiement --}}
            <div x-show="tab === 'payment'" x-cloak class="space-y-6">
                <x-setting.title icon="fas fa-credit-card" label="Méthodes de paiement" />

                <input type="hidden" name="payment[stripe][enabled]" value="0">
                <div @change="stripeEnabled = $event.target.checked">
                    <x-setting.toggle name="payment[stripe][enabled]" label="Stripe"
                                      :checked="$stripe['enabled'] ?? false" />
                </div>

                <div x-show="stripeEnabled" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-setting.input name="payment[stripe][api_key]" label="Clé publique Stripe" :value="$stripe['api_key'] ?? ''" />
                    <x-setting.input name="payment[stripe][secret_key]" label="Clé secrète Stripe" :value="$stripe['secret_key'] ?? ''" />
                </div>

                <input type="hidden" name="payment[paypal][enabled]" value="0">
                <div @change="paypalEnabled = $event.target.checked">
                    <x-setting.toggle name="payment[paypal][enabled]" label="PayPal"
                                      :checked="$paypal['enabled'] ?? false" />
                </div>

                <div x-show="paypalEnabled" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-setting.input name="payment[paypal][client_id]" label="Client ID PayPal" :value="$paypal['client_id'] ?? ''" />
                    <x-setting.input name="payment[paypal][secret]" label="Secret PayPal" :value="$paypal['secret'] ?? ''" />
                </div>
            </div>

            {{-- Taxes --}}
            <div x-show="tab === 'taxes'" x-cloak class="space-y-6">
                <x-setting.title icon="fas fa-percent" label="Taxes" />
                <input type="hidden" name="shop[general][tax_enabled]" value="0">
                <div @change="taxEnabled = $event.target.checked">
                    <x-setting.toggle name="shop[general][tax_enabled]" label="Activer la TVA"
                                      :checked="$general['tax_enabled'] ?? false" />
                </div>
                <div x-show="taxEnabled" x-cloak>
                    <x-setting.input name="shop[general][tax_rate]" label="Taux de TVA (%)" type="number" step="0.1" :value="$general['tax_rate'] ?? 20" />
                </div>
            </div>

            {{-- Livraison --}}
            <div x-show="tab === 'shipping'" x-cloak class="space-y-6">
                <x-setting.title icon="fas fa-truck" label="Livraison" />
                <input type="hidden" name="shop[general][shipping_enabled]" value="0">
                <div @change="shippingEnabled = $event.target.checked">
                    <x-setting.toggle name="shop[general][shipping_enabled]" label="Activer la livraison"
                                      :checked="$general['shipping_enabled'] ?? false" />
                </div>
                <div x-show="shippingEnabled" x-cloak>
                    <x-setting.input name="shop[general][shipping_flat_rate]" label="Frais de livraison (€)" type="number" step="0.01" :value="$general['shipping_flat_rate'] ?? 4.99" />
                </div>
            </div>

            <div class="text-right pt-6">
                <button type="submit" class="px-6 py-2 bg-primary text-white rounded hover:bg-primary/90 transition shadow">
                    <i class="fas fa-save mr-2"></i> Enregistrer les paramètres
                </button>
            </div>
        </form>
    </div>
@endsection
