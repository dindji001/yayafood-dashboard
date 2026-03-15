<div><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Dashboard - YayaFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F1F5F4; }
    </style>
</head>
<body class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-[#2C3E3F] text-white p-6">
        <h1 class="text-2xl font-extrabold mb-10 tracking-tighter">YayaFood<span class="text-teal-400">.</span></h1>
        <nav class="space-y-4">
            <a href="#" class="block p-3 bg-teal-500/10 text-teal-400 rounded-xl font-bold border border-teal-500/20">Tableau de bord</a>
            <a href="#" class="block p-3 hover:bg-white/5 rounded-xl transition font-semibold">Menu Digital</a>
            <a href="#" class="block p-3 hover:bg-white/5 rounded-xl transition font-semibold">Commandes Direct</a>
            <a href="#" class="block p-3 hover:bg-white/5 rounded-xl transition font-semibold">Avis Clients</a>
            <a href="#" class="block p-3 hover:bg-white/5 rounded-xl transition font-semibold">Profil Restaurant</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6 font-bold">
                {{ session('success') }}
            </div>
        @endif

        <header class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-3xl font-extrabold text-[#2C3E3F]">{{ $restaurant->name }}</h2>
                <p class="text-gray-500 font-medium">Gestion de votre restaurant en temps réel.</p>
            </div>
            <div class="flex items-center gap-4">
                <span class="bg-green-100 text-green-600 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider">Restaurant Ouvert</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-500 font-bold hover:text-red-500">Déconnexion</button>
                </form>
                <div class="bg-white p-2 rounded-full shadow-sm border border-gray-100">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ Auth::user()->name }}" class="w-10 h-10 rounded-full">
                </div>
            </div>
        </header>

        <!-- Stats Grid -->
        <div class="grid grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Commandes Aujourd'hui</p>
                <h3 class="text-4xl font-extrabold text-[#2C3E3F]">{{ $stats['orders_today'] }}</h3>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Chiffre d'Affaire (CFA)</p>
                <h3 class="text-4xl font-extrabold text-[#2C3E3F]">{{ number_format($stats['revenue_today'], 0, ',', ' ') }}</h3>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Note Moyenne</p>
                <h3 class="text-4xl font-extrabold text-[#2C3E3F]">{{ number_format($stats['average_rating'], 1) }} <span class="text-lg text-amber-400">★</span></h3>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-10">
            <!-- Form: Update Restaurant Info -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-6">Informations du Restaurant</h3>
                <form action="{{ route('restaurant.info.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nom du Restaurant</label>
                        <input type="text" name="name" value="{{ $restaurant->name }}" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Logo (Carré)</label>
                            <input type="file" name="logo" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 text-xs focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Bannière (Fond d'écran)</label>
                            <input type="file" name="banner" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 text-xs focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Adresse</label>
                        <input type="text" name="address" value="{{ $restaurant->address }}" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Téléphone</label>
                        <input type="text" name="phone" value="{{ $restaurant->phone }}" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <button type="submit" class="w-full bg-[#2C3E3F] text-white py-4 rounded-2xl font-bold hover:bg-[#1A2829] transition">Mettre à jour les infos</button>
                </form>
            </div>

            <!-- Menu Management -->
            <div class="space-y-8">
                <!-- Form: Create Category -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-6">Ajouter une Catégorie</h3>
                    <form action="{{ route('restaurant.categories.create') }}" method="POST" class="flex gap-4">
                        @csrf
                        <input type="text" name="name" placeholder="Ex: Entrées, Boissons..." required class="flex-1 bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                        <button type="submit" class="bg-teal-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-teal-700 transition">Ajouter</button>
                    </form>
                </div>

                <!-- Form: Create Dish -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-6">Ajouter un Plat au Menu</h3>
                    <form action="{{ route('restaurant.dishes.create') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Catégorie</label>
                            <select name="category_id" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                                @foreach($restaurant->categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nom du Plat</label>
                                <input type="text" name="name" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Prix (CFA)</label>
                                <input type="number" name="price" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-[#2C3E3F] text-white py-4 rounded-2xl font-bold hover:bg-[#1A2829] transition">Ajouter au Menu</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Section: Live Orders -->
        <div class="grid grid-cols-2 gap-8">
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-6">Commandes en cours</h3>
                <div class="space-y-4">
                    @forelse($liveOrders as $order)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="flex items-center gap-4">
                            <div class="bg-[#2C3E3F] text-white w-12 h-12 flex items-center justify-center rounded-xl font-bold">#{{ $order->id }}</div>
                            <div>
                                <p class="font-extrabold text-[#2C3E3F]">Table {{ $order->table_number ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 font-bold">
                                    @foreach($order->items as $item)
                                        {{ $item->quantity }}x {{ $item->dish->name }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="bg-amber-100 text-amber-600 px-3 py-1 rounded-lg text-xs font-bold uppercase">{{ $order->status }}</span>
                            <button class="bg-teal-600 text-white px-4 py-2 rounded-xl text-xs font-bold">Mettre à jour</button>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-400 font-bold py-10">Aucune commande en cours.</p>
                    @endforelse
                </div>
            </div>

            <!-- Section: Reviews -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-6">Avis Clients</h3>
                <div class="space-y-6 overflow-y-auto max-h-[400px] pr-2">
                    @forelse($reviews as $review)
                    <div class="border-b border-gray-50 pb-4 last:border-0">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex items-center gap-3">
                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $review->user->name }}" class="w-8 h-8 rounded-full bg-gray-100">
                                <div>
                                    <p class="font-extrabold text-sm text-[#2C3E3F]">{{ $review->user->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 bg-amber-50 px-2 py-1 rounded-lg">
                                <span class="text-xs font-black text-amber-600">{{ $review->rating }}</span>
                                <span class="text-amber-400 text-xs">★</span>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed font-medium italic mb-3">"{{ $review->comment }}"</p>
                        
                        @if($review->reply)
                            <div class="bg-gray-50 p-4 rounded-2xl border-l-4 border-teal-500 ml-4">
                                <p class="text-[10px] text-teal-600 font-bold uppercase tracking-widest mb-1">Votre réponse</p>
                                <p class="text-gray-700 text-xs font-semibold">{{ $review->reply }}</p>
                            </div>
                        @else
                            <form action="{{ route('restaurant.reviews.reply', $review->id) }}" method="POST" class="ml-4 mt-2">
                                @csrf
                                <div class="flex gap-2">
                                    <input type="text" name="reply" placeholder="Répondre à ce client..." required class="flex-1 bg-gray-50 border border-gray-100 rounded-xl px-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                                    <button type="submit" class="bg-[#2C3E3F] text-white px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-wider">Répondre</button>
                                </div>
                            </form>
                        @endif
                    </div>
                    @empty
                    <p class="text-center text-gray-400 font-bold py-10">Aucun avis pour le moment.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</body>
</html>
    <!-- Very little is needed to make a happy life. - Marcus Aurelius -->
</div>
