<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demandes de Suppression - YayaFood</title>
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
                Restaurants
            </a>
            <a href="{{ route('admin.deletion-requests.index') }}" class="flex items-center gap-3 p-4 rounded-2xl font-bold bg-[#2C3E3F] text-white shadow-lg transition-all">
                <i data-lucide="user-x" class="w-5 h-5"></i>
                Demandes Suppression
            </a>
        </nav>
    </aside>

    <main class="flex-1 ml-72 p-10">
        <header class="mb-12">
            <h2 class="text-4xl font-black text-[#2C3E3F] tracking-tight mb-2 uppercase">Demandes de Suppression</h2>
            <p class="text-gray-400 font-medium italic">Gérez les demandes de clôture de compte des utilisateurs et restaurants.</p>
        </header>

        <div class="glass-card rounded-[3rem] shadow-sm overflow-hidden border border-gray-100">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em]">
                        <th class="px-10 py-6">Utilisateur / Restaurant</th>
                        <th class="px-10 py-6">Raison invoquée</th>
                        <th class="px-10 py-6">Date de demande</th>
                        <th class="px-10 py-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($requests as $req)
                        <tr class="hover:bg-gray-50/30 transition-all">
                            <td class="px-10 py-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center text-[#2C3E3F]">
                                        <i data-lucide="{{ $req->user->role === 'restaurant' ? 'store' : 'user' }}" class="w-6 h-6"></i>
                                    </div>
                                    <div>
                                        <p class="font-black text-[#2C3E3F]">{{ $req->user->name }}</p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $req->user->email }}</p>
                                        @if($req->user->restaurant)
                                            <span class="inline-block mt-1 px-2 py-0.5 bg-orange-50 text-orange-600 rounded text-[8px] font-black uppercase">{{ $req->user->restaurant->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-8">
                                <p class="text-xs text-gray-500 font-medium leading-relaxed max-w-xs">
                                    {{ $req->reason ?: 'Aucune raison spécifiée.' }}
                                </p>
                            </td>
                            <td class="px-10 py-8 text-xs font-bold text-gray-400">
                                {{ $req->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-10 py-8 text-right">
                                <div class="flex justify-end gap-3">
                                    <form action="{{ route('admin.deletion-requests.process', $req->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" onclick="return confirm('Confirmer la suppression définitive ?')" class="px-4 py-2 bg-red-50 text-red-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all">
                                            Approuver
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.deletion-requests.process', $req->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="cancel">
                                        <button type="submit" class="px-4 py-2 bg-gray-50 text-gray-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-200 transition-all">
                                            Rejeter
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-10 py-20 text-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i data-lucide="check-circle" class="w-10 h-10 text-green-500"></i>
                                </div>
                                <p class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Aucune demande en attente</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    <script>lucide.createIcons();</script>
</body>
</html>
