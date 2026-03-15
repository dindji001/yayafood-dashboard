<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Digital - YayaFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; }
        .sidebar-link.active { background-color: #2C3E3F; color: white; box-shadow: 0 10px 15px -3px rgba(44, 62, 63, 0.2); }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(241, 245, 249, 1); }
        .dish-card { transition: all 0.3s ease; }
        .dish-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
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
            <a href="{{ route('restaurant.menu.index') }}" class="sidebar-link active flex items-center gap-4 p-4 rounded-2xl font-bold transition-all group">
                <i data-lucide="book-open" class="w-6 h-6"></i>
                <span class="hidden lg:block">Menu Digital</span>
            </a>
            <a href="{{ route('restaurant.reviews.index') }}" class="sidebar-link flex items-center gap-4 p-4 rounded-2xl font-semibold text-gray-400 hover:bg-white/10 hover:text-white transition-all group">
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
        <header class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-12 gap-6">
            <div>
                <h2 class="text-4xl font-black text-[#2C3E3F] tracking-tight mb-2 uppercase">Menu Digital</h2>
                <p class="text-gray-400 font-medium italic">Gérez vos catégories et vos plats en temps réel.</p>
            </div>
            <div class="flex gap-4">
                <button onclick="openModal('createCategoryModal')" class="bg-white border border-gray-100 text-[#2C3E3F] px-8 py-4 rounded-[1.5rem] font-bold transition-all flex items-center gap-3 shadow-sm hover:shadow-md">
                    <i data-lucide="folder-plus" class="w-5 h-5"></i>
                    Catégorie
                </button>
                <button onclick="openModal('createDishModal')" class="bg-[#2C3E3F] text-white px-8 py-4 rounded-[1.5rem] font-bold transition-all flex items-center gap-3 shadow-lg shadow-[#2C3E3F]/10 hover:bg-orange-500">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    Nouveau Plat
                </button>
            </div>
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

        @foreach($restaurant->categories as $category)
            <section class="mb-16">
                <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-[#2C3E3F]/5 rounded-2xl flex items-center justify-center text-[#2C3E3F]">
                            <i data-lucide="folder" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-2xl font-black text-[#2C3E3F] uppercase tracking-tight">{{ $category->name }}</h3>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">({{ count($category->dishes) }} plats)</span>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="openEditCategoryModal('{{ $category->id }}', '{{ $category->name }}')" class="p-3 text-gray-400 hover:text-orange-500 transition-all">
                            <i data-lucide="edit-3" class="w-5 h-5"></i>
                        </button>
                        <form action="{{ route('restaurant.categories.delete', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cette catégorie et tous ses plats ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-3 text-gray-400 hover:text-red-500 transition-all">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    @foreach($category->dishes as $dish)
                        <div class="glass-card rounded-[2.5rem] p-8 dish-card relative overflow-hidden group">
                            @if(!$dish->is_available)
                                <div class="absolute inset-0 bg-white/60 backdrop-blur-[2px] z-10 flex items-center justify-center">
                                    <span class="bg-red-500 text-white px-6 py-2 rounded-full font-black text-[10px] uppercase tracking-widest shadow-xl shadow-red-500/20">Indisponible</span>
                                </div>
                            @endif

                            <div class="flex justify-between items-start mb-6">
                                <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center text-[#2C3E3F] font-black text-xl border border-gray-100">
                                    {{ substr($dish->name, 0, 1) }}
                                </div>
                                <div class="flex gap-1">
                                    <button onclick="openEditDishModal('{{ $dish->id }}', '{{ $dish->name }}', '{{ $dish->price }}', '{{ $dish->description }}', '{{ $category->id }}')" class="p-2 text-gray-300 hover:text-orange-500 transition-all">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </button>
                                    <form action="{{ route('restaurant.dishes.toggle', $dish->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-2 text-gray-300 hover:text-teal-500 transition-all">
                                            <i data-lucide="{{ $dish->is_available ? 'eye' : 'eye-off' }}" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <h4 class="text-xl font-extrabold text-[#2C3E3F] mb-2">{{ $dish->name }}</h4>
                            <p class="text-xs text-gray-400 font-medium mb-6 line-clamp-2 h-8">{{ $dish->description ?: 'Aucune description' }}</p>

                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-50">
                                <span class="text-2xl font-black text-[#2C3E3F]">{{ number_format($dish->price, 0, ',', ' ') }} <span class="text-xs font-bold text-orange-500 uppercase">CFA</span></span>
                                <form action="{{ route('restaurant.dishes.delete', $dish->id) }}" method="POST" onsubmit="return confirm('Supprimer ce plat ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 transition-all font-bold text-[10px] uppercase tracking-widest">Supprimer</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    </main>

    <!-- Modal Create Category -->
    <div id="createCategoryModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-[#0F172A]/40 backdrop-blur-sm transition-opacity" onclick="closeModal('createCategoryModal')"></div>
            <div class="relative bg-white rounded-[2.5rem] w-full max-w-md p-10 shadow-2xl border border-gray-100 overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-teal-500 to-orange-500"></div>
                <h3 class="text-2xl font-black text-[#2C3E3F] mb-8 uppercase tracking-tight">Nouvelle Catégorie</h3>
                <form action="{{ route('restaurant.categories.create') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-[#2C3E3F] ml-2">Nom du menu</label>
                        <input type="text" name="name" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all font-semibold" placeholder="ex: Burgers, Boissons...">
                    </div>
                    <button type="submit" class="w-full bg-[#2C3E3F] text-white py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-orange-500 transition-all shadow-lg">Créer le menu</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Create Dish -->
    <div id="createDishModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-[#0F172A]/40 backdrop-blur-sm transition-opacity" onclick="closeModal('createDishModal')"></div>
            <div class="relative bg-white rounded-[2.5rem] w-full max-w-xl p-10 shadow-2xl border border-gray-100 overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-teal-500 to-orange-500"></div>
                <h3 class="text-2xl font-black text-[#2C3E3F] mb-8 uppercase tracking-tight">Ajouter un Plat</h3>
                <form action="{{ route('restaurant.dishes.create') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2 col-span-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-[#2C3E3F] ml-2">Catégorie</label>
                            <select name="category_id" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all font-semibold">
                                @foreach($restaurant->categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-[#2C3E3F] ml-2">Nom du plat</label>
                            <input type="text" name="name" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all font-semibold">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-[#2C3E3F] ml-2">Prix (CFA)</label>
                            <input type="number" name="price" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all font-semibold">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-[#2C3E3F] ml-2">Description</label>
                        <textarea name="description" rows="3" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all font-semibold resize-none"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-[#2C3E3F] text-white py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-orange-500 transition-all shadow-lg">Ajouter au menu</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.body.style.overflow = 'auto'; }
    </script>
</body>
</html>
