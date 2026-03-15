<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Dashboard - YayaFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; }
        .sidebar-link.active { background-color: #2C3E3F; color: white; box-shadow: 0 10px 15px -3px rgba(44, 62, 63, 0.2); }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(241, 245, 249, 1); }
        .btn-primary { background-color: #2C3E3F; transition: all 0.3s ease; }
        .btn-primary:hover { background-color: #1A2829; transform: translateY(-1px); }
        .order-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .order-card:hover { transform: scale(1.02); }
        @keyframes pulse-soft { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
        .animate-pulse-soft { animation: pulse-soft 2s infinite; }
    </style>
</head>
<body class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-20 lg:w-72 bg-[#2C3E3F] text-white flex flex-col fixed h-full transition-all duration-300 z-50">
        <div class="p-6 lg:p-8 mb-10">
            <h1 class="text-xl lg:text-2xl font-extrabold tracking-tighter flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-[#2C3E3F]">
                    <i data-lucide="chef-hat" class="w-6 h-6"></i>
                </div>
                <span class="hidden lg:block">YayaFood<span class="text-orange-400">.</span></span>
            </h1>
        </div>
        
        <nav class="flex-1 px-4 space-y-3">
            <a href="#dashboard" class="sidebar-link active flex items-center gap-4 p-4 rounded-2xl font-bold transition-all group">
                <i data-lucide="layout-grid" class="w-6 h-6"></i>
                <span class="hidden lg:block">Tableau de bord</span>
            </a>
            <a href="#orders" class="sidebar-link flex items-center gap-4 p-4 rounded-2xl font-semibold text-gray-400 hover:bg-white/10 hover:text-white transition-all group">
                <div class="relative">
                    <i data-lucide="bell" class="w-6 h-6"></i>
                    @if(count($liveOrders) > 0)
                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-orange-500 rounded-full border-2 border-[#2C3E3F]"></span>
                    @endif
                </div>
                <span class="hidden lg:block">Commandes Live</span>
            </a>
            <a href="#menu" class="sidebar-link flex items-center gap-4 p-4 rounded-2xl font-semibold text-gray-400 hover:bg-white/10 hover:text-white transition-all group">
                <i data-lucide="book-open" class="w-6 h-6"></i>
                <span class="hidden lg:block">Menu Digital</span>
            </a>
            <a href="#reviews" class="sidebar-link flex items-center gap-4 p-4 rounded-2xl font-semibold text-gray-400 hover:bg-white/10 hover:text-white transition-all group">
                <i data-lucide="star" class="w-6 h-6"></i>
                <span class="hidden lg:block">Avis Clients</span>
            </a>
            <a href="#profile" class="sidebar-link flex items-center gap-4 p-4 rounded-2xl font-semibold text-gray-400 hover:bg-white/10 hover:text-white transition-all group">
                <i data-lucide="settings" class="w-6 h-6"></i>
                <span class="hidden lg:block">Configuration</span>
            </a>
        </nav>

        <div class="p-6 mt-auto border-t border-white/5">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-3 p-4 text-red-400 font-bold text-xs uppercase tracking-widest hover:bg-red-500/10 rounded-2xl transition-all">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    <span class="hidden lg:block">Déconnexion</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-20 lg:ml-72 p-6 lg:p-12">
        @if(session('success'))
            <div class="fixed top-8 right-8 z-[100] bg-green-500 text-white px-8 py-4 rounded-2xl shadow-2xl shadow-green-500/20 flex items-center gap-4 animate-slide-in">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <header class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-12 gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-[#2C3E3F] tracking-tight">{{ $restaurant->name }}</h2>
                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                        Ouvert
                    </span>
                </div>
                <p class="text-gray-400 font-medium flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-orange-400"></i>
                    {{ $restaurant->address ?: 'Adresse non configurée' }}
                </p>
            </div>
            
            <div class="flex items-center gap-4 bg-white p-2 rounded-[2rem] shadow-sm border border-gray-100">
                <div class="pl-6 pr-4 hidden lg:block">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Connecté en tant que</p>
                    <p class="text-xs font-extrabold text-[#2C3E3F]">{{ Auth::user()->name }}</p>
                </div>
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ Auth::user()->name }}" class="w-12 h-12 rounded-[1.5rem] bg-[#F1F5F4] p-1 border border-gray-50">
            </div>
        </header>

        <!-- Stats Grid -->
        <div id="dashboard" class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 text-[#2C3E3F]/5 group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="shopping-cart" class="w-32 h-32"></i>
                </div>
                <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center mb-6">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Ventes du jour</p>
                <h3 class="text-4xl font-extrabold text-[#2C3E3F]">{{ $stats['orders_today'] }} <span class="text-sm font-medium text-gray-300">cmd.</span></h3>
            </div>
            <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 text-[#2C3E3F]/5 group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="banknote" class="w-32 h-32"></i>
                </div>
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-6">
                    <i data-lucide="banknote" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Recettes (CFA)</p>
                <h3 class="text-4xl font-extrabold text-[#2C3E3F]">{{ number_format($stats['revenue_today'], 0, ',', ' ') }}</h3>
            </div>
            <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group">
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
        </div>

        <!-- Live Orders Section -->
        <section id="orders" class="mb-16">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-extrabold text-[#2C3E3F] flex items-center gap-3">
                    <i data-lucide="flame" class="w-6 h-6 text-orange-500 animate-pulse"></i>
                    Commandes en cours
                </h3>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-[0.2em]">{{ count($liveOrders) }} commande(s) active(s)</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                @forelse($liveOrders as $order)
                    <div class="glass-card rounded-[2.5rem] p-8 border-l-8 border-orange-500 order-card">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-black text-white bg-orange-500 px-2 py-0.5 rounded-md uppercase tracking-widest">Table {{ $order->table_number ?: '?' }}</span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">#{{ $order->id }}</span>
                                </div>
                                <h4 class="text-lg font-extrabold text-[#2C3E3F]">{{ $order->user->name }}</h4>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">{{ $order->created_at->diffForHumans() }}</p>
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-[9px] font-black uppercase tracking-widest">{{ $order->status }}</span>
                            </div>
                        </div>

                        <div class="space-y-3 mb-8">
                            @foreach($order->items as $item)
                                <div class="flex justify-between items-center text-sm font-semibold">
                                    <div class="flex items-center gap-3">
                                        <span class="w-6 h-6 bg-gray-100 rounded-lg flex items-center justify-center text-[10px] text-[#2C3E3F]">{{ $item->quantity }}</span>
                                        <span class="text-gray-600">{{ $item->dish->name }}</span>
                                    </div>
                                    <span class="text-[#2C3E3F]">{{ number_format($item->price * $item->quantity, 0) }}</span>
                                </div>
                            @endforeach
                            <div class="pt-3 border-t border-dashed border-gray-100 flex justify-between items-center">
                                <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Total</span>
                                <span class="text-xl font-black text-[#2C3E3F]">{{ number_format($order->total_amount, 0) }} <span class="text-xs">CFA</span></span>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            @if($order->status === 'pending')
                                <form action="{{ route('restaurant.orders.status', $order->id) }}" method="POST" class="flex-1">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="preparing">
                                    <button class="w-full bg-[#2C3E3F] text-white py-4 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-[#1A2829] transition-all flex items-center justify-center gap-2">
                                        <i data-lucide="play" class="w-4 h-4"></i> Préparer
                                    </button>
                                </form>
                            @elseif($order->status === 'preparing')
                                <form action="{{ route('restaurant.orders.status', $order->id) }}" method="POST" class="flex-1">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="ready">
                                    <button class="w-full bg-green-500 text-white py-4 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-green-600 transition-all flex items-center justify-center gap-2">
                                        <i data-lucide="check" class="w-4 h-4"></i> Terminé
                                    </button>
                                </form>
                            @elseif($order->status === 'ready')
                                <form action="{{ route('restaurant.orders.status', $order->id) }}" method="POST" class="flex-1">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="served">
                                    <button class="w-full bg-blue-500 text-white py-4 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-blue-600 transition-all flex items-center justify-center gap-2">
                                        <i data-lucide="hand" class="w-4 h-4"></i> Servi
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('restaurant.orders.status', $order->id) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="cancelled">
                                <button class="p-4 bg-red-50 text-red-500 rounded-2xl hover:bg-red-500 hover:text-white transition-all">
                                    <i data-lucide="x" class="w-5 h-5"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 flex flex-col items-center justify-center bg-white rounded-[3rem] border border-dashed border-gray-200 opacity-50">
                        <i data-lucide="inbox" class="w-16 h-16 text-gray-300 mb-4"></i>
                        <p class="text-gray-400 font-extrabold uppercase tracking-widest text-sm">Le calme avant la tempête...</p>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Menu Management -->
        <div id="menu" class="grid grid-cols-12 gap-10 mb-16">
            <div class="col-span-12 xl:col-span-4 space-y-8">
                <div class="glass-card rounded-[2.5rem] p-10 shadow-sm">
                    <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-8 flex items-center gap-3">
                        <i data-lucide="folder-plus" class="w-6 h-6 text-orange-500"></i>
                        Nouvelle Catégorie
                    </h3>
                    <form action="{{ route('restaurant.categories.create') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="text" name="name" placeholder="Ex: Entrées, Desserts..." required class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-semibold focus:ring-2 focus:ring-orange-500/10 outline-none">
                        <button type="submit" class="w-full bg-[#2C3E3F] text-white py-5 rounded-[1.5rem] font-extrabold text-sm hover:scale-[1.02] transition-all">
                            Créer la catégorie
                        </button>
                    </form>
                </div>

                <div class="glass-card rounded-[2.5rem] p-10 shadow-sm">
                    <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-8 flex items-center gap-3">
                        <i data-lucide="plus-circle" class="w-6 h-6 text-green-500"></i>
                        Ajouter un Plat
                    </h3>
                    <form action="{{ route('restaurant.dishes.create') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 px-2">Catégorie</label>
                                <select name="category_id" required class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-semibold outline-none appearance-none">
                                    @foreach($restaurant->categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="text" name="name" placeholder="Nom du plat" required class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-semibold outline-none">
                            <div class="relative">
                                <input type="number" name="price" placeholder="Prix" required class="w-full bg-gray-50 border-none rounded-2xl pl-6 pr-16 py-4 text-sm font-semibold outline-none">
                                <span class="absolute right-6 top-1/2 -translate-y-1/2 text-xs font-black text-gray-400 uppercase tracking-widest">CFA</span>
                            </div>
                            <textarea name="description" placeholder="Description courte..." rows="3" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-semibold outline-none"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-orange-500 text-white py-5 rounded-[1.5rem] font-extrabold text-sm hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/20">
                            Ajouter à la carte
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-span-12 xl:col-span-8">
                <div class="glass-card rounded-[3rem] shadow-sm overflow-hidden">
                    <div class="p-10 border-b border-gray-50 flex justify-between items-center">
                        <div>
                            <h3 class="text-2xl font-extrabold text-[#2C3E3F]">Votre Carte Digitale</h3>
                            <p class="text-sm font-medium text-gray-400 italic">Gérez vos plats et leur disponibilité</p>
                        </div>
                        <i data-lucide="book-open" class="w-8 h-8 text-gray-100"></i>
                    </div>
                    <div class="p-10 space-y-12">
                        @foreach($restaurant->categories as $cat)
                            <div>
                                <div class="flex justify-between items-center mb-6">
                                    <h4 class="text-xl font-black text-[#2C3E3F] tracking-tight">{{ $cat->name }}</h4>
                                    <form action="{{ route('restaurant.categories.delete', $cat->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="p-2 text-gray-300 hover:text-red-500 transition-colors"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                    </form>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @foreach($cat->dishes as $dish)
                                        <div class="p-6 bg-gray-50 rounded-[2rem] border border-gray-100 flex justify-between items-center group hover:bg-white hover:shadow-xl hover:shadow-gray-200/50 transition-all">
                                            <div>
                                                <p class="font-extrabold text-[#2C3E3F] mb-1">{{ $dish->name }}</p>
                                                <p class="text-xs font-bold text-orange-500">{{ number_format($dish->price, 0) }} CFA</p>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <form action="{{ route('restaurant.dishes.toggle', $dish->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-[0.15em] {{ $dish->is_available ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                                        {{ $dish->is_available ? 'En Stock' : 'Épuisé' }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('restaurant.dishes.delete', $dish->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button class="p-2 text-gray-200 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-all"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews & Feedback -->
        <section id="reviews" class="mb-16">
            <h3 class="text-2xl font-extrabold text-[#2C3E3F] mb-8 flex items-center gap-3">
                <i data-lucide="message-square" class="w-6 h-6 text-blue-500"></i>
                Retours clients
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($reviews as $review)
                    <div class="glass-card rounded-[2.5rem] p-8">
                        <div class="flex justify-between items-start mb-6">
                            <div class="flex items-center gap-4">
                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $review->user->name }}" class="w-12 h-12 rounded-2xl bg-gray-50 border border-gray-100">
                                <div>
                                    <h4 class="font-extrabold text-[#2C3E3F]">{{ $review->user->name }}</h4>
                                    <div class="flex text-amber-400 gap-0.5">
                                        @for($i=0; $i<$review->rating; $i++) <i data-lucide="star" class="w-3 h-3 fill-current"></i> @endfor
                                    </div>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-500 italic mb-8 px-2">"{{ $review->comment }}"</p>
                        
                        @if($review->reply)
                            <div class="bg-gray-50 p-6 rounded-2xl border-l-4 border-teal-500 relative">
                                <i data-lucide="corner-down-right" class="absolute -top-3 -left-3 w-6 h-6 text-teal-500"></i>
                                <p class="text-[10px] font-black text-teal-600 uppercase tracking-widest mb-2">Votre réponse officielle</p>
                                <p class="text-sm font-semibold text-gray-600">{{ $review->reply }}</p>
                            </div>
                        @else
                            <form action="{{ route('restaurant.reviews.reply', $review->id) }}" method="POST" class="mt-4">
                                @csrf
                                <div class="relative">
                                    <input type="text" name="reply" placeholder="Écrire une réponse..." required class="w-full bg-gray-50 border-none rounded-2xl pl-6 pr-24 py-4 text-sm font-semibold outline-none focus:ring-2 focus:ring-teal-500/10">
                                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-teal-500 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-teal-600 transition-all">
                                        Publier
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Configuration & Profile -->
        <section id="profile" class="glass-card rounded-[3rem] p-12 mb-12">
            <div class="flex items-center gap-4 mb-12">
                <div class="w-14 h-14 bg-gray-50 rounded-[1.5rem] flex items-center justify-center text-[#2C3E3F]">
                    <i data-lucide="settings-2" class="w-8 h-8"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-[#2C3E3F]">Paramètres du Restaurant</h3>
                    <p class="text-sm font-medium text-gray-400">Identité visuelle et coordonnées</p>
                </div>
            </div>

            <form action="{{ route('restaurant.info.update') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                @csrf
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-12">
                    <div class="space-y-6">
                        <div class="group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 px-2">Nom commercial</label>
                            <input type="text" name="name" value="{{ $restaurant->name }}" required class="w-full bg-gray-50 border-none rounded-2xl px-6 py-5 text-sm font-bold text-[#2C3E3F] focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 px-2">Description</label>
                            <textarea name="description" rows="4" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-5 text-sm font-bold text-[#2C3E3F] focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none" placeholder="Décrivez votre restaurant...">{{ $restaurant->description }}</textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 px-2">Adresse de l'établissement</label>
                            <input type="text" name="address" value="{{ $restaurant->address }}" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-5 text-sm font-bold text-[#2C3E3F] focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 px-2">Ligne téléphonique</label>
                            <input type="text" name="phone" value="{{ $restaurant->phone }}" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-5 text-sm font-bold text-[#2C3E3F] focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 px-2">Logo</label>
                            <div class="relative group cursor-pointer">
                                <div class="w-full aspect-square bg-gray-50 rounded-[2rem] border-2 border-dashed border-gray-200 flex flex-col items-center justify-center group-hover:border-teal-500 transition-all overflow-hidden">
                                    @if($restaurant->logo)
                                        <img src="{{ Storage::url($restaurant->logo) }}" class="w-full h-full object-cover">
                                    @else
                                        <i data-lucide="image" class="w-8 h-8 text-gray-300 mb-2"></i>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase">Choisir</span>
                                    @endif
                                    <input type="file" name="logo" class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>
                            </div>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 px-2">Bannière</label>
                            <div class="relative group cursor-pointer">
                                <div class="w-full aspect-square bg-gray-50 rounded-[2rem] border-2 border-dashed border-gray-200 flex flex-col items-center justify-center group-hover:border-teal-500 transition-all overflow-hidden">
                                    @if($restaurant->banner)
                                        <img src="{{ Storage::url($restaurant->banner) }}" class="w-full h-full object-cover">
                                    @else
                                        <i data-lucide="layout-template" class="w-8 h-8 text-gray-300 mb-2"></i>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase">Choisir</span>
                                    @endif
                                    <input type="file" name="banner" class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-teal-50 rounded-3xl border border-teal-100 text-center relative overflow-hidden group">
                    <p class="text-[10px] font-bold text-teal-600 uppercase tracking-widest mb-4">QR Code du Restaurant</p>
                    <div class="bg-white w-32 h-32 mx-auto mb-4 rounded-2xl flex items-center justify-center border border-teal-100 shadow-sm overflow-hidden relative">
                        @if($restaurant->qr_code)
                            <img src="{{ Storage::url($restaurant->qr_code) }}" class="w-full h-full object-cover">
                        @else
                            <i data-lucide="qr-code" class="w-12 h-12 text-teal-600 mb-2"></i>
                            <span class="text-[8px] font-black text-teal-600 uppercase tracking-tighter">Pas de QR</span>
                        @endif
                        <input type="file" name="qr_code" class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                    @if($restaurant->qr_code)
                        <a href="{{ Storage::url($restaurant->qr_code) }}" download class="text-teal-600 font-extrabold text-xs uppercase tracking-widest hover:underline flex items-center justify-center gap-2">
                            <i data-lucide="download" class="w-3 h-3"></i> Télécharger
                        </a>
                    @else
                        <p class="text-[8px] text-gray-400 font-bold uppercase italic">Cliquez sur le QR pour en ajouter un</p>
                    @endif
                </div>

                <button type="submit" class="w-full bg-[#2C3E3F] text-white py-6 rounded-[2rem] font-black text-sm uppercase tracking-[0.2em] shadow-2xl shadow-[#2C3E3F]/30 hover:scale-[1.01] transition-all">
                    Enregistrer les modifications
                </button>
            </form>
        </section>
    </main>

    <script>
        lucide.createIcons();
        
        // Handle Active State & Smooth Scroll
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        const sections = [
            document.getElementById('dashboard'),
            document.getElementById('orders'),
            document.getElementById('menu'),
            document.getElementById('reviews'),
            document.getElementById('profile')
        ].filter(el => el !== null);

        sidebarLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                const href = link.getAttribute('href');
                if (href.startsWith('#')) {
                    e.preventDefault();
                    const targetId = href.substring(1);
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }

                    sidebarLinks.forEach(l => l.classList.remove('active'));
                    link.classList.add('active');
                }
            });
        });

        // Update active link on scroll
        window.addEventListener('scroll', () => {
            let current = "";
            sections.forEach((section) => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 150) {
                    current = section.getAttribute("id");
                }
            });

            sidebarLinks.forEach((link) => {
                link.classList.remove("active");
                if (link.getAttribute("href") === `#${current}`) {
                    link.classList.add("active");
                }
            });
        });

        // Auto-hide alerts after 5 seconds
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