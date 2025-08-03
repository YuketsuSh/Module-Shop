@extends('admin.layouts.admin')

@section('title', 'Modifier le tag')

@section('content')
    <div class="max-w-2xl mx-auto" x-data="tagForm()" x-init="init()">
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6">
                <h3 class="text-2xl font-semibold leading-none tracking-tight">Modifier le tag</h3>
            </div>

            <div class="p-6 pt-0">
                <form method="POST" action="{{ route('admin.shop.tags.update', $tag) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Nom --}}
                    <div class="space-y-2">
                        <label for="name" class="text-sm font-medium text-muted-foreground">Nom</label>
                        <input type="text" name="name" id="name"
                               value="{{ old('name', $tag->name) }}"
                               class="form-input w-full" required>
                        @error('name') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                    </div>

                    {{-- Slug --}}
                    <div class="space-y-2">
                        <label for="slug" class="text-sm font-medium text-muted-foreground">Slug</label>
                        <input type="text" name="slug" id="slug"
                               value="{{ old('slug', $tag->slug) }}"
                               class="form-input w-full" required>
                        @error('slug') <p class="text-sm text-destructive">{{ $message }}</p> @enderror
                    </div>

                    {{-- Boutons --}}
                    <div class="flex justify-end gap-3 pt-4">
                        <a href="{{ route('admin.shop.tags.index') }}"
                           class="inline-flex items-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium hover:bg-accent hover:text-accent-foreground">
                            Annuler
                        </a>
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-md bg-primary text-white text-sm font-medium px-4 py-2 hover:bg-primary/90">
                            <i class="fas fa-save"></i> Enregistrer
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
            Alpine.data('tagForm', () => ({
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
