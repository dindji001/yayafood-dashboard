<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard - YayaFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; }
        .sidebar-link.active { background-color: rgba(44, 62, 63, 0.1); color: #2C3E3F; border-right: 4px solid #2C3E3F; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(241, 245, 249, 1); }
        .btn-primary { background-color: #2C3E3F; transition: all 0.3s ease; }
        .btn-primary:hover { background-color: #1A2829; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(44, 62, 63, 0.2); }
        .status-badge { font-size: 0.65rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; }
    </style>
</head>
<body class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-72 bg-white border-r border-gray-100 flex flex-col fixed h-full">
        <div class="p-8">
            <h1 class="text-2xl font-extrabold tracking-tighter flex items-center gap-2 text-[#2C3E3F]">
                <div class="w-10 h-10 bg-[#2C3E3F] rounded-xl flex items-center justify-center text-white">
                    <i data-lucide="utensils-crosses" class="w-6 h-6"></i>
                </div>
                YayaFood<span class="text-orange-500">.</span>
            </h1>
        </div>
        
        <nav class="flex-1 px-4 space-y-2 mt-4">
            <a href="#dashboard" class="sidebar-link active flex items-center gap-3 p-4 rounded-2xl font-bold transition-all">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Tableau de bord
            </a>
            <a href="#restaurants" class="sidebar-link flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all">
                <i data-lucide="store" class="w-5 h-5"></i>
                Restaurants
            </a>
            <a href="#users" class="sidebar-link flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all">
                <i data-lucide="users" class="w-5 h-5"></i>
                Utilisateurs
            </a>
            <a href="#" class="sidebar-link flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all">
                <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                Rapports Financiers
            </a>
        </nav>

        <div class="p-6 border-t border-gray-50 mt-auto">
            <div class="bg-gray-50 p-4 rounded-2xl flex items-center gap-3">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ Auth::user()->name }}" class="w-10 h-10 rounded-xl bg-white p-1">
                <div class="overflow-hidden">
                    <p class="text-xs font-extrabold text-[#2C3E3F] truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Super Admin</p>
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
            <div class="bg-[#059669]/10 border border-[#059669]/20 text-[#059669] px-6 py-4 rounded-2xl mb-8 flex items-center gap-3 animate-bounce">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <header class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-4xl font-extrabold text-[#2C3E3F] tracking-tight mb-2 uppercase">Vue d'ensemble</h2>
                <p class="text-gray-400 font-medium">Gestion globale de l'écosystème YayaFood.</p>
            </div>
            <div class="flex gap-4">
                <button class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all text-[#2C3E3F]">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                </button>
                <button class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all text-[#2C3E3F]">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                </button>
            </div>
        </header>

        <!-- Stats Grid -->
        <div id="dashboard" class="grid grid-cols-4 gap-8 mb-12">
            <div class="glass-card p-8 rounded-[2rem] shadow-sm relative overflow-hidden group hover:shadow-xl transition-all">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform">
                    <i data-lucide="store" class="w-32 h-32 text-[#2C3E3F]"></i>
                </div>
                <div class="w-12 h-12 bg-[#2C3E3F]/5 rounded-2xl flex items-center justify-center text-[#2C3E3F] mb-6">
                    <i data-lucide="store" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Restaurants</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-extrabold text-[#2C3E3F]">{{ $stats['restaurants_count'] }}</h3>
                    <span class="text-xs font-bold text-green-500 flex items-center"><i data-lucide="arrow-up-right" class="w-3 h-3"></i> +2</span>
                </div>
            </div>
            <div class="glass-card p-8 rounded-[2rem] shadow-sm relative overflow-hidden group hover:shadow-xl transition-all">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform text-[#2C3E3F]">
                    <i data-lucide="users" class="w-32 h-32"></i>
                </div>
                <div class="w-12 h-12 bg-blue-500/5 rounded-2xl flex items-center justify-center text-blue-500 mb-6">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Clients Actifs</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-extrabold text-[#2C3E3F]">{{ $stats['users_count'] }}</h3>
                    <span class="text-xs font-bold text-green-500 flex items-center"><i data-lucide="arrow-up-right" class="w-3 h-3"></i> +12%</span>
                </div>
            </div>
            <div class="glass-card p-8 rounded-[2rem] shadow-sm relative overflow-hidden group hover:shadow-xl transition-all text-[#2C3E3F]">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform">
                    <i data-lucide="shopping-bag" class="w-32 h-32"></i>
                </div>
                <div class="w-12 h-12 bg-orange-500/5 rounded-2xl flex items-center justify-center text-orange-500 mb-6">
                    <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Commandes</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-extrabold text-[#2C3E3F]">{{ $stats['orders_count'] }}</h3>
                    <span class="text-xs font-bold text-orange-500 flex items-center"><i data-lucide="arrow-right" class="w-3 h-3"></i> 0%</span>
                </div>
            </div>
            <div class="glass-card p-8 rounded-[2rem] shadow-sm relative overflow-hidden group hover:shadow-xl transition-all">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform text-[#2C3E3F]">
                    <i data-lucide="wallet" class="w-32 h-32"></i>
                </div>
                <div class="w-12 h-12 bg-green-500/5 rounded-2xl flex items-center justify-center text-green-600 mb-6">
                    <i data-lucide="wallet" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Chiffre d'affaires</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-extrabold text-[#2C3E3F]">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} <span class="text-xs">CFA</span></h3>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-10">
            <!-- Left Side: Forms -->
            <div class="col-span-4 space-y-8">
                <!-- Create Restaurant -->
                <div class="glass-card rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="plus" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-xl font-extrabold text-[#2C3E3F]">Nouveau Restaurant</h3>
                    </div>
                    <form action="{{ route('admin.restaurants.create') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Identité de l'établissement</label>
                                <input type="text" name="name" placeholder="Nom du restaurant" required class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 text-sm font-semibold focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Localisation</label>
                                <div class="relative">
                                    <i data-lucide="map-pin" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                                    <input type="text" name="address" placeholder="Adresse complète" class="w-full bg-gray-50 border-none rounded-2xl pl-12 pr-5 py-4 text-sm font-semibold focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn-primary w-full text-white py-5 rounded-[1.5rem] font-extrabold text-sm shadow-lg shadow-[#2C3E3F]/10 flex items-center justify-center gap-2">
                            Enregistrer le restaurant
                        </button>
                    </form>
                </div>

                <!-- Create Manager -->
                <div class="glass-card rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="user-plus" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-xl font-extrabold text-[#2C3E3F]">Accès Restaurateur</h3>
                    </div>
                    <form action="{{ route('admin.users.create') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-4">
                            <input type="text" name="name" placeholder="Nom du gérant" required class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 text-sm font-semibold focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none">
                            <input type="email" name="email" placeholder="Email professionnel" required class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 text-sm font-semibold focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none">
                            <input type="password" name="password" placeholder="Mot de passe" required class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 text-sm font-semibold focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none">
                            <div>
                                <label class="block text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Assignation</label>
                                <select name="restaurant_id" required class="w-full bg-gray-50 border-none rounded-2xl px-5 py-4 text-sm font-semibold focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none appearance-none">
                                    @foreach($restaurants as $r)
                                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-orange-500 text-white py-5 rounded-[1.5rem] font-extrabold text-sm shadow-lg shadow-orange-500/20 hover:bg-orange-600 transition-all flex items-center justify-center gap-2">
                            Créer l'accès manager
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Side: Data Tables -->
            <div class="col-span-8 space-y-10">
                <!-- Restaurants Section -->
                <div id="restaurants" class="glass-card rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-extrabold text-[#2C3E3F]">Écosystème Restaurants</h3>
                            <p class="text-xs font-medium text-gray-400">Liste exhaustive des établissements partenaires</p>
                        </div>
                        <div class="relative w-64">
                            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                            <input type="text" onkeyup="filterTable('restaurant-table', this.value)" placeholder="Rechercher..." class="w-full bg-gray-50 border-none rounded-xl pl-10 pr-4 py-2 text-xs font-semibold focus:ring-1 focus:ring-[#2C3E3F]/10 outline-none">
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="restaurant-table" class="w-full text-left">
                            <thead class="bg-gray-50/50">
                                <tr class="text-gray-400 text-[10px] font-extrabold uppercase tracking-[0.15em]">
                                    <th class="px-8 py-5">Établissement</th>
                                    <th class="px-8 py-5">Contact</th>
                                    <th class="px-8 py-5">Activités</th>
                                    <th class="px-8 py-5">Statut</th>
                                    <th class="px-8 py-5 text-right">Gestion</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($restaurants as $r)
                                    <tr class="hover:bg-gray-50/30 transition-all group">
                                        <td class="px-8 py-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-[#2C3E3F]/5 rounded-xl flex items-center justify-center text-[#2C3E3F] font-bold">
                                                    {{ substr($r->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-extrabold text-[#2C3E3F]">{{ $r->name }}</p>
                                                    <p class="text-[10px] text-gray-400 font-medium italic">{{ $r->address ?: 'Sans adresse' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <p class="text-xs font-bold text-gray-500">{{ $r->phone ?: '---' }}</p>
                                        </td>
                                        <td class="px-8 py-6 text-center">
                                            <span class="text-xs font-extrabold text-[#2C3E3F] bg-gray-100 px-3 py-1 rounded-lg">{{ $r->orders_count }} cmd.</span>
                                        </td>
                                        <td class="px-8 py-6">
                                            <form action="{{ route('admin.restaurants.toggle', $r->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="status-badge px-3 py-1 rounded-full {{ $r->is_active ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                                    {{ $r->is_active ? 'Opérationnel' : 'Suspendu' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <form action="{{ route('admin.restaurants.delete', $r->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Attention : suppression irréversible !')" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Users Section -->
                <div id="users" class="glass-card rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-extrabold text-[#2C3E3F]">Comptes & Permissions</h3>
                            <p class="text-xs font-medium text-gray-400">Gérants et clients enregistrés</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table id="users-table" class="w-full text-left">
                            <thead class="bg-gray-50/50">
                                <tr class="text-gray-400 text-[10px] font-extrabold uppercase tracking-[0.15em]">
                                    <th class="px-8 py-5">Identité</th>
                                    <th class="px-8 py-5">Rôle</th>
                                    <th class="px-8 py-5">Liaison Restaurant</th>
                                    <th class="px-8 py-5 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($users as $u)
                                    <tr class="hover:bg-gray-50/30 transition-all group">
                                        <td class="px-8 py-6">
                                            <div class="flex items-center gap-3">
                                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $u->name }}" class="w-8 h-8 rounded-lg bg-gray-50">
                                                <div>
                                                    <p class="text-sm font-extrabold text-[#2C3E3F]">{{ $u->name }}</p>
                                                    <p class="text-[10px] text-gray-400 font-medium">{{ $u->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <span class="status-badge px-3 py-1 rounded-full {{ $u->role === 'restaurant' ? 'bg-indigo-100 text-indigo-600' : 'bg-blue-100 text-blue-600' }}">
                                                {{ $u->role }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6">
                                            @if($u->restaurant)
                                                <div class="flex items-center gap-2 text-xs font-bold text-gray-500">
                                                    <i data-lucide="link" class="w-3 h-3"></i>
                                                    {{ $u->restaurant->name }}
                                                </div>
                                            @else
                                                <span class="text-[10px] text-gray-300 font-bold uppercase tracking-widest italic">Aucune liaison</span>
                                            @endif
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <form action="{{ route('admin.users.delete', $u->id) }}" method="POST" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                                @csrf @method('DELETE')
                                                <button type="submit" onclick="return confirm('Révoquer cet utilisateur ?')" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                                    <i data-lucide="user-x" class="w-4 h-4"></i>
                                                </button>
                                            </form>
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

    <script>
        lucide.createIcons();

        function filterTable(tableId, query) {
            const table = document.getElementById(tableId);
            const rows = table.getElementsByTagName('tr');
            const lowerQuery = query.toLowerCase();

            for (let i = 1; i < rows.length; i++) {
                const text = rows[i].textContent.toLowerCase();
                rows[i].style.display = text.includes(lowerQuery) ? '' : 'none';
            }
        }
    </script>
</body>
</html>