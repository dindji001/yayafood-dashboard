<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Restaurants - Admin YayaFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(241, 245, 249, 1); }
    </style>
</head>
<body class="flex min-h-screen">
    <!-- Sidebar (Reprise du design existant) -->
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
            <a href="{{ route('admin.restaurants.index') }}" class="bg-[#2C3E3F]/10 text-[#2C3E3F] border-r-4 border-[#2C3E3F] flex items-center gap-3 p-4 rounded-2xl font-bold transition-all">
                <i data-lucide="store" class="w-5 h-5"></i>
                Liste des Restaurants
            </a>
            <a href="{{ route('dashboard') }}#users" class="flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all">
                <i data-lucide="users" class="w-5 h-5"></i>
                Utilisateurs
            </a>
        </nav>
    </aside>

    <main class="flex-1 ml-72 p-10">
        <header class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-4xl font-extrabold text-[#2C3E3F] tracking-tight mb-2">ÉCOSYSTÈME RESTAURANTS</h2>
                <p class="text-gray-400 font-medium">Analyse et performance de vos partenaires.</p>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            @foreach($restaurants as $r)
                <div class="glass-card rounded-[2.5rem] p-8 hover:shadow-2xl transition-all group">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-16 h-16 bg-[#F1F5F4] rounded-2xl flex items-center justify-center text-[#2C3E3F] font-black text-2xl border border-gray-100 shadow-sm">
                            {{ substr($r->name, 0, 1) }}
                        </div>
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $r->is_active ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                            {{ $r->is_active ? 'Opérationnel' : 'Suspendu' }}
                        </span>
                    </div>

                    <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-2">{{ $r->name }}</h3>
                    <p class="text-xs text-gray-400 font-medium mb-6 flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-3 h-3"></i> {{ Str::limit($r->address, 30) }}
                    </p>

                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="bg-gray-50 p-4 rounded-2xl text-center">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Commandes</p>
                            <p class="text-lg font-black text-[#2C3E3F]">{{ $r->orders_count }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-2xl text-center">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Note Moy.</p>
                            <p class="text-lg font-black text-orange-500">{{ number_format($r->averageRating(), 1) }} ★</p>
                        </div>
                    </div>

                    <a href="{{ route('admin.restaurants.show', $r->id) }}" class="w-full bg-[#2C3E3F] text-white py-4 rounded-2xl font-extrabold text-xs uppercase tracking-[0.2em] flex items-center justify-center gap-2 group-hover:bg-orange-500 transition-all">
                        Voir les statistiques <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            @endforeach
        </div>
    </main>

    <script>lucide.createIcons();</script>
</body>
</html>