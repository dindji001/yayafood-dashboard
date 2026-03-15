<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard - YayaFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; }
        .sidebar-link.active { background-color: rgba(44, 62, 63, 0.1); color: #2C3E3F; border-right: 4px solid #2C3E3F; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(241, 245, 249, 1); }
        .btn-primary { background-color: #2C3E3F; transition: all 0.3s ease; }
        .btn-primary:hover { background-color: #1A2829; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(44, 62, 63, 0.2); }
        .status-badge { font-size: 0.65rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; }
        .chart-container { position: relative; height: 300px; width: 100%; }
    </style>
</head>
<body class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-72 bg-white border-r border-gray-100 flex flex-col fixed h-full">
        <div class="p-8">
            <a href="{{ route('dashboard') }}" class="text-2xl font-extrabold tracking-tighter flex items-center gap-2 text-[#2C3E3F]">
                <div class="w-10 h-10 bg-[#2C3E3F] rounded-xl flex items-center justify-center text-white">
                    <i data-lucide="utensils-crosses" class="w-6 h-6"></i>
                </div>
                YayaFood<span class="text-orange-500">.</span>
            </a>
        </div>
        
        <nav class="flex-1 px-4 space-y-2 mt-4">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center gap-3 p-4 rounded-2xl font-bold transition-all">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Tableau de bord
            </a>
            <a href="{{ route('admin.restaurants.index') }}" class="sidebar-link {{ request()->routeIs('admin.restaurants.*') ? 'active' : '' }} flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all">
                <i data-lucide="store" class="w-5 h-5"></i>
                Restaurants
            </a>
            <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }} flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                Commandes
            </a>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }} flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all">
                <i data-lucide="users" class="w-5 h-5"></i>
                Clients
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="sidebar-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }} flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all">
                <i data-lucide="star" class="w-5 h-5"></i>
                Avis
            </a>
        </nav>

        <div class="p-6 border-t border-gray-50 mt-auto">
            <div class="bg-gray-50 p-4 rounded-2xl flex items-center gap-3">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ Auth::user()->name }}" class="w-10 h-10 rounded-xl bg-white p-1">
                <div class="overflow-hidden">
                    <p class="text-xs font-extrabold text-[#2C3E3F] truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Super Admin</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 p-3 text-red-500 font-bold text-xs uppercase tracking-widest hover:bg-red-50 rounded-xl transition-all">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-72 p-10">
        @if(session('success'))
            <div class="bg-[#059669]/10 border border-[#059669]/20 text-[#059669] px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 animate-bounce">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <header class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-4xl font-extrabold text-[#2C3E3F] tracking-tight mb-2 uppercase">Vue d'ensemble</h2>
                <p class="text-gray-400 font-medium">Gestion globale de l'écosystème YayaFood.</p>
            </div>
            <div class="flex gap-4">
                <button class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all text-[#2C3E3F]">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                </button>
                <button class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all text-[#2C3E3F]">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                </button>
            </div>
        </header>

        <!-- Stats Grid -->
        <div id="dashboard" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <div class="glass-card p-8 rounded-[2.5rem] shadow-sm relative overflow-hidden group hover:shadow-xl transition-all">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform">
                    <i data-lucide="store" class="w-32 h-32 text-[#2C3E3F]"></i>
                </div>
                <div class="w-12 h-12 bg-[#2C3E3F]/5 rounded-2xl flex items-center justify-center text-[#2C3E3F] mb-6">
                    <i data-lucide="store" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Restaurants</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-extrabold text-[#2C3E3F]">{{ $stats['restaurants_count'] }}</h3>
                    <span class="text-xs font-bold text-green-500 flex items-center"><i data-lucide="arrow-up-right" class="w-3 h-3"></i> +{{ rand(1, 3) }}</span>
                </div>
            </div>
            <div class="glass-card p-8 rounded-[2.5rem] shadow-sm relative overflow-hidden group hover:shadow-xl transition-all">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform text-[#2C3E3F]">
                    <i data-lucide="users" class="w-32 h-32"></i>
                </div>
                <div class="w-12 h-12 bg-blue-500/5 rounded-2xl flex items-center justify-center text-blue-500 mb-6">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Clients Actifs</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-extrabold text-[#2C3E3F]">{{ $stats['users_count'] }}</h3>
                    <span class="text-xs font-bold text-green-500 flex items-center"><i data-lucide="arrow-up-right" class="w-3 h-3"></i> +{{ rand(5, 15) }}%</span>
                </div>
            </div>
            <div class="glass-card p-8 rounded-[2.5rem] shadow-sm relative overflow-hidden group hover:shadow-xl transition-all text-[#2C3E3F]">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform">
                    <i data-lucide="shopping-bag" class="w-32 h-32"></i>
                </div>
                <div class="w-12 h-12 bg-orange-500/5 rounded-2xl flex items-center justify-center text-orange-500 mb-6">
                    <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Commandes</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-extrabold text-[#2C3E3F]">{{ $stats['orders_count'] }}</h3>
                    <span class="text-xs font-bold text-orange-500 flex items-center"><i data-lucide="trending-up" class="w-3 h-3"></i> {{ rand(2, 8) }}%</span>
                </div>
            </div>
            <div class="glass-card p-8 rounded-[2.5rem] shadow-sm relative overflow-hidden group hover:shadow-xl transition-all">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform text-[#2C3E3F]">
                    <i data-lucide="wallet" class="w-32 h-32"></i>
                </div>
                <div class="w-12 h-12 bg-green-500/5 rounded-2xl flex items-center justify-center text-green-600 mb-6">
                    <i data-lucide="wallet" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Chiffre d'affaires</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-extrabold text-[#2C3E3F]">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} <span class="text-xs font-bold text-gray-400 ml-1">CFA</span></h3>
                </div>
            </div>
        </div>

        <!-- Charts and Recent Activity -->
        <div class="grid grid-cols-12 gap-10 mb-12">
            <!-- Revenue Chart -->
            <div class="col-span-12 lg:col-span-8 glass-card rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-extrabold text-[#2C3E3F]">Performance de l'Écosystème</h3>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-widest">Revenus des 7 derniers jours</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-3 h-3 bg-[#2C3E3F] rounded-full"></span>
                        <span class="text-[10px] font-bold text-[#2C3E3F] uppercase">Revenus (CFA)</span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-span-12 lg:col-span-4 glass-card rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-8 uppercase tracking-tighter">Commandes Récentes</h3>
                <div class="space-y-6">
                    @forelse($recentOrders as $order)
                        <div class="flex items-center gap-4 group">
                            <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-[#2C3E3F] font-black border border-gray-100 group-hover:bg-[#2C3E3F] group-hover:text-white transition-all">
                                {{ substr($order->restaurant->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-extrabold text-[#2C3E3F]">{{ $order->restaurant->name }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">{{ $order->user->name }} • {{ $order->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-[#2C3E3F]">{{ number_format($order->total_amount, 0, ',', ' ') }}</p>
                                <span class="text-[8px] font-black text-green-500 uppercase tracking-widest px-2 py-0.5 bg-green-50 rounded-md border border-green-100">{{ $order->status }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center opacity-40 italic">
                            <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2"></i>
                            <p class="text-xs font-bold uppercase tracking-widest">Aucune commande</p>
                        </div>
                    @endforelse
                </div>
                <a href="{{ route('admin.orders.index') }}" class="w-full mt-8 flex items-center justify-center gap-2 py-4 rounded-2xl bg-gray-50 text-[#2C3E3F] font-black text-[10px] uppercase tracking-[0.2em] hover:bg-gray-100 transition-all border border-gray-100">
                    Historique Complet <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-10">
            <!-- Left Side: Quick Stats / Clients -->
            <div class="col-span-12 lg:col-span-4 space-y-8">
                <div class="glass-card rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                    <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-8 uppercase tracking-tighter">Nouveaux Clients</h3>
                    <div class="space-y-6">
                        @foreach($recentUsers as $user)
                            <div class="flex items-center gap-4 group">
                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $user->name }}" class="w-10 h-10 rounded-xl bg-gray-50 p-1 border border-gray-100 shadow-sm group-hover:scale-110 transition-transform">
                                <div class="flex-1">
                                    <p class="text-sm font-extrabold text-[#2C3E3F]">{{ $user->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Inscrit {{ $user->created_at->diffForHumans() }}</p>
                                </div>
                                <a href="{{ route('admin.users.show', $user->id) }}" class="p-2 hover:bg-gray-50 rounded-lg transition-all text-gray-400 hover:text-[#2C3E3F]">
                                    <i data-lucide="external-link" class="w-4 h-4"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Create Restaurant CTA -->
                <a href="{{ route('admin.restaurants.index') }}" class="block glass-card rounded-[2.5rem] p-10 shadow-sm border border-gray-100 bg-[#2C3E3F] text-white hover:scale-[1.02] transition-all group relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full group-hover:scale-125 transition-transform duration-700"></div>
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-orange-500 transition-all relative z-10">
                        <i data-lucide="plus" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-2xl font-black mb-2 uppercase tracking-tight relative z-10">Étendre le réseau</h3>
                    <p class="text-white/60 text-xs font-bold uppercase tracking-[0.15em] relative z-10">Ajouter un nouveau partenaire</p>
                </a>
            </div>

            <!-- Right Side: Restaurant Table (Summary) -->
            <div class="col-span-12 lg:col-span-8">
                <div class="glass-card rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                        <div>
                            <h3 class="text-xl font-extrabold text-[#2C3E3F] uppercase tracking-tighter">Partenaires Actifs</h3>
                            <p class="text-xs font-medium text-gray-400 italic">Top 5 des établissements les plus actifs</p>
                        </div>
                        <a href="{{ route('admin.restaurants.index') }}" class="text-[10px] font-black text-orange-500 uppercase tracking-widest hover:underline flex items-center gap-2">
                            Tout voir <i data-lucide="arrow-right" class="w-3 h-3"></i>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50/50">
                                <tr class="text-gray-400 text-[10px] font-extrabold uppercase tracking-[0.15em]">
                                    <th class="px-8 py-5">Établissement</th>
                                    <th class="px-8 py-5 text-center">Commandes</th>
                                    <th class="px-8 py-5 text-center">Statut</th>
                                    <th class="px-8 py-5 text-right">Analytique</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($restaurants->take(5) as $r)
                                    <tr class="hover:bg-gray-50/30 transition-all group">
                                        <td class="px-8 py-6">
                                            <div class="flex items-center gap-3">
                                                @if($r->logo)
                                                    <img src="{{ $r->logo_url }}" class="w-10 h-10 rounded-xl object-cover border border-gray-100 bg-white">
                                                @else
                                                    <div class="w-10 h-10 bg-[#2C3E3F]/5 rounded-xl flex items-center justify-center text-[#2C3E3F] font-black border border-gray-100">
                                                        {{ substr($r->name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="text-sm font-extrabold text-[#2C3E3F]">{{ $r->name }}</p>
                                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tight truncate max-w-[200px]">{{ $r->address ?: 'Sans adresse' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 text-center">
                                            <span class="text-xs font-black text-[#2C3E3F] bg-gray-100 px-3 py-1 rounded-lg">{{ $r->orders_count }} cmd.</span>
                                        </td>
                                        <td class="px-8 py-6 text-center">
                                            <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest {{ $r->is_active ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                                {{ $r->is_active ? 'Opérationnel' : 'Suspendu' }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <a href="{{ route('admin.restaurants.show', $r->id) }}" class="p-3 hover:bg-orange-50 rounded-xl transition-all inline-block text-gray-400 hover:text-orange-500 border border-transparent hover:border-orange-100">
                                                <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();

        // Chart.js Configuration
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const chartData = JSON.parse('{!! json_encode($chartData) !!}');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.map(d => d.date),
                datasets: [{
                    label: 'Revenus',
                    data: chartData.map(d => d.revenue),
                    borderColor: '#2C3E3F',
                    backgroundColor: 'rgba(44, 62, 63, 0.05)',
                    borderWidth: 4,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2C3E3F',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#2C3E3F',
                        titleFont: { family: 'Plus Jakarta Sans', weight: '800' },
                        bodyFont: { family: 'Plus Jakarta Sans', weight: '600' },
                        padding: 12,
                        borderRadius: 12,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toLocaleString() + ' CFA';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', weight: '600', size: 10 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], color: '#f1f5f9' },
                        ticks: { 
                            font: { family: 'Plus Jakarta Sans', weight: '600', size: 10 },
                            callback: function(value) { return value.toLocaleString(); }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>