@extends('admin.layouts.admin')
@section('title', "Shop - Admin Dashboard")

@php
    $dashboardData = [
        'kpis'         => $kpis ?? ['revenue' => 0, 'orders_today' => 0, 'abandoned_carts' => 0, 'avg_order' => 0],
        'sales'        => $sales ?? [],
        'topProducts'  => $topProducts ?? [],
        'recentOrders' => $recentOrders ?? []
    ];
@endphp

@section('content')
    <div x-data='shopDashboard(@json($dashboardData))' class="max-w-7xl mx-auto space-y-6">

        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.shop.products.index') }}"
                   class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 hover:bg-accent hover:text-accent-foreground h-9 px-3 hover-glow-purple">
                    <i class="fas fa-arrow-left mr-2"></i> Retour catalogue
                </a>

                <h1 class="text-2xl font-semibold leading-none tracking-tight">Dashboard Boutique</h1>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.shop.products.create') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium text-primary-foreground h-9 px-3 bg-primary hover:bg-primary/90 hover-glow-purple">
                    <i class="fas fa-plus mr-2"></i> Nouveau produit
                </a>
                <a href="{{ route('admin.shop.orders.index') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3">
                    <i class="fas fa-receipt mr-2"></i> Voir commandes
                </a>
            </div>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-muted-foreground">Période :</span>
                    <button type="button" @click="setPreset('7d')"
                            :class="btnPreset('7d')"
                            class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium h-8 px-3 border">
                        7 j
                    </button>
                    <button type="button" @click="setPreset('30d')"
                            :class="btnPreset('30d')"
                            class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium h-8 px-3 border">
                        30 j
                    </button>
                    <button type="button" @click="setPreset('90d')"
                            :class="btnPreset('90d')"
                            class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium h-8 px-3 border">
                        90 j
                    </button>
                </div>

                <div class="flex items-center gap-2">
                    <label class="form-label sr-only">De</label>
                    <input type="date" x-model="from"
                           class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">

                    <span class="text-muted-foreground text-sm">→</span>

                    <label class="form-label sr-only">À</label>
                    <input type="date" x-model="to"
                           class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">

                    <button type="button" @click="applyFilters()"
                            class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium h-9 px-3 bg-primary text-primary-foreground hover:bg-primary/90 hover-glow-purple">
                        <i class="fas fa-filter mr-2"></i> Appliquer
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-4">
                <p class="text-sm text-muted-foreground">Chiffre d’affaires</p>
                <p class="text-2xl font-bold mt-1" x-text="formatCurrency(kpis.revenue)"></p>
            </div>
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-4">
                <p class="text-sm text-muted-foreground">Commandes aujourd’hui</p>
                <p class="text-2xl font-bold mt-1" x-text="kpis.orders_today"></p>
            </div>
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-4">
                <p class="text-sm text-muted-foreground">Panier moyen</p>
                <p class="text-2xl font-bold mt-1" x-text="formatCurrency(kpis.avg_order)"></p>
            </div>
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-4">
                <p class="text-sm text-muted-foreground">Paniers abandonnés</p>
                <p class="text-2xl font-bold mt-1 text-destructive" x-text="kpis.abandoned_carts"></p>
            </div>
        </div>

        <div class="rounded-lg border bg-card text-card-foreground shadow-sm p-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold leading-none tracking-tight">Évolution des ventes</h3>
                <p class="text-sm text-muted-foreground" x-show="sales.length">
                    <span x-text="sales[0]?.date ?? ''"></span>
                    <span>→</span>
                    <span x-text="sales[sales.length-1]?.date ?? ''"></span>
                </p>
            </div>

            <div class="mt-4">
                <div class="w-full h-48">
                    <svg viewBox="0 0 100 40" preserveAspectRatio="none" class="w-full h-full">
                        <path :d="areaPath()" class="fill-primary/10"></path>
                        <polyline :points="polylinePoints()" class="stroke-primary" stroke-width="2" fill="none"></polyline>
                    </svg>
                </div>
                <p class="text-xs text-muted-foreground mt-2" x-show="!sales.length">Aucune donnée sur la période.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-lg font-semibold leading-none tracking-tight">Top produits</h3>
                    <p class="text-sm text-muted-foreground">Quantités et CA sur la période</p>
                </div>
                <div class="p-6 pt-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-muted-foreground">
                            <tr class="text-left">
                                <th class="py-2 pr-4">Produit</th>
                                <th class="py-2 pr-4 text-right">Qté</th>
                                <th class="py-2 pr-4 text-right">CA</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y">
                            <template x-if="!topProducts.length">
                                <tr><td colspan="3" class="py-3 text-muted-foreground">Aucun produit vendu.</td></tr>
                            </template>
                            <template x-for="p in topProducts" :key="p.name">
                                <tr>
                                    <td class="py-2 pr-4">
                                        <span class="font-medium" x-text="p.name"></span>
                                    </td>
                                    <td class="py-2 pr-4 text-right" x-text="p.qty"></td>
                                    <td class="py-2 pr-4 text-right" x-text="formatCurrency(p.revenue)"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3 class="text-lg font-semibold leading-none tracking-tight">Dernières commandes</h3>
                    <p class="text-sm text-muted-foreground">10 plus récentes</p>
                </div>
                <div class="p-6 pt-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-muted-foreground">
                            <tr class="text-left">
                                <th class="py-2 pr-4">N°</th>
                                <th class="py-2 pr-4">Client</th>
                                <th class="py-2 pr-4 text-right">Montant</th>
                                <th class="py-2 pr-4">Statut</th>
                                <th class="py-2 pr-4 text-right">Date</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y">
                            <template x-if="!recentOrders.length">
                                <tr><td colspan="5" class="py-3 text-muted-foreground">Aucune commande.</td></tr>
                            </template>
                            <template x-for="o in recentOrders" :key="o.id">
                                <tr>
                                    <td class="py-2 pr-4">
                                        <a :href="o.show_url" class="hover:underline" x-text="o.id"></a>
                                    </td>
                                    <td class="py-2 pr-4" x-text="o.customer ?? 'Invité'"></td>
                                    <td class="py-2 pr-4 text-right" x-text="formatCurrency(o.total)"></td>
                                    <td class="py-2 pr-4">
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs"
                                              :class="badgeClass(o.status)"
                                              x-text="statusLabel(o.status)"></span>
                                    </td>
                                    <td class="py-2 pr-0 text-right" x-text="o.created_at"></td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-right">
                        <a href="{{ route('admin.shop.orders.index') }}" class="text-sm text-primary hover:underline">Toutes les commandes →</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/alpinejs" defer></script>

    <script>
        window.shopDashboard = function ({ kpis, sales, topProducts, recentOrders }) {
            return {
                kpis, sales, topProducts, recentOrders,
                preset: '30d',
                from: null,
                to: null,

                btnPreset(p) {
                    return this.preset === p
                        ? 'border-primary text-primary bg-primary/10'
                        : 'border border-input bg-background hover:bg-accent hover:text-accent-foreground';
                },

                setPreset(p) {
                    this.preset = p;
                    const days = { '7d':7, '30d':30, '90d':90 }[p] ?? 30;
                    const end = new Date();
                    const start = new Date(); start.setDate(end.getDate() - (days - 1));
                    this.from = start.toISOString().slice(0,10);
                    this.to   = end.toISOString().slice(0,10);
                },

                applyFilters() {
                    const url = new URL(window.location.href);
                    if (this.from) url.searchParams.set('from', this.from);
                    if (this.to)   url.searchParams.set('to', this.to);
                    window.location.href = url.toString();
                },

                maxY() {
                    if (!this.sales.length) return 1;
                    return Math.max(...this.sales.map(s => Number(s.total) || 0)) || 1;
                },
                polylinePoints() {
                    if (!this.sales.length) return '';
                    const n = this.sales.length;
                    const max = this.maxY();
                    return this.sales.map((s, i) => {
                        const x = (i / (n - 1)) * 100;
                        const y = 40 - ((Number(s.total) || 0) / max) * 36 - 2;
                        return `${x.toFixed(2)},${y.toFixed(2)}`;
                    }).join(' ');
                },
                areaPath() {
                    const pts = this.polylinePoints();
                    if (!pts) return '';
                    const firstX = 0, baseY = 40;
                    const lastX = 100;
                    return `M ${firstX},${baseY} L ${pts.replaceAll(' ', ' L ')} L ${lastX},${baseY} Z`;
                },

                formatCurrency(v) {
                    return new Intl.NumberFormat('fr-FR', { style:'currency', currency:'EUR' }).format(Number(v || 0));
                },
                statusLabel(s) {
                    const map = { draft:'Brouillon', pending:'En attente', paid:'Payée', failed:'Échouée', refunded:'Remboursée', fulfilled:'Expédiée', delivered:'Livrée', available:'Disponible' };
                    return map[s] ?? s;
                },
                badgeClass(s) {
                    const base = 'bg-muted text-muted-foreground';
                    const map = {
                        paid:'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300',
                        pending:'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300',
                        failed:'bg-destructive/15 text-destructive',
                        refunded:'bg-sky-100 text-sky-800 dark:bg-sky-900/30 dark:text-sky-300',
                        fulfilled:'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300',
                        delivered:'bg-lime-100 text-lime-800 dark:bg-lime-900/30 dark:text-lime-300',
                    };
                    return map[s] ?? base;
                },

                init() {
                    this.setPreset(this.preset);
                }
            }
        }
    </script>
@endpush
