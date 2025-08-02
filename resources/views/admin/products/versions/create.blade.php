@extends('admin.layouts.admin')

@section('title', 'Ajouter une version')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">

        <div class="inline-flex items-center gap-2">
            <a href="{{ route('admin.shop.products.versions.index', $product) }}"
               class="border border-input bg-background hover:bg-accent hover:text-accent-foreground flex items-center gap-2 h-9 rounded-md px-3">
                <i class="fas fa-arrow-left"></i> Retour aux versions
            </a>
        </div>

        <form action="{{ route('admin.shop.products.versions.store', $product) }}"
              method="POST" enctype="multipart/form-data">
            @csrf

            <div class="rounded-lg border bg-card text-card-foreground shadow-sm space-y-6 p-6">
                <h2 class="text-xl font-semibold">
                    Nouvelle version pour :
                    <span class="text-primary">{{ $product->name }}</span>
                </h2>

                <div>
                    <label class="label">Version <span class="text-destructive">*</span></label>
                    <input type="text" name="version" value="{{ old('version') }}"
                           class="w-full h-10 px-3 rounded-md border border-border bg-background" required>
                    @error('version')<p class="text-destructive text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label">Fichier <span class="text-destructive">*</span></label>
                    <input type="file" name="file"
                           class="w-full h-10 px-3 rounded-md border border-border bg-background" required>
                    @error('file')<p class="text-destructive text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label">Changelog</label>
                    <textarea name="changelog" rows="4"
                              class="w-full rounded-md border border-border bg-background px-3 py-2 text-sm"
                              placeholder="Résumé des changements">{{ old('changelog') }}</textarea>
                    @error('changelog')<p class="text-destructive text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label">Durée de validité (TTL en jours)</label>
                    <input type="number" name="ttl" value="{{ old('ttl') }}"
                           class="w-full h-10 px-3 rounded-md border border-border bg-background"
                           placeholder="Optionnel">
                    @error('ttl')<p class="text-destructive text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <a href="{{ route('admin.shop.products.versions.index', $product) }}"
                       class="inline-flex items-center gap-2 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4">
                        Annuler
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-md text-white bg-primary hover:bg-primary/90 h-10 px-4">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
