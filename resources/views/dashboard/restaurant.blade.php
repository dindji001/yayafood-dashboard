<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Dashboard - YayaFood</title>
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
        .order-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .order-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
        .chart-container { position: relative; height: 300px; width: 100%; }
    </style>
</head>
<body class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-72 bg-white border-r border-gray-100 flex flex-col fixed h-full transition-all duration-300 z-50">
        <div class="p-8">
            <h1 class="text-2xl font-extrabold tracking-tighter flex items-center gap-3 text-[#2C3E3F]">
                <div class="w-10 h-10 bg-[#2C3E3F] rounded-xl flex items-center justify-center text-white">
                    <i data-lucide="chef-hat" class="w-6 h-6"></i>
                </div>
                <span>YayaFood<span class="text-orange-500">.</span></span>
            </h1>
        </div>
        
        <nav class="flex-1 px-4 space-y-2 mt-4">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center gap-3 p-4 rounded-2xl font-bold transition-all group">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Tableau de bord
            </a>
            <a href="{{ route('restaurant.orders.index') }}" class="sidebar-link flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all group">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                Commandes
                @if(isset($liveOrders) && count($liveOrders) > 0)
                    <span class="ml-auto w-2 h-2 bg-orange-500 rounded-full animate-pulse"></span>
                @endif
            </a>
            <a href="{{ route('restaurant.menu.index') }}" class="sidebar-link flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all group">
                <i data-lucide="book-open" class="w-5 h-5"></i>
                Menu Digital
            </a>
            <a href="{{ route('restaurant.reviews.index') }}" class="sidebar-link flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all group">
                <i data-lucide="star" class="w-5 h-5"></i>
                Avis Clients
            </a>
            <a href="{{ route('restaurant.settings.index') }}" class="sidebar-link flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all group">
                <i data-lucide="settings" class="w-5 h-5"></i>
                Configuration
            </a>
        </nav>

        <div class="p-6 border-t border-gray-50 mt-auto">
            <div class="bg-gray-50 p-4 rounded-2xl flex items-center gap-3">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ Auth::user()->name }}" class="w-10 h-10 rounded-xl bg-white p-1">
                <div class="overflow-hidden">
                    <p class="text-xs font-extrabold text-[#2C3E3F] truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Restaurateur</p>
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
            <div class="fixed top-8 right-8 z-[100] bg-green-500 text-white px-8 py-4 rounded-2xl shadow-2xl shadow-green-500/20 flex items-center gap-4 animate-slide-in">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <header class="flex justify-between items-end mb-12">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h2 class="text-4xl font-extrabold text-[#2C3E3F] tracking-tight">{{ $restaurant->name }}</h2>
                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                        Ouvert
                    </span>
                </div>
                <p class="text-gray-400 font-medium flex items-center gap-2 uppercase tracking-tighter text-sm">
                    <i data-lucide="map-pin" class="w-4 h-4 text-orange-400"></i>
                    {{ $restaurant->address ?: 'Adresse non configurée' }}
                </p>
            </div>
            
            <div class="flex gap-4">
                <a href="{{ route('restaurant.settings.index') }}" class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all text-[#2C3E3F]">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                </a>
                <a href="{{ route('restaurant.orders.index') }}" class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all text-[#2C3E3F] relative">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    @if(isset($liveOrders) && count($liveOrders) > 0)
                        <span class="absolute top-2 right-2 w-2 h-2 bg-orange-500 rounded-full"></span>
                    @endif
                </a>
            </div>
        </header>

        <!-- Stats Grid -->
        <div id="dashboard" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group hover:shadow-xl transition-all">
                <div class="absolute -right-6 -bottom-6 text-[#2C3E3F]/5 group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="shopping-cart" class="w-32 h-32"></i>
                </div>
                <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center mb-6">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Ventes du jour</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-extrabold text-[#2C3E3F]">{{ $stats['orders_today'] }}</h3>
                    <span class="text-xs font-bold text-gray-300">commandes</span>
                </div>
            </div>
            <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group hover:shadow-xl transition-all">
                <div class="absolute -right-6 -bottom-6 text-[#2C3E3F]/5 group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="banknote" class="w-32 h-32"></i>
                </div>
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-6">
                    <i data-lucide="banknote" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Recettes Jour</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-extrabold text-[#2C3E3F]">{{ number_format($stats['revenue_today'], 0, ',', ' ') }}</h3>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">CFA</span>
                </div>
            </div>
            <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group hover:shadow-xl transition-all">
                <div class="absolute -right-6 -bottom-6 text-[#2C3E3F]/5 group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="star" class="w-32 h-32"></i>
                </div>
                <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mb-6">
                    <i data-lucide="star" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Réputation</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-extrabold text-[#2C3E3F]">{{ number_format($stats['average_rating'], 1) }}</h3>
                    <div class="flex text-amber-400">
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    </div>
                </div>
            </div>
            <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group hover:shadow-xl transition-all">
                <div class="absolute -right-6 -bottom-6 text-[#2C3E3F]/5 group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="package" class="w-32 h-32"></i>
                </div>
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6">
                    <i data-lucide="package" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Total Ventes</p>
                <h3 class="text-4xl font-extrabold text-[#2C3E3F]">{{ $stats['total_orders'] }}</h3>
            </div>
        </div>

        <!-- Charts and Top Dishes -->
        <div class="grid grid-cols-12 gap-10 mb-12">
            <!-- Revenue Chart -->
            <div class="col-span-12 lg:col-span-8 glass-card rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-xl font-extrabold text-[#2C3E3F]">Performance Commerciale</h3>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-widest">Revenus des 7 derniers jours</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-3 h-3 bg-[#2C3E3F] rounded-full"></span>
                        <span class="text-[10px] font-bold text-[#2C3E3F] uppercase tracking-widest">Recettes (CFA)</span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Top Dishes -->
            <div class="col-span-12 lg:col-span-4 glass-card rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-8 uppercase tracking-tighter">Top 5 des Plats</h3>
                <div class="space-y-6">
                    @forelse($topDishes as $item)
                        <div class="flex items-center gap-4 group">
                            <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-[#2C3E3F] font-black border border-gray-100 group-hover:bg-[#2C3E3F] group-hover:text-white transition-all">
                                {{ substr($item->dish->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-extrabold text-[#2C3E3F]">{{ $item->dish->name }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $item->total }} ventes ce mois</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-orange-500">{{ number_format($item->dish->price, 0) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center opacity-40 italic">
                            <i data-lucide="utensils" class="w-8 h-8 mx-auto mb-2"></i>
                            <p class="text-xs font-bold uppercase tracking-widest">Aucune vente</p>
                        </div>
                    @endforelse
                </div>
                <a href="{{ route('restaurant.menu.index') }}" class="w-full mt-8 flex items-center justify-center gap-2 py-4 rounded-2xl bg-gray-50 text-[#2C3E3F] font-black text-[10px] uppercase tracking-[0.2em] hover:bg-gray-100 transition-all border border-gray-100">
                    Gérer le menu <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-10">
            <!-- Recent Live Orders -->
            <div class="col-span-12 lg:col-span-7">
                <div class="glass-card rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></div>
                            <h3 class="text-xl font-extrabold text-[#2C3E3F] uppercase tracking-tighter">Commandes en Direct</h3>
                        </div>
                        <a href="{{ route('restaurant.orders.index') }}" class="text-[10px] font-black text-orange-500 uppercase tracking-widest hover:underline flex items-center gap-2">
                            Voir tout <i data-lucide="arrow-right" class="w-3 h-3"></i>
                        </a>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($liveOrders->take(3) as $order)
                            <div class="p-6 bg-white border border-gray-100 rounded-3xl flex items-center justify-between group hover:border-orange-200 transition-all shadow-sm">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-orange-50 rounded-2xl flex flex-col items-center justify-center">
                                        <span class="text-[8px] font-black text-orange-400 uppercase">Table</span>
                                        <span class="text-lg font-black text-orange-600 leading-none">{{ $order->table_number ?: '?' }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-extrabold text-[#2C3E3F]">{{ $order->user->name }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">{{ count($order->items) }} articles • {{ $order->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-6">
                                    <div class="text-right">
                                        <p class="text-sm font-black text-[#2C3E3F]">{{ number_format($order->total_amount, 0) }} CFA</p>
                                        <span class="text-[8px] font-black px-2 py-0.5 rounded bg-orange-100 text-orange-600 uppercase tracking-widest">{{ $order->status }}</span>
                                    </div>
                                    <a href="{{ route('restaurant.orders.index') }}" class="p-3 bg-gray-50 text-gray-400 rounded-xl group-hover:bg-[#2C3E3F] group-hover:text-white transition-all">
                                        <i data-lucide="play" class="w-4 h-4"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center opacity-40">
                                <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3"></i>
                                <p class="text-xs font-bold uppercase tracking-[0.2em]">Pas de commande active</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Reviews -->
            <div class="col-span-12 lg:col-span-5">
                <div class="glass-card rounded-[2.5rem] p-10 shadow-sm border border-gray-100 h-full">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-xl font-extrabold text-[#2C3E3F] uppercase tracking-tighter">Derniers Avis</h3>
                        <a href="{{ route('restaurant.reviews.index') }}" class="text-[10px] font-black text-blue-500 uppercase tracking-widest hover:underline">Voir tout</a>
                    </div>
                    <div class="space-y-8">
                        @forelse($reviews->take(3) as $review)
                            <div class="relative pl-4 border-l-2 border-gray-100 hover:border-blue-400 transition-all">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-2">
                                        <p class="text-xs font-black text-[#2C3E3F]">{{ $review->user->name }}</p>
                                        <div class="flex text-amber-400">
                                            @for($i=0; $i<$review->rating; $i++) <i data-lucide="star" class="w-2 h-2 fill-current"></i> @endfor
                                        </div>
                                    </div>
                                    <span class="text-[8px] font-bold text-gray-400 uppercase">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-gray-500 italic line-clamp-2">"{{ $review->comment }}"</p>
                            </div>
                        @empty
                            <div class="py-10 text-center opacity-40">
                                <p class="text-xs font-bold uppercase tracking-widest">Aucun avis reçu</p>
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="mt-12 pt-8 border-t border-gray-50 grid grid-cols-2 gap-4">
                        <a href="{{ route('restaurant.menu.index') }}" class="p-4 rounded-2xl bg-teal-50 text-teal-600 flex flex-col items-center justify-center gap-2 group hover:bg-teal-600 hover:text-white transition-all">
                            <i data-lucide="plus-circle" class="w-5 h-5"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest">Nouveau Plat</span>
                        </a>
                        <a href="{{ route('restaurant.settings.index') }}" class="p-4 rounded-2xl bg-gray-50 text-gray-500 flex flex-col items-center justify-center gap-2 group hover:bg-[#2C3E3F] hover:text-white transition-all">
                            <i data-lucide="settings" class="w-5 h-5"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest">Réglages</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();

        // Chart.js Configuration
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueData = JSON.parse('{!! json_encode($revenueChart) !!}');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: revenueData.map(d => d.date),
                datasets: [{
                    label: 'Revenus',
                    data: revenueData.map(d => d.revenue),
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

        // Auto-hide alerts
        setTimeout(() => {
            const alert = document.querySelector('.animate-slide-in');
            if (alert) {
                alert.style.transform = 'translateX(200%)';
                alert.style.transition = 'all 0.5s ease-in';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    </script>
</body>
</html>