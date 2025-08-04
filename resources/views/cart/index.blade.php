@extends('layouts.app')

@section('title', 'Mon Panier')

@section('content')
    <div x-data="cart()" class="max-w-4xl mx-auto px-4 py-8 space-y-6">
        <h1 class="text-2xl font-bold">Mon panier</h1>

        <template x-if="items.length === 0">
            <div class="text-muted-foreground text-sm text-center py-12">
                Votre panier est vide.
            </div>
        </template>

        <template x-if="items.length > 0">
            <div class="space-y-6">
                <div class="space-y-4 divide-y">
                    <template x-for="(item, index) in items" :key="item.id">
                        <div class="py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <p class="font-medium" x-text="item.product"></p>
                                <p class="text-sm text-muted-foreground">Prix unitaire :
                                    <span x-text="format(item.price)"></span>
                                </p>
                            </div>

                            <div class="flex items-center gap-2">
                                <input type="number" min="1"
                                       x-model="item.quantity"
                                       class="w-20 h-9 rounded-md border border-input bg-background px-2 py-1 text-sm"
                                >
                                <button @click="updateItem(item)"
                                        class="h-9 px-3 rounded-md bg-primary text-white hover:bg-primary/90 text-sm">
                                    Mettre à jour
                                </button>
                                <button @click="removeItem(item.id)"
                                        class="h-9 px-3 rounded-md border border-destructive text-destructive text-sm hover:bg-destructive/10">
                                    Retirer
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="border-t pt-4 space-y-2 text-sm sm:text-base">
                    <div class="flex justify-between">
                        <span>Sous-total</span>
                        <span x-text="format(subtotal)"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>TVA</span>
                        <span x-text="format(tax)"></span>
                    </div>
                    <div class="flex justify-between font-semibold text-lg">
                        <span>Total TTC</span>
                        <span x-text="format(total)"></span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 justify-end pt-2">
                    <button @click="clearCart()"
                            class="inline-flex items-center justify-center rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 text-sm">
                        Vider le panier
                    </button>
                    <a href="{{ route('checkout.index') }}"
                       class="inline-flex items-center justify-center rounded-md bg-primary text-white hover:bg-primary/90 h-9 px-4 text-sm">
                        Passer la commande
                    </a>
                </div>
            </div>
        </template>
    </div>
@endsection

@push('scripts')
    <script>
        function cart() {
            return {
                items: @json($items),
                subtotal: @json($subtotal),
                tax: @json($tax),
                total: @json($total),

                updateItem(item) {
                    fetch(`{{ url('cart/item') }}/${item.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ quantity: item.quantity })
                    }).then(() => location.reload());
                },

                removeItem(id) {
                    fetch(`{{ url('cart/item') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                    }).then(() => location.reload());
                },

                clearCart() {
                    fetch(`{{ route('cart.clear') }}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                    }).then(() => location.reload());
                },

                format(amount) {
                    return new Intl.NumberFormat('fr-FR', {
                        style: 'currency',
                        currency: 'EUR'
                    }).format(amount);
                },
            };
        }
    </script>
@endpush
