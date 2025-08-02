@extends('admin.layouts.admin')

@section('title', $editing ? 'Modifier le produit' : 'Créer le produit')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6" x-data="slugGenerator()" x-init="init()">

        <div class="inline-flex items-center gap-2">
            <a href="{{ route('admin.shop.products.index') }}" class="border border-input bg-background hover:bg-accent hover:text-accent-foreground flex items-center gap-2 h-9 rounded-md px-3">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <form method="POST" action="{{ $editing ? route('admin.shop.products.update', $product) : route('admin.shop.products.store') }}" enctype="multipart/form-data">
            @csrf
            @if($editing) @method('PUT') @endif

            <div class="space-y-6">
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="flex flex-col space-y-1.5 p-6">
                        <h2 class="text-2xl font-semibold leading-none tracking-tight">Informations du produit</h2>
                    </div>
                    <div class="p-6 pt-0 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="label">Nom</label>
                                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full h-10 px-3 rounded-md border border-border bg-background" required>
                                @error('name')<p class="text-destructive text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="label">Slug</label>
                                <input type="text" name="slug" id="slug" value="{{ old('slug', $product->slug) }}" class="w-full h-10 px-3 rounded-md border border-border bg-background" required>
                                @error('slug')<p class="text-destructive text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="label">Type</label>
                                <select name="type" x-model="productType" class="w-full h-10 px-3 rounded-md border border-border bg-background" required>
                                    <option value="digital">Numérique</option>
                                    <option value="physical">Physique</option>
                                </select>
                                @error('type')<p class="text-destructive text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="label">Prix</label>
                                <input type="number" name="price" step="0.01" value="{{ old('price', $product->price) }}" class="w-full h-10 px-3 rounded-md border border-border bg-background" required>
                                @error('price')<p class="text-destructive text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <template x-if="productType === 'digital'">
                            <div class="space-y-4">
                                <div>
                                    <label class="label">Fichier téléchargeable (ZIP, PDF...)</label>
                                    <input type="file" name="file" class="w-full h-10 px-3 rounded-md border border-border bg-background">
                                    @error('file')<p class="text-destructive text-sm mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="label">Notes de version (changelog)</label>
                                    <input type="text" name="changelog" class="w-full h-10 px-3 rounded-md border border-border bg-background" placeholder="Ex: Ajout de contenu, correction de bugs...">
                                    @error('changelog')<p class="text-destructive text-sm mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </template>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="label">Stock</label>
                                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="w-full h-10 px-3 rounded-md border border-border bg-background" min="0">
                                @error('stock')<p class="text-destructive text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="label">Devise</label>
                                <select name="currency" class="w-full h-10 px-3 rounded-md border border-border bg-background" required>
                                    @foreach($currencies as $currencyCode => $currencyName)
                                        <option value="{{ $currencyCode }}" @selected(old('currency', $product->currency) === $currencyCode)> {{ $currencyName }} ({{ $currencyCode }})</option>
                                    @endforeach
                                </select>
                                @error('currency')<p class="text-destructive text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label class="label">Catégorie</label>
                            @if($categories->count())
                                <select name="category_id" class="w-full h-10 px-3 rounded-md border border-border bg-background">
                                    <option value="">—</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <div class="text-muted-foreground text-sm">Aucune catégorie créée</div>
                            @endif
                            @error('category_id')<p class="text-destructive text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="label">Tags</label>
                            @if($tags->count())
                                <select name="tags[]" multiple class="w-full h-32 px-3 py-2 rounded-md border border-border bg-background">
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}" @selected(in_array($tag->id, old('tags', $product->tags->pluck('id')->toArray() ?? [])))>{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <div class="text-muted-foreground text-sm">Aucun tag créé</div>
                            @endif
                            @error('tags')<p class="text-destructive text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured)) class="rounded border-input text-primary shadow-sm focus:ring focus:ring-primary">
                            <label for="is_featured" class="text-sm font-medium text-foreground">Produit vedette ?</label>
                        </div>

                        <div>
                            <label class="label">Description</label>
                            <textarea name="description" id="tinymce" class="w-full min-h-[200px] rounded-md border border-border bg-background">{{ old('description', $product->description) }}</textarea>
                            @error('description')<p class="text-destructive text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.shop.products.index') }}" class="inline-flex items-center gap-2 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4">Annuler</a>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-md text-white bg-primary hover:bg-primary/90 h-10 px-4">
                        <i class="fas fa-save"></i> {{ $editing ? 'Mettre à jour' : 'Créer' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: 'textarea#tinymce',
            height: 300,
            menubar: false,
            plugins: 'link lists code table',
            toolbar: 'undo redo | formatselect | bold italic underline | bullist numlist | link table | code',
            branding: false,

            base_url: '/vendor/tinymce',
            suffix: '.min',

            skin: 'oxide-dark',
            content_css: 'dark',

            license_key: 'no-license'
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('slugGenerator', () => ({
                slug: '',
                productType: '{{ old('type', $product->type ?? 'digital') }}',
                init() {
                    const name = document.querySelector('input[name="name"]');
                    const slug = document.querySelector('input[name="slug"]');
                    name?.addEventListener('input', () => {
                        if (!slug.value || slug.value === '' || slug.value === this.slug) {
                            this.slug = name.value.toLowerCase()
                                .normalize('NFD').replace(/[̀-ͯ]/g, '')
                                .replace(/[^a-z0-9\s-]/g, '')
                                .trim().replace(/\s+/g,'-').replace(/-+/g,'-');
                            slug.value = this.slug;
                        }
                    });
                }
            }));
        });
    </script>
@endpush
