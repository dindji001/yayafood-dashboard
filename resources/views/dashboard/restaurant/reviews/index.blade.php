<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avis Clients - YayaFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; }
        .sidebar-link.active { background-color: #2C3E3F; color: white; box-shadow: 0 10px 15px -3px rgba(44, 62, 63, 0.2); }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(241, 245, 249, 1); }
        .rating-star { color: #F59E0B; }
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
            <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center gap-4 p-4 rounded-2xl font-semibold text-gray-400 hover:bg-white/10 hover:text-white transition-all group">
                <i data-lucide="layout-grid" class="w-6 h-6"></i>
                <span class="hidden lg:block">Tableau de bord</span>
            </a>
            <a href="{{ route('restaurant.orders.index') }}" class="sidebar-link flex items-center gap-4 p-4 rounded-2xl font-semibold text-gray-400 hover:bg-white/10 hover:text-white transition-all group">
                <i data-lucide="bell" class="w-6 h-6"></i>
                <span class="hidden lg:block">Commandes</span>
            </a>
            <a href="{{ route('restaurant.menu.index') }}" class="sidebar-link flex items-center gap-4 p-4 rounded-2xl font-semibold text-gray-400 hover:bg-white/10 hover:text-white transition-all group">
                <i data-lucide="book-open" class="w-6 h-6"></i>
                <span class="hidden lg:block">Menu Digital</span>
            </a>
            <a href="{{ route('restaurant.reviews.index') }}" class="sidebar-link active flex items-center gap-4 p-4 rounded-2xl font-bold transition-all group">
                <i data-lucide="star" class="w-6 h-6"></i>
                <span class="hidden lg:block">Avis Clients</span>
            </a>
            <a href="{{ route('restaurant.settings.index') }}" class="sidebar-link flex items-center gap-4 p-4 rounded-2xl font-semibold text-gray-400 hover:bg-white/10 hover:text-white transition-all group">
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
        <header class="mb-12">
            <h2 class="text-4xl font-black text-[#2C3E3F] tracking-tight mb-2 uppercase">Avis Clients</h2>
            <p class="text-gray-400 font-medium italic">Ce que vos clients pensent de votre établissement.</p>
        </header>

        @if(session('success'))
            <div class="mb-8 p-6 bg-green-50 border border-green-100 rounded-[2rem] flex items-center gap-4 text-green-600 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="font-black uppercase text-[10px] tracking-widest mb-0.5">Succès</p>
                    <p class="font-bold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @forelse($reviews as $review)
                <div class="glass-card rounded-[2.5rem] p-8 relative overflow-hidden flex flex-col h-full">
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex items-center gap-4">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $review->user->name }}" class="w-12 h-12 rounded-2xl bg-gray-100 p-1">
                            <div>
                                <h4 class="font-black text-[#2C3E3F]">{{ $review->user->name }}</h4>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 bg-orange-50 px-3 py-1.5 rounded-full">
                            <span class="text-orange-500 font-black text-sm">{{ $review->rating }}</span>
                            <i data-lucide="star" class="w-3.5 h-3.5 rating-star fill-current"></i>
                        </div>
                    </div>

                    <p class="text-gray-600 font-medium italic leading-relaxed mb-8">"{{ $review->comment }}"</p>

                    <div class="mt-auto">
                        @if($review->reply)
                            <div class="bg-gray-50 rounded-[1.5rem] p-6 border border-gray-100">
                                <p class="text-[9px] font-black text-orange-500 uppercase tracking-widest mb-2 flex items-center gap-2">
                                    <i data-lucide="message-square" class="w-3 h-3"></i>
                                    Votre réponse
                                </p>
                                <p class="text-sm text-[#2C3E3F] font-bold leading-relaxed">{{ $review->reply }}</p>
                                <p class="text-[9px] text-gray-400 font-bold uppercase mt-2 italic">{{ $review->replied_at ? \Carbon\Carbon::parse($review->replied_at)->diffForHumans() : '' }}</p>
                            </div>
                        @else
                            <button onclick="document.getElementById('reply-form-{{ $review->id }}').classList.toggle('hidden')" class="text-[10px] font-black text-[#2C3E3F] uppercase tracking-widest hover:text-orange-500 transition-all flex items-center gap-2">
                                <i data-lucide="corner-up-left" class="w-4 h-4"></i>
                                Répondre à cet avis
                            </button>
                            <form id="reply-form-{{ $review->id }}" action="{{ route('restaurant.reviews.reply', $review->id) }}" method="POST" class="mt-4 hidden animate-in slide-in-from-top-2">
                                @csrf
                                <textarea name="reply" required rows="3" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all font-semibold text-sm resize-none mb-4" placeholder="Tapez votre réponse..."></textarea>
                                <button type="submit" class="bg-[#2C3E3F] text-white px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-orange-500 transition-all shadow-lg shadow-[#2C3E3F]/10">Envoyer la réponse</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="lg:col-span-2 glass-card rounded-[2.5rem] p-20 flex flex-col items-center justify-center text-center opacity-50 italic">
                    <i data-lucide="message-square-off" class="w-20 h-20 text-gray-200 mb-6"></i>
                    <h3 class="text-xl font-black text-gray-400 uppercase tracking-widest">Aucun avis pour le moment</h3>
                </div>
            @endforelse
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
