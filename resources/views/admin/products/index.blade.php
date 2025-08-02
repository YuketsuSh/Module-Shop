@extends('admin.layouts.admin')
@section('title', 'Produits')

@section('content')
    <div class="space-y-6">

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Produits</h1>
            <a href="{{ route('admin.shop.products.create') }}" class="inline-flex items-center gap-2 rounded-md text-sm font-medium bg-primary text-white hover:bg-primary/90 px-4 py-2">
                <i class="fas fa-plus"></i> Ajouter un produit
            </a>
        </div>

        @if (session('success'))
            <div class="p-4 bg-emerald-100 text-emerald-800 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm overflow-auto">
            <table class="w-full table-auto text-sm divide-y divide-muted">
                <thead class="bg-muted/10 text-muted-foreground">
                <tr>
                    <th class="px-4 py-2 text-left">Nom</th>
                    <th class="px-4 py-2 text-left">Type</th>
                    <th class="px-4 py-2 text-left">Prix</th>
                    <th class="px-4 py-2 text-left">Stock</th>
                    <th class="px-4 py-2 text-left">Catégorie</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    <tr class="hover:bg-muted/20">
                        <td class="px-4 py-2 font-medium">{{ $product->name }}</td>
                        <td class="px-4 py-2 capitalize">{{ $product->type }}</td>
                        <td class="px-4 py-2">{{ number_format($product->price, 2) }} {{ $product->currency }}</td>
                        <td class="px-4 py-2">{{ $product->stock ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $product->category->name ?? '—' }}</td>
                        <td class="px-4 py-2">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.shop.products.edit', $product) }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium h-9 px-3 border bg-background hover:bg-accent hover:text-accent-foreground">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.shop.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Supprimer ce produit ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium h-9 px-3 border bg-background hover:bg-destructive/10 hover:text-destructive">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-muted-foreground">Aucun produit trouvé</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4">
            {{ $products->links() }}
        </div>
    </div>
@endsection
