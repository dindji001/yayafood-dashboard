<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Utilisateurs - Admin YayaFood</title>
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
        <header class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-4xl font-extrabold text-[#2C3E3F] tracking-tight mb-2 uppercase">CLIENTS & UTILISATEURS</h2>
                <p class="text-gray-400 font-medium">Gérez vos clients et analysez leurs habitudes de consommation.</p>
            </div>
        </header>

        <div class="glass-card rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                <h3 class="text-xl font-extrabold text-[#2C3E3F]">Base Clientèle</h3>
                <div class="relative w-64">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                    <input type="text" id="userSearch" placeholder="Rechercher un client..." class="w-full bg-gray-50 border-none rounded-xl pl-10 pr-4 py-2 text-xs font-semibold focus:ring-1 focus:ring-[#2C3E3F]/10 outline-none">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table id="users-table" class="w-full text-left">
                    <thead class="bg-gray-50/50">
                        <tr class="text-gray-400 text-[10px] font-extrabold uppercase tracking-[0.15em]">
                            <th class="px-8 py-5">Identité</th>
                            <th class="px-8 py-5">Email</th>
                            <th class="px-8 py-5 text-center">Commandes</th>
                            <th class="px-8 py-5 text-center">Avis Donnés</th>
                            <th class="px-8 py-5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($users as $u)
                            <tr class="hover:bg-gray-50/30 transition-all group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $u->name }}" class="w-10 h-10 rounded-xl bg-gray-100 border border-gray-50">
                                        <div>
                                            <p class="text-sm font-extrabold text-[#2C3E3F]">{{ $u->name }}</p>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Inscrit {{ $u->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-xs font-bold text-gray-500">{{ $u->email }}</p>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="text-xs font-extrabold text-[#2C3E3F] bg-gray-100 px-3 py-1 rounded-lg">{{ $u->orders_count }} cmd.</span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="text-xs font-extrabold text-orange-500 bg-orange-50 px-3 py-1 rounded-lg">{{ $u->reviews_count }} avis</span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <a href="{{ route('admin.users.show', $u->id) }}" class="inline-flex items-center gap-2 p-2 px-4 bg-[#2C3E3F] text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-orange-500 transition-all shadow-lg shadow-[#2C3E3F]/10 group-hover:scale-105">
                                        Voir Profil <i data-lucide="chevron-right" class="w-3 h-3"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();
        document.getElementById('userSearch').addEventListener('keyup', function() {
            const query = this.value.toLowerCase();
            const rows = document.querySelectorAll('#users-table tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    </script>
</body>
</html>