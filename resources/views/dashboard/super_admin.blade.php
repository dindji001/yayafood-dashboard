<div><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard - YayaFood</title>
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
            <a href="#" class="block p-3 hover:bg-white/5 rounded-xl transition font-semibold">Restaurants</a>
            <a href="#" class="block p-3 hover:bg-white/5 rounded-xl transition font-semibold">Utilisateurs</a>
            <a href="#" class="block p-3 hover:bg-white/5 rounded-xl transition font-semibold">Statistiques</a>
            <a href="#" class="block p-3 hover:bg-white/5 rounded-xl transition font-semibold">Paramètres</a>
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
                <h2 class="text-3xl font-extrabold text-[#2C3E3F]">Super Administrateur</h2>
                <p class="text-gray-500 font-medium">Bienvenue sur votre espace de gestion globale.</p>
            </div>
            <div class="flex items-center gap-4">
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
        <div class="grid grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Total Restaurants</p>
                <h3 class="text-4xl font-extrabold text-[#2C3E3F]">{{ $stats['restaurants_count'] }}</h3>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Clients Actifs</p>
                <h3 class="text-4xl font-extrabold text-[#2C3E3F]">{{ $stats['users_count'] }}</h3>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Total Commandes</p>
                <h3 class="text-4xl font-extrabold text-[#2C3E3F]">{{ $stats['orders_count'] }}</h3>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <p class="text-gray-400 font-bold text-xs tracking-widest uppercase mb-2">Revenus CFA</p>
                <h3 class="text-4xl font-extrabold text-[#2C3E3F]">{{ number_format($stats['total_revenue'], 0, ',', ' ') }}</h3>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-10">
            <!-- Form: Create Restaurant -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-6">Ajouter un Restaurant</h3>
                <form action="{{ route('admin.restaurants.create') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nom du Restaurant</label>
                        <input type="text" name="name" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Adresse</label>
                        <input type="text" name="address" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <button type="submit" class="w-full bg-[#2C3E3F] text-white py-4 rounded-2xl font-bold hover:bg-[#1A2829] transition">Créer le Restaurant</button>
                </form>
            </div>

            <!-- Form: Create User -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-6">Créer un Restauranteur</h3>
                <form action="{{ route('admin.users.create') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nom Complet</label>
                            <input type="text" name="name" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Email</label>
                            <input type="email" name="email" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Mot de passe provisoire</label>
                        <input type="password" name="password" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Assigner au Restaurant</label>
                        <select name="restaurant_id" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                            @foreach($restaurants as $r)
                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-teal-600 text-white py-4 rounded-2xl font-bold hover:bg-teal-700 transition">Créer et Assigner</button>
                </form>
            </div>
        </div>

        <!-- Section: Restaurants List -->
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
            <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-6">Liste des Restaurants</h3>
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-400 text-xs tracking-widest uppercase border-b border-gray-50">
                        <th class="pb-4 font-bold">Restaurant</th>
                        <th class="pb-4 font-bold text-center">Commandes</th>
                        <th class="pb-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($restaurants as $r)
                    <tr>
                        <td class="py-4 font-extrabold text-[#2C3E3F]">{{ $r->name }}</td>
                        <td class="py-4 text-center text-gray-500 font-medium">{{ $r->orders_count }}</td>
                        <td class="py-4 text-right"><button class="text-teal-600 font-bold hover:underline">Détails</button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
    <!-- It is quality rather than quantity that matters. - Lucius Annaeus Seneca -->
</div>
