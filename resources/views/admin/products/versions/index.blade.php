@extends('admin.layouts.admin')

@section('title', "Versions du produit: {$product->name}")

@section('content')
    <div class="space-y-8 max-w-6xl mx-auto">
        @if(session('success'))
            <div class="p-4 bg-green-100 text-green-800 rounded-lg border border-green-300 shadow">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold">Versions du produit : {{ $product->name }}</h1>
            <a href="{{ route('admin.shop.products.versions.create', $product) }}"
               class="inline-flex items-center gap-2 rounded-md text-sm font-medium bg-primary text-white hover:bg-primary/90 px-4 py-2">
                <i class="fas fa-plus"></i> Nouvelle version
            </a>
        </div>

        @if($product->versions->isEmpty())
            <div class="text-muted-foreground text-center py-12">Aucune version disponible pour ce produit.</div>
        @else
            <div class="bg-card border rounded-2xl shadow p-6 text-card-foreground">
                <h2 class="text-lg font-semibold mb-4">Liste des versions</h2>
                <div class="overflow-x-auto rounded-lg">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead>
                        <tr class="text-xs uppercase text-muted-foreground border-b">
                            <th class="py-2 px-3">Version</th>
                            <th class="py-2 px-3">Changelog</th>
                            <th class="py-2 px-3">Hash</th>
                            <th class="py-2 px-3">Téléchargement</th>
                            <th class="py-2 px-3">TTL</th>
                            <th class="py-2 px-3 text-right">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($product->versions as $version)
                            <tr class="border-b hover:bg-muted/50 transition">
                                <td class="py-2 px-3 font-medium">{{ $version->version }}</td>
                                <td class="py-2 px-3">{{ $version->changelog ?? '—' }}</td>
                                <td class="py-2 px-3 font-mono text-xs">{{ Str::limit($version->file_hash, 15) }}</td>
                                <td class="py-2 px-3">
                                    <a href="{{ route('admin.shop.products.versions.download', [$product, $version]) }}"
                                       class="text-primary hover:underline text-xs">Télécharger</a>
                                </td>
                                <td class="py-2 px-3">{{ $version->ttl ? $version->ttl . 'j' : 'Illimité' }}</td>
                                <td class="py-2 px-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.shop.products.versions.edit', [$product, $version]) }}"
                                           class="text-blue-600 hover:underline text-xs">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <form method="POST" action="{{ route('admin.shop.products.versions.destroy', [$product, $version]) }}" class="inline-block"
                                              onsubmit="return confirm('Confirmer la suppression ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-destructive hover:underline text-xs">
                                                <i class="fas fa-trash-alt"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('admin.shop.products.index') }}"
               class="inline-flex items-center gap-2 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4">
                <i class="fas fa-arrow-left"></i> Retour aux produits
            </a>
        </div>
    </div>
@endsection
