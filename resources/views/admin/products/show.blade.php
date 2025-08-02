@extends('admin.layouts.admin')

@section('title', 'Détail produit')

@section('content')
    <div class="space-y-6 max-w-4xl mx-auto">
        <a href="{{ route('admin.shop.products.index') }}" class="text-sm text-muted-foreground hover:underline">
            ← Retour à la liste
        </a>

        <div class="rounded-lg border bg-card p-6 shadow-sm space-y-4">
            <h1 class="text-2xl font-bold">{{ $product->name }}</h1>

            <p class="text-muted-foreground">{{ $product->description ?: '—' }}</p>

            <ul class="text-sm grid grid-cols-1 sm:grid-cols-2 gap-2 pt-4">
                <li><strong>Prix :</strong> {{ number_format($product->price, 2) }} {{ $product->currency }}</li>
                <li><strong>Stock :</strong> {{ $product->stock ?? '—' }}</li>
                <li><strong>Type :</strong> {{ ucfirst($product->type) }}</li>
                <li><strong>Catégorie :</strong> {{ $product->category->name ?? '—' }}</li>
                <li><strong>Tags :</strong>
                    @forelse($product->tags as $tag)
                        <span class="inline-block px-2 py-0.5 bg-muted text-muted-foreground rounded text-xs">{{ $tag->name }}</span>
                    @empty
                        —
                    @endforelse
                </li>
            </ul>
        </div>
    </div>
@endsection
