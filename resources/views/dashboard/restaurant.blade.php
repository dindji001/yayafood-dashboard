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
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(241, 245, 249, 1); transition: all 0.3s ease; }
        .glass-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); }
        .btn-primary { background-color: #2C3E3F; transition: all 0.3s ease; }
        .btn-primary:hover { background-color: #1A2829; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(44, 62, 63, 0.2); }
        .order-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .chart-container { position: relative; height: 350px; width: 100%; }
        .stat-icon { position: absolute; right: -10px; bottom: -10px; opacity: 0.05; transform: rotate(-15deg); transition: all 0.5s ease; }
        .glass-card:hover .stat-icon { transform: rotate(0deg) scale(1.1); opacity: 0.08; }
        .quick-action-card { transition: all 0.3s ease; border: 1px solid transparent; }
        .quick-action-card:hover { border-color: currentColor; transform: translateY(-3px); }
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
            <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group">
                <i data-lucide="shopping-cart" class="w-32 h-32 text-orange-500 stat-icon"></i>
                <div class="w-14 h-14 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center mb-6 shadow-sm">
                    <i data-lucide="shopping-cart" class="w-7 h-7"></i>
                </div>
                <p class="text-gray-400 font-bold text-[10px] tracking-[0.2em] uppercase mb-2">Ventes du jour</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-black text-[#2C3E3F] tracking-tight">{{ $stats['orders_today'] }}</h3>
                    <span class="text-xs font-bold text-orange-500 flex items-center gap-1">
                        <i data-lucide="trending-up" class="w-3 h-3"></i>
                        Aujourd'hui
                    </span>
                </div>
            </div>

            <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group">
                <i data-lucide="banknote" class="w-32 h-32 text-green-500 stat-icon"></i>
                <div class="w-14 h-14 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-6 shadow-sm">
                    <i data-lucide="banknote" class="w-7 h-7"></i>
                </div>
                <p class="text-gray-400 font-bold text-[10px] tracking-[0.2em] uppercase mb-2">Recettes Jour</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-black text-[#2C3E3F] tracking-tight">{{ number_format($stats['revenue_today'], 0, ',', ' ') }}</h3>
                    <span class="text-[10px] font-black text-gray-400 uppercase">CFA</span>
                </div>
            </div>

            <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group">
                <i data-lucide="star" class="w-32 h-32 text-amber-500 stat-icon"></i>
                <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mb-6 shadow-sm">
                    <i data-lucide="star" class="w-7 h-7"></i>
                </div>
                <p class="text-gray-400 font-bold text-[10px] tracking-[0.2em] uppercase mb-2">Note Moyenne</p>
                <div class="flex items-center gap-2">
                    <h3 class="text-4xl font-black text-[#2C3E3F] tracking-tight">{{ number_format($stats['average_rating'], 1) }}</h3>
                    <div class="flex text-amber-400">
                        @for($i=0; $i<5; $i++)
                            <i data-lucide="star" class="w-4 h-4 {{ $i < floor($stats['average_rating']) ? 'fill-current' : 'text-gray-200' }}"></i>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group">
                <i data-lucide="package" class="w-32 h-32 text-blue-500 stat-icon"></i>
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-sm">
                    <i data-lucide="package" class="w-7 h-7"></i>
                </div>
                <p class="text-gray-400 font-bold text-[10px] tracking-[0.2em] uppercase mb-2">Commandes Totales</p>
                <h3 class="text-4xl font-black text-[#2C3E3F] tracking-tight">{{ $stats['total_orders'] }}</h3>
            </div>
        </div>

        <!-- Charts and Top Dishes -->
        <div class="grid grid-cols-12 gap-10 mb-12">
            <!-- Revenue Chart -->
            <div class="col-span-12 lg:col-span-8 glass-card rounded-[3rem] p-10 shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-10">
                    <div>
                        <h3 class="text-2xl font-black text-[#2C3E3F] tracking-tight mb-1 uppercase">Analytique Revenus</h3>
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-[0.1em]">Performance des 7 derniers jours</p>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-[#2C3E3F] rounded-full"></span>
                            <span class="text-[10px] font-black text-[#2C3E3F] uppercase tracking-widest">Recettes</span>
                        </div>
                        <div class="bg-gray-50 px-4 py-2 rounded-xl border border-gray-100">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Période: </span>
                            <span class="text-xs font-black text-[#2C3E3F]">{{ number_format($revenueChart->sum('revenue'), 0, ',', ' ') }} CFA</span>
                        </div>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Top Dishes -->
            <div class="col-span-12 lg:col-span-4 glass-card rounded-[3rem] p-10 shadow-sm border border-gray-100 flex flex-col">
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="award" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-black text-[#2C3E3F] uppercase tracking-tight">Best-Sellers</h3>
                </div>
                
                <div class="space-y-6 flex-1">
                    @forelse($topDishes as $index => $item)
                        <div class="flex items-center gap-4 group p-4 rounded-[1.5rem] hover:bg-gray-50 transition-all border border-transparent hover:border-gray-100">
                            <div class="relative">
                                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-[#2C3E3F] font-black text-xl shadow-sm border border-gray-100 group-hover:scale-110 transition-all">
                                    {{ substr($item->dish->name, 0, 1) }}
                                </div>
                                <span class="absolute -top-2 -left-2 w-6 h-6 bg-[#2C3E3F] text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                                    #{{ $index + 1 }}
                                </span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-black text-[#2C3E3F] mb-0.5 group-hover:text-orange-500 transition-colors">{{ $item->dish->name }}</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">{{ $item->total }} ventes</span>
                                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                    <span class="text-[10px] text-orange-500 font-black">{{ number_format($item->dish->price, 0) }} CFA</span>
                                </div>
                            </div>
                            <div class="w-8 h-8 bg-green-50 text-green-600 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                                <i data-lucide="trending-up" class="w-4 h-4"></i>
                            </div>
                        </div>
                    @empty
                        <div class="flex-1 flex flex-col items-center justify-center opacity-40 grayscale">
                            <i data-lucide="utensils-crosses" class="w-16 h-16 mb-4"></i>
                            <p class="text-xs font-black uppercase tracking-widest">Aucune donnée</p>
                        </div>
                    @endforelse
                </div>
                
                <a href="{{ route('restaurant.menu.index') }}" class="w-full mt-8 py-4 rounded-2xl bg-gray-50 text-[#2C3E3F] font-black text-[10px] uppercase tracking-[0.2em] hover:bg-[#2C3E3F] hover:text-white transition-all text-center border border-gray-100">
                    Catalogue Complet
                </a>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-10">
            <!-- Recent Live Orders -->
            <div class="col-span-12 lg:col-span-7">
                <div class="glass-card rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-orange-500 rounded-full animate-pulse shadow-[0_0_10px_rgba(249,115,22,0.5)]"></div>
                            <h3 class="text-xl font-black text-[#2C3E3F] uppercase tracking-tight">Commandes en Direct</h3>
                        </div>
                        <a href="{{ route('restaurant.orders.index') }}" class="text-[10px] font-black text-orange-500 uppercase tracking-widest hover:underline flex items-center gap-2">
                            Voir tout <i data-lucide="arrow-right" class="w-3 h-3"></i>
                        </a>
                    </div>
                    <div class="p-8 space-y-6">
                        @forelse($liveOrders->take(4) as $order)
                            <div class="p-6 bg-white border border-gray-100 rounded-[2rem] flex items-center justify-between group hover:border-orange-200 hover:shadow-xl hover:shadow-orange-500/5 transition-all">
                                <div class="flex items-center gap-5">
                                    <div class="w-16 h-16 bg-orange-50 rounded-2xl flex flex-col items-center justify-center border border-orange-100 group-hover:bg-orange-500 group-hover:border-orange-500 transition-all duration-500">
                                        <span class="text-[8px] font-black text-orange-400 uppercase group-hover:text-white/70 transition-colors">Table</span>
                                        <span class="text-2xl font-black text-orange-600 leading-none group-hover:text-white transition-colors">{{ $order->table_number ?: '?' }}</span>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <p class="text-sm font-black text-[#2C3E3F]">{{ $order->user->name }}</p>
                                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">{{ $order->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="flex -space-x-2">
                                                @foreach($order->items->take(3) as $item)
                                                    <div class="w-6 h-6 rounded-full bg-gray-100 border-2 border-white flex items-center justify-center text-[8px] font-black text-[#2C3E3F]" title="{{ $item->dish->name }}">
                                                        {{ substr($item->dish->name, 0, 1) }}
                                                    </div>
                                                @endforeach
                                                @if(count($order->items) > 3)
                                                    <div class="w-6 h-6 rounded-full bg-[#2C3E3F] border-2 border-white flex items-center justify-center text-[8px] font-black text-white">
                                                        +{{ count($order->items) - 3 }}
                                                    </div>
                                                @endif
                                            </div>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ count($order->items) }} article(s)</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-8">
                                    <div class="text-right">
                                        <p class="text-lg font-black text-[#2C3E3F]">{{ number_format($order->total_amount, 0) }} <span class="text-[10px] text-gray-400">CFA</span></p>
                                        <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-[0.15em] {{ $order->status === 'pending' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }}">
                                            {{ $order->status }}
                                        </span>
                                    </div>
                                    <a href="{{ route('restaurant.orders.index') }}" class="w-12 h-12 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center hover:bg-[#2C3E3F] hover:text-white hover:rotate-12 transition-all">
                                        <i data-lucide="play" class="w-5 h-5"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="py-20 text-center bg-gray-50/50 rounded-[2.5rem] border border-dashed border-gray-200">
                                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                                    <i data-lucide="inbox" class="w-10 h-10 text-gray-200"></i>
                                </div>
                                <p class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Aucune commande active</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Reviews -->
            <div class="col-span-12 lg:col-span-5 flex flex-col gap-10">
                <div class="glass-card rounded-[3rem] p-10 shadow-sm border border-gray-100 flex-1">
                    <div class="flex justify-between items-center mb-10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                                <i data-lucide="message-circle" class="w-6 h-6"></i>
                            </div>
                            <h3 class="text-xl font-black text-[#2C3E3F] uppercase tracking-tight">Retours Clients</h3>
                        </div>
                        <a href="{{ route('restaurant.reviews.index') }}" class="text-[10px] font-black text-blue-500 uppercase tracking-widest hover:underline">Voir tout</a>
                    </div>
                    
                    <div class="space-y-8">
                        @forelse($reviews->take(3) as $review)
                            <div class="relative group">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center gap-3">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $review->user->name }}" class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 group-hover:scale-110 transition-transform">
                                        <div>
                                            <p class="text-sm font-black text-[#2C3E3F]">{{ $review->user->name }}</p>
                                            <div class="flex text-amber-400 gap-0.5">
                                                @for($i=0; $i<5; $i++)
                                                    <i data-lucide="star" class="w-2.5 h-2.5 {{ $i < $review->rating ? 'fill-current' : 'text-gray-200' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-[8px] font-black text-gray-300 uppercase tracking-widest">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="pl-13">
                                    <p class="text-xs text-gray-500 italic leading-relaxed">"{{ $review->comment }}"</p>
                                </div>
                            </div>
                        @empty
                            <div class="py-10 text-center opacity-40 grayscale">
                                <p class="text-xs font-black uppercase tracking-widest">Aucun avis reçu</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-2 gap-6">
                    <a href="{{ route('restaurant.menu.index') }}" class="quick-action-card p-8 rounded-[2.5rem] bg-[#2C3E3F] text-white flex flex-col items-center justify-center gap-4 group">
                        <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center group-hover:bg-orange-500 group-hover:scale-110 transition-all">
                            <i data-lucide="plus-circle" class="w-8 h-8"></i>
                        </div>
                        <span class="text-xs font-black uppercase tracking-[0.2em] text-center">Nouveau Plat</span>
                    </a>
                    <a href="{{ route('restaurant.settings.index') }}" class="quick-action-card p-8 rounded-[2.5rem] bg-white border border-gray-100 text-[#2C3E3F] flex flex-col items-center justify-center gap-4 group shadow-sm">
                        <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center group-hover:bg-[#2C3E3F] group-hover:text-white group-hover:scale-110 transition-all">
                            <i data-lucide="settings" class="w-8 h-8"></i>
                        </div>
                        <span class="text-xs font-black uppercase tracking-[0.2em] text-center">Réglages</span>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();

        // Chart.js Configuration
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueData = JSON.parse('{!! json_encode($revenueChart) !!}');
        
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(44, 62, 63, 0.15)');
        gradient.addColorStop(1, 'rgba(44, 62, 63, 0)');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: revenueData.map(d => {
                    const date = new Date(d.date);
                    return date.toLocaleDateString('fr-FR', { weekday: 'short', day: 'numeric' });
                }),
                datasets: [{
                    label: 'Revenus',
                    data: revenueData.map(d => d.revenue),
                    borderColor: '#2C3E3F',
                    backgroundColor: gradient,
                    borderWidth: 4,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#2C3E3F',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: '#2C3E3F',
                    pointHoverBorderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#2C3E3F',
                        titleFont: { family: 'Plus Jakarta Sans', weight: '800', size: 14 },
                        bodyFont: { family: 'Plus Jakarta Sans', weight: '600', size: 13 },
                        padding: 16,
                        borderRadius: 15,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Recettes: ' + context.parsed.y.toLocaleString() + ' CFA';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { 
                            font: { family: 'Plus Jakarta Sans', weight: '700', size: 11 },
                            color: '#94A3B8'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], color: '#F1F5F9', drawBorder: false },
                        ticks: { 
                            font: { family: 'Plus Jakarta Sans', weight: '700', size: 11 },
                            color: '#94A3B8',
                            callback: function(value) { return value.toLocaleString() + ' CFA'; }
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