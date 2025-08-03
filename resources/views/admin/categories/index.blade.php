@extends('admin.layouts.admin')

@section('title', 'Gestion des Catégories')

@section('content')
    <div x-data="categories()" x-init="init()" class="space-y-6">

        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold leading-none tracking-tight">Catégories de Produits</h1>
            <a href="{{ route('admin.shop.categories.create') }}"
               class="inline-flex items-center gap-2 rounded-md bg-primary text-primary-foreground text-sm font-medium px-4 py-2 hover:bg-primary/90">
                <i class="fas fa-plus"></i> Ajouter une catégorie
            </a>
        </div>

        <div class="space-y-6">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-2xl font-semibold leading-none tracking-tight">Liste des catégories</h3>
                </div>
                <div class="p-6 pt-0">
                    <div class="rounded-md border relative w-full overflow-auto">
                        <table class="w-full caption-bottom text-sm">
                            <thead class="[&_tr]:border-b">
                            <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                <th class="h-12 px-4 text-left font-medium text-muted-foreground w-8"></th>
                                <th class="h-12 px-4 text-left font-medium text-muted-foreground">Nom</th>
                                <th class="h-12 px-4 text-left font-medium text-muted-foreground">Slug</th>
                                <th class="h-12 px-4 text-left font-medium text-muted-foreground">Parent</th>
                                <th class="h-12 px-4 text-left font-medium text-muted-foreground">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="sortable">
                            @foreach($categories as $category)
                                <tr data-id="{{ $category->id }}" class="border-b hover:bg-muted/50">
                                    <td class="px-4"><i class="fas fa-bars cursor-move"></i></td>
                                    <td class="px-4">{{ $category->name }}</td>
                                    <td class="px-4 text-muted-foreground">{{ $category->slug }}</td>
                                    <td class="px-4 text-muted-foreground">
                                        {{ $category->parent?->name ?? '–' }}
                                    </td>
                                    <td class="px-4 space-x-2">
                                        <a href="{{ route('admin.shop.categories.edit', $category) }}" class="text-blue-600 hover:underline">Modifier</a>
                                        <form method="POST" action="{{ route('admin.shop.categories.destroy', $category) }}" class="inline-block"
                                              onsubmit="return confirm('Supprimer cette catégorie ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="flex flex-col space-y-1.5 p-6">
                <h3 class="text-2xl font-semibold leading-none tracking-tight">Informations</h3>
            </div>
            <div class="p-6 pt-0">
                <p class="text-muted-foreground">
                    Glissez-déposez les catégories pour changer l’ordre ou réorganiser la hiérarchie.
                </p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        function categories() {
            return {
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
                }
            }
        }
    </script>
@endpush
