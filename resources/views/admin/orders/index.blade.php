@extends('admin.layouts.admin')

@section('title', 'Commandes')

@section('content')
    <div x-data="{ status: '{{ $activeStatus }}' }" class="space-y-6">

        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold tracking-tight">Liste des commandes</h1>
        </div>

        <div class="flex items-center gap-4">
            <select @change="window.location = '?status=' + $event.target.value"
                    class="h-10 px-3 rounded-md border border-input bg-background text-sm w-64">
                <option value="">Tous les statuts</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}" @selected($activeStatus === $status->value)>
                        {{ ucfirst($status->label()) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm overflow-auto">
            <table class="w-full table-auto text-sm divide-y divide-muted">
                <thead class="bg-muted/10 text-muted-foreground">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Client</th>
                    <th class="px-4 py-2 text-left">Total</th>
                    <th class="px-4 py-2 text-left">Paiement</th>
                    <th class="px-4 py-2 text-left">Statut</th>
                    <th class="px-4 py-2 text-left">Créée le</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    <tr class="hover:bg-muted/20">
                        <td class="px-4 py-2 font-medium text-foreground">#{{ $order->id }}</td>
                        <td class="px-4 py-2">
                            {{ $order->user?->name ?? 'Utilisateur supprimé' }}<br>
                            <span class="text-xs text-muted-foreground">{{ $order->user?->email }}</span>
                        </td>
                        <td class="px-4 py-2">{{ number_format($order->total_amount, 2) }} {{ $order->currency }}</td>
                        <td class="px-4 py-2 capitalize">{{ ucfirst($order->payment_method) }}</td>
                        <td class="px-4 py-2">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold border
                                @class([
                                    'bg-success/10 text-success border-success/20' => $order->status === 'paid',
                                    'bg-warning/10 text-warning border-warning/20' => $order->status === 'pending',
                                    'bg-destructive/10 text-destructive border-destructive/20' => $order->status === 'failed',
                                    'bg-muted text-muted-foreground border-muted-foreground/20' => ! in_array($order->status, ['paid', 'pending', 'failed']),
                                ])
                            ">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-muted-foreground">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.shop.orders.show', $order) }}"
                                   class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8"
                                   title="Voir commande">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-muted-foreground">Aucune commande trouvée.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $orders->withQueryString()->links() }}
        </div>

    </div>
@endsection
