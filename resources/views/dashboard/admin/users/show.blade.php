<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - Profil Client</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(241, 245, 249, 1); }
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
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Tableau de bord
            </a>
            <a href="{{ route('admin.restaurants.index') }}" class="flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all">
                <i data-lucide="store" class="w-5 h-5"></i>
                Liste des Restaurants
            </a>
            <a href="{{ route('admin.users.index') }}" class="bg-[#2C3E3F]/10 text-[#2C3E3F] border-r-4 border-[#2C3E3F] flex items-center gap-3 p-4 rounded-2xl font-bold transition-all">
                <i data-lucide="users" class="w-5 h-5"></i>
                Utilisateurs
            </a>
        </nav>
    </aside>

    <main class="flex-1 ml-72 p-10">
        <header class="flex justify-between items-center mb-12">
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.users.index') }}" class="p-3 bg-white rounded-xl border border-gray-100 shadow-sm hover:bg-gray-50 transition-all">
                    <i data-lucide="chevron-left" class="w-5 h-5 text-[#2C3E3F]"></i>
                </a>
                <div>
                    <h2 class="text-4xl font-extrabold text-[#2C3E3F] tracking-tight uppercase">{{ $user->name }}</h2>
                    <p class="text-gray-400 font-medium italic">Membre depuis le {{ $user->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
            <div class="bg-white p-2 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="pl-6 pr-2">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right px-2">Statut Compte</p>
                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-[9px] font-black uppercase tracking-widest">Actif</span>
                </div>
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $user->name }}" class="w-14 h-14 rounded-[1.5rem] bg-gray-50 border border-gray-50 p-1">
            </div>
        </header>

        <!-- Top Stats Grid -->
        <div class="grid grid-cols-4 gap-8 mb-12">
            <div class="glass-card p-8 rounded-[2.5rem] shadow-sm">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-6">
                    <i data-lucide="wallet" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-[10px] tracking-widest uppercase mb-2">Total Dépensé</p>
                <h3 class="text-3xl font-black text-[#2C3E3F]">{{ number_format($userStats['total_spent'], 0, ',', ' ') }} <span class="text-xs">CFA</span></h3>
            </div>
            <div class="glass-card p-8 rounded-[2.5rem] shadow-sm text-[#2C3E3F]">
                <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center mb-6">
                    <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-[10px] tracking-widest uppercase mb-2">Commandes Totales</p>
                <h3 class="text-3xl font-black">{{ $user->orders_count }}</h3>
            </div>
            <div class="glass-card p-8 rounded-[2.5rem] shadow-sm text-[#2C3E3F]">
                <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mb-6">
                    <i data-lucide="star" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-[10px] tracking-widest uppercase mb-2">Note Moyenne Donnée</p>
                <h3 class="text-3xl font-black">{{ number_format($userStats['avg_rating'], 1) }} ★</h3>
            </div>
            <div class="glass-card p-8 rounded-[2.5rem] shadow-sm text-[#2C3E3F]">
                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center mb-6">
                    <i data-lucide="alert-circle" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-[10px] tracking-widest uppercase mb-2">Commandes Annulées</p>
                <h3 class="text-3xl font-black">{{ $userStats['orders_cancelled'] }}</h3>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-10">
            <!-- Left Side: Preferences -->
            <div class="col-span-4 space-y-10">
                <!-- Favorite Dish -->
                <div class="glass-card rounded-[2.5rem] p-10 shadow-sm border-l-8 border-orange-500">
                    <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-8 flex items-center gap-3 uppercase tracking-tighter">
                        <i data-lucide="heart" class="w-6 h-6 text-red-500 fill-current"></i>
                        Plat Préféré
                    </h3>
                    @if($favoriteDish)
                        <div class="flex items-center gap-6">
                            <div class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center text-3xl">🥘</div>
                            <div>
                                <p class="text-lg font-black text-[#2C3E3F]">{{ $favoriteDish->dish->name }}</p>
                                <p class="text-xs font-bold text-orange-500 uppercase tracking-widest">{{ $favoriteDish->total }} fois commandé</p>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-400 text-sm font-medium italic">Pas encore de plat favori.</p>
                    @endif
                </div>

                <!-- Favorite Restaurant -->
                <div class="glass-card rounded-[2.5rem] p-10 shadow-sm border-l-8 border-[#2C3E3F]">
                    <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-8 flex items-center gap-3 uppercase tracking-tighter">
                        <i data-lucide="store" class="w-6 h-6 text-[#2C3E3F]"></i>
                        Habitude Locale
                    </h3>
                    @if($favoriteRestaurant)
                        <div class="flex items-center gap-6">
                            <div class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center text-3xl">🏢</div>
                            <div>
                                <p class="text-lg font-black text-[#2C3E3F]">{{ $favoriteRestaurant->restaurant->name }}</p>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $favoriteRestaurant->total }} visites</p>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-400 text-sm font-medium italic">Pas encore de restaurant favori.</p>
                    @endif
                </div>
            </div>

            <!-- Right Side: Activity -->
            <div class="col-span-8 space-y-10">
                <!-- Recent Reviews -->
                <div class="glass-card rounded-[3rem] p-10 shadow-sm overflow-hidden">
                    <div class="flex justify-between items-center mb-10">
                        <h3 class="text-2xl font-black text-[#2C3E3F] uppercase tracking-tighter">Historique des Avis</h3>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $user->reviews_count }} avis au total</span>
                    </div>
                    <div class="space-y-6">
                        @forelse($user->reviews as $review)
                            <div class="p-6 bg-gray-50 rounded-[2rem] border border-gray-100 group hover:bg-white hover:shadow-xl hover:shadow-gray-200/50 transition-all">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm text-lg">🏢</div>
                                        <div>
                                            <p class="text-sm font-black text-[#2C3E3F]">{{ $review->restaurant->name }}</p>
                                            <div class="flex text-amber-400 gap-0.5">
                                                @for($i=0; $i<$review->rating; $i++) <i data-lucide="star" class="w-3 h-3 fill-current"></i> @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-gray-500 italic text-sm leading-relaxed px-2">"{{ $review->comment }}"</p>
                            </div>
                        @empty
                            <div class="text-center py-12 opacity-30">
                                <i data-lucide="message-square-off" class="w-12 h-12 mx-auto mb-4"></i>
                                <p class="text-sm font-bold uppercase tracking-widest">Aucun avis publié</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Orders Table -->
                <div class="glass-card rounded-[3rem] shadow-sm overflow-hidden">
                    <div class="p-10 border-b border-gray-50 flex justify-between items-center">
                        <h3 class="text-2xl font-black text-[#2C3E3F] uppercase tracking-tighter">Dernières Commandes</h3>
                        <i data-lucide="shopping-cart" class="w-6 h-6 text-gray-200"></i>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50/50">
                                <tr class="text-gray-400 text-[10px] font-extrabold uppercase tracking-[0.15em]">
                                    <th class="px-10 py-5">Restaurant</th>
                                    <th class="px-10 py-5">Date</th>
                                    <th class="px-10 py-5 text-right">Montant</th>
                                    <th class="px-10 py-5 text-center">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($user->orders->take(5) as $order)
                                    <tr class="hover:bg-gray-50/30 transition-all">
                                        <td class="px-10 py-6 font-extrabold text-[#2C3E3F] text-sm">{{ $order->restaurant->name }}</td>
                                        <td class="px-10 py-6 text-xs text-gray-400 font-bold uppercase">{{ $order->created_at->format('d M Y') }}</td>
                                        <td class="px-10 py-6 text-right font-black text-[#2C3E3F]">{{ number_format($order->total_amount, 0) }} CFA</td>
                                        <td class="px-10 py-6 text-center">
                                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $order->status === 'served' ? 'bg-green-100 text-green-600' : 'bg-orange-100 text-orange-600' }}">
                                                {{ $order->status }}
                                            </span>
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

    <script>lucide.createIcons();</script>
</body>
</html>