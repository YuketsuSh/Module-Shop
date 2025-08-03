@extends('admin.layouts.admin')

@section('title', 'Tags')

@section('content')
    <div x-data="tags()" x-init="init()" class="space-y-6">

        {{-- Header --}}
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold leading-none tracking-tight">Tags</h1>
            <a href="{{ route('admin.shop.tags.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground text-sm font-medium h-9 px-4">
                <i class="fas fa-plus"></i> Ajouter
            </a>
        </div>

        {{-- Table --}}
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm overflow-auto">
            <table class="w-full table-auto text-sm divide-y divide-muted">
                <thead class="bg-muted/10 text-muted-foreground">
                <tr>
                    <th class="px-4 py-2 text-left">Nom</th>
                    <th class="px-4 py-2 text-left">Slug</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($tags as $tag)
                    <tr class="hover:bg-muted/20" data-id="{{ $tag->id }}">
                        <td class="px-4 py-2 font-medium">{{ $tag->name }}</td>
                        <td class="px-4 py-2 text-muted-foreground">{{ $tag->slug }}</td>
                        <td class="px-4 py-2">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.shop.tags.edit', $tag) }}"
                                   class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8"
                                   title="Modifier">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>
                                <button @click="confirmDelete({{ $tag->id }}, '{{ $tag->name }}')" title="Supprimer"
                                        class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium border border-input bg-background hover:bg-destructive/10 hover:text-destructive h-8 w-8">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                @if ($tags->isEmpty())
                    <tr>
                        <td colspan="3" class="text-center text-muted-foreground py-8">Aucun tag disponible.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        {{-- Modale de suppression --}}
        <div x-show="showModal" x-transition
             class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center"
             style="display: none;">
            <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-lg max-w-md w-full space-y-4">
                <h2 class="text-lg font-semibold">Confirmation de suppression</h2>
                <p class="text-sm text-muted-foreground">
                    Supprimer le tag <strong x-text="selectedName"></strong> ?
                </p>
                <div class="flex justify-end gap-2">
                    <button @click="closeModal()"
                            class="px-4 py-2 rounded-md border border-input bg-background hover:bg-accent hover:text-accent-foreground text-sm">
                        Annuler
                    </button>
                    <button @click="deleteTag()" class="px-4 py-2 rounded-md bg-destructive text-white text-sm hover:bg-destructive/90">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function tags() {
            return {
                showModal: false,
                selectedId: null,
                selectedName: '',
                init() {},
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
                async deleteTag() {
                    const res = await fetch(`/admin/shop/tags/${this.selectedId}`, {
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
