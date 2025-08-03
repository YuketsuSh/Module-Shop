@extends('admin.layouts.admin')

@section('title', 'Catégories de Produits')

@section('content')
    <div x-data="categories()" x-init="init()" class="space-y-6">

        {{-- Bouton --}}
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold leading-none tracking-tight">Catégories</h1>
            <a href="{{ route('admin.shop.categories.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground text-sm font-medium h-9 px-4">
                <i class="fas fa-plus"></i> Ajouter
            </a>
        </div>

        {{-- Liste --}}
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm overflow-auto">
            <table class="w-full table-auto text-sm divide-y divide-muted">
                <thead class="bg-muted/10 text-muted-foreground">
                <tr>
                    <th class="px-4 py-2 text-left w-8"></th>
                    <th class="px-4 py-2 text-left">Nom</th>
                    <th class="px-4 py-2 text-left">Slug</th>
                    <th class="px-4 py-2 text-left">Parent</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
                </thead>
                <tbody id="sortable">
                @foreach($categories as $category)
                    <tr data-id="{{ $category->id }}" class="hover:bg-muted/20">
                        <td class="px-4 text-muted-foreground"><i class="fas fa-grip-lines cursor-move"></i></td>
                        <td class="px-4 py-2 font-medium">{{ $category->name }}</td>
                        <td class="px-4 py-2 text-muted-foreground">{{ $category->slug }}</td>
                        <td class="px-4 py-2 text-muted-foreground">{{ $category->parent?->name ?? '—' }}</td>
                        <td class="px-4 py-2">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.shop.categories.edit', $category) }}"
                                   class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8"
                                   title="Modifier">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                                <button @click="confirmDelete({{ $category->id }}, '{{ $category->name }}')" title="Supprimer"
                                        class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium border border-input bg-background hover:bg-destructive/10 hover:text-destructive h-8 w-8">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Info --}}
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-6 space-y-2">
                <h3 class="text-lg font-semibold">Informations</h3>
                <p class="text-sm text-muted-foreground">
                    Glissez-déposez les catégories pour modifier leur ordre.
                </p>
            </div>
        </div>

        {{-- Modal --}}
        <div x-show="showModal" x-transition
             class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center"
             style="display: none;">
            <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-lg max-w-md w-full space-y-4">
                <h2 class="text-lg font-semibold">Confirmation de suppression</h2>
                <p class="text-sm text-muted-foreground">
                    Êtes-vous sûr de vouloir supprimer <strong x-text="selectedName"></strong> ?
                </p>
                <div class="flex justify-end gap-2">
                    <button @click="closeModal()"
                            class="px-4 py-2 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground text-sm">
                        Annuler
                    </button>
                    <button @click="deleteCategory()" class="px-4 py-2 rounded-md bg-destructive text-white text-sm hover:bg-destructive/90">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        function categories() {
            return {
                showModal: false,
                selectedId: null,
                selectedName: '',
                init() {
                    new Sortable(document.getElementById('sortable'), {
                        animation: 150,
                        onEnd: () => {
                            const order = [...document.querySelectorAll('#sortable tr[data-id]')].map(el => el.dataset.id);
                            fetch("{{ route('admin.shop.categories.reorder') }}", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ order })
                            });
                        }
                    });
                },
                confirmDelete(id, name) {
                    this.selectedId = id;
                    this.selectedName = name;
                    this.showModal = true;
                },
                closeModal() {
                    this.showModal = false;
                    this.selectedId = null;
                    this.selectedName = '';
                },
                async deleteCategory() {
                    const res = await fetch(`/admin/shop/categories/${this.selectedId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    if (res.ok) {
                        document.querySelector(`tr[data-id='${this.selectedId}']`)?.remove();
                        this.closeModal();
                    } else {
                        alert("Erreur lors de la suppression.");
                    }
                }
            }
        }
    </script>
@endpush
