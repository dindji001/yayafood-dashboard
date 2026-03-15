<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avis Globaux - Admin YayaFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; }
        .sidebar-link.active { background-color: rgba(44, 62, 63, 0.1); color: #2C3E3F; border-right: 4px solid #2C3E3F; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(241, 245, 249, 1); }
        .rating-star { color: #F59E0B; }
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
            <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Tableau de bord
            </a>
            <a href="{{ route('admin.restaurants.index') }}" class="sidebar-link flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all">
                <i data-lucide="store" class="w-5 h-5"></i>
                Restaurants
            </a>
            <a href="{{ route('admin.orders.index') }}" class="sidebar-link flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                Commandes
            </a>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all">
                <i data-lucide="users" class="w-5 h-5"></i>
                Clients
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="sidebar-link active flex items-center gap-3 p-4 rounded-2xl font-bold transition-all">
                <i data-lucide="star" class="w-5 h-5"></i>
                Avis
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-72 p-10">
        <header class="mb-12">
            <h2 class="text-4xl font-black text-[#2C3E3F] tracking-tight mb-2 uppercase">Avis Globaux</h2>
            <p class="text-gray-400 font-medium italic">Réputation globale des établissements partenaires.</p>
        </header>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            @foreach($reviews as $review)
                <div class="glass-card rounded-[2.5rem] p-8 relative overflow-hidden flex flex-col h-full">
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex items-center gap-4">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $review->user->name }}" class="w-12 h-12 rounded-2xl bg-gray-100 p-1">
                            <div>
                                <h4 class="font-black text-[#2C3E3F]">{{ $review->user->name }}</h4>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <div class="flex items-center gap-1 bg-orange-50 px-3 py-1.5 rounded-full mb-2">
                                <span class="text-orange-500 font-black text-sm">{{ $review->rating }}</span>
                                <i data-lucide="star" class="w-3.5 h-3.5 rating-star fill-current"></i>
                            </div>
                            <span class="text-[9px] font-black text-[#2C3E3F] uppercase tracking-widest italic bg-gray-50 px-2 py-1 rounded-lg border border-gray-100">{{ $review->restaurant->name }}</span>
                        </div>
                    </div>

                    <p class="text-gray-600 font-medium italic leading-relaxed mb-8">"{{ $review->comment }}"</p>

                    @if($review->reply)
                        <div class="mt-auto bg-gray-50 rounded-[1.5rem] p-6 border border-gray-100">
                            <p class="text-[9px] font-black text-orange-500 uppercase tracking-widest mb-2 flex items-center gap-2">
                                <i data-lucide="message-square" class="w-3 h-3"></i>
                                Réponse de l'établissement
                            </p>
                            <p class="text-sm text-[#2C3E3F] font-bold leading-relaxed">{{ $review->reply }}</p>
                        </div>
                    @else
                        <div class="mt-auto opacity-40 italic text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                            En attente de réponse
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if($reviews->hasPages())
            <div class="mt-12">
                {{ $reviews->links() }}
            </div>
        @endif
    </main>

    <script>lucide.createIcons();</script>
</body>
</html>
