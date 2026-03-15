<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Restaurant - YayaFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; }
        .sidebar-link.active { background-color: #2C3E3F; color: white; box-shadow: 0 10px 15px -3px rgba(44, 62, 63, 0.2); }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(241, 245, 249, 1); }
        .nav-tab.active { color: #2C3E3F; border-bottom: 3px solid #2C3E3F; }
    </style>
</head>
<body class="flex min-h-screen">
    <!-- Sidebar (Same as index) -->
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
            <a href="{{ route('restaurant.reviews.index') }}" class="sidebar-link flex items-center gap-4 p-4 rounded-2xl font-semibold text-gray-400 hover:bg-white/10 hover:text-white transition-all group">
                <i data-lucide="star" class="w-6 h-6"></i>
                <span class="hidden lg:block">Avis Clients</span>
            </a>
            <a href="{{ route('restaurant.settings.index') }}" class="sidebar-link active flex items-center gap-4 p-4 rounded-2xl font-bold transition-all group">
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
            <h2 class="text-4xl font-black text-[#2C3E3F] tracking-tight mb-2 uppercase">Configuration</h2>
            <p class="text-gray-400 font-medium italic">Gérez les informations de votre établissement.</p>
        </header>

        <!-- Settings Tabs Navigation -->
        <div class="flex items-center gap-8 border-b border-gray-200 mb-12">
            <a href="{{ route('restaurant.settings.profile') }}" class="nav-tab active px-4 py-4 font-bold text-sm uppercase tracking-widest transition-all">Profil Restaurant</a>
            <a href="{{ route('restaurant.settings.hours') }}" class="nav-tab px-4 py-4 font-bold text-sm uppercase tracking-widest text-gray-400 hover:text-[#2C3E3F] transition-all">Horaires</a>
            <a href="{{ route('restaurant.settings.services') }}" class="nav-tab px-4 py-4 font-bold text-sm uppercase tracking-widest text-gray-400 hover:text-[#2C3E3F] transition-all">Services & QR</a>
        </div>

        <div class="max-w-4xl">
            <div class="glass-card rounded-[2.5rem] p-10 shadow-sm border border-gray-100 mb-8">
                <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-8 flex items-center gap-3">
                    <i data-lucide="user" class="w-6 h-6 text-[#2C3E3F]"></i>
                    Informations Générales
                </h3>

                <form action="{{ route('restaurant.info.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-2">Nom commercial</label>
                        <input type="text" name="name" value="{{ $restaurant->name }}" required class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-[#2C3E3F] focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-2">Description</label>
                        <textarea name="description" rows="4" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-[#2C3E3F] focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none" placeholder="Décrivez votre restaurant...">{{ $restaurant->description }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-2">Adresse</label>
                            <input type="text" name="address" value="{{ $restaurant->address }}" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-[#2C3E3F] focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-2">Téléphone</label>
                            <input type="text" name="phone" value="{{ $restaurant->phone }}" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-[#2C3E3F] focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-2">Logo de l'établissement</label>
                            <div class="relative group h-40 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center overflow-hidden transition-all hover:border-[#2C3E3F]">
                                @if($restaurant->logo)
                                    <img src="{{ $restaurant->logo_url }}" class="w-full h-full object-cover">
                                @else
                                    <i data-lucide="image" class="w-8 h-8 text-gray-300 mb-2"></i>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">Choisir un logo</span>
                                @endif
                                <input type="file" name="logo" class="absolute inset-0 opacity-0 cursor-pointer">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-2">Image de couverture</label>
                            <div class="relative group h-40 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center overflow-hidden transition-all hover:border-[#2C3E3F]">
                                @if($restaurant->banner)
                                    <img src="{{ $restaurant->banner_url }}" class="w-full h-full object-cover">
                                @else
                                    <i data-lucide="layout-template" class="w-8 h-8 text-gray-300 mb-2"></i>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">Choisir une bannière</span>
                                @endif
                                <input type="file" name="banner" class="absolute inset-0 opacity-0 cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#2C3E3F] text-white py-5 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-[#1A2829] transition-all shadow-lg shadow-[#2C3E3F]/20">
                        Sauvegarder les modifications
                    </button>
                </form>
            </div>

            <!-- Danger Zone -->
            <div class="glass-card rounded-[2.5rem] p-10 shadow-sm border border-red-100 bg-red-50/30">
                <h3 class="text-xl font-extrabold text-red-600 mb-4 flex items-center gap-3">
                    <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                    Zone de Danger
                </h3>
                <p class="text-xs text-gray-500 mb-6 leading-relaxed font-medium">
                    Si vous souhaitez cesser votre activité sur YayaFood, vous pouvez envoyer une demande de suppression de compte au Super Admin. Cette action est irréversible.
                </p>
                <form action="{{ route('restaurant.settings.delete-request') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <textarea name="reason" placeholder="Raison de la suppression (facultatif)..." rows="2" class="w-full bg-white border border-red-100 rounded-2xl px-6 py-4 text-sm font-medium text-gray-600 focus:ring-2 focus:ring-red-500/10 outline-none"></textarea>
                    </div>
                    <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir envoyer une demande de suppression ?')" class="w-full bg-red-500 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-red-600 transition-all shadow-lg shadow-red-500/20">
                        Demander la suppression du compte
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
