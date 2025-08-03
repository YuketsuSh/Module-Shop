@extends('admin.layouts.admin')

@section('title', 'Créer une catégorie')

@section('content')
    <div class="max-w-2xl mx-auto" x-data="categoryForm()" x-init="init()">
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6">
                <h3 class="text-2xl font-semibold leading-none tracking-tight">Nouvelle catégorie</h3>
            </div>

            <div class="p-6 pt-0">
                <form method="POST" action="{{ route('admin.shop.categories.store') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label for="name" class="text-sm font-medium text-muted-foreground">Nom</label>
                        <input type="text" name="name" id="name"
                               value="{{ old('name') }}"
                               placeholder="Ex : Accessoires, Logiciels, Audio..."
                               class="form-input w-full" required>
                        @error('name') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="slug" class="text-sm font-medium text-muted-foreground">Slug</label>
                        <input type="text" name="slug" id="slug"
                               value="{{ old('slug') }}"
                               class="form-input w-full" required>
                        @error('slug') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="parent_id" class="text-sm font-medium text-muted-foreground">Catégorie parente</label>
                        <select name="parent_id" id="parent_id" class="form-select w-full">
                            <option value="">Aucune</option>
                            @foreach($parents as $cat)
                                <option value="{{ $cat->id }}" @selected(old('parent_id') == $cat->id)>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="description" class="text-sm font-medium text-muted-foreground">Description</label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-4 py-2 rounded border border-border bg-background text-gray-900 dark:text-white focus:ring-primary focus:outline-none resize-none"
                                  placeholder="Brève description de la catégorie...">{{ old('description') }}</textarea>
                        @error('description') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end space-x-4 pt-4">
                        <a href="{{ route('admin.shop.categories.index') }}"
                           class="inline-flex items-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium hover:bg-accent hover:text-accent-foreground">
                            Annuler
                        </a>
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-md bg-primary text-white text-sm font-medium px-4 py-2 hover:bg-primary/90">
                            <i class="fas fa-plus"></i> Créer la catégorie
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('categoryForm', () => ({
                init() {
                    const nameInput = document.querySelector('input[name="name"]');
                    const slugInput = document.querySelector('input[name="slug"]');

                    nameInput?.addEventListener('input', () => {
                        if (!slugInput.dataset.changed) {
                            const formatted = nameInput.value.toLowerCase()
                                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                                .replace(/\s+/g, '-')
                                .replace(/[^a-z0-9\-]/g, '')
                                .replace(/\-\-+/g, '-')
                                .replace(/^-+|-+$/g, '');

                            slugInput.value = formatted;
                        }
                    });

                    slugInput?.addEventListener('input', () => {
                        slugInput.dataset.changed = true;
                    });
                }
            }));
        });
    </script>
@endpush
