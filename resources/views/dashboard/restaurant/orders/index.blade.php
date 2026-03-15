<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes - YayaFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; }
        .sidebar-link.active { background-color: #2C3E3F; color: white; box-shadow: 0 10px 15px -3px rgba(44, 62, 63, 0.2); }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(241, 245, 249, 1); }
        .status-badge { font-size: 0.65rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; }
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
            <a href="{{ route('restaurant.orders.index') }}" class="sidebar-link active flex items-center gap-4 p-4 rounded-2xl font-bold transition-all group">
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
                <h2 class="text-4xl font-black text-[#2C3E3F] tracking-tight mb-2 uppercase">Commandes</h2>
                <p class="text-gray-400 font-medium italic">Historique et gestion des commandes de votre restaurant.</p>
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

        <div class="glass-card rounded-[2.5rem] overflow-hidden shadow-sm border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Commande</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Client</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Contenu</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Total</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Statut</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Paiement</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50/50 transition-all group">
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="font-black text-[#2C3E3F]">#{{ $order->id }}</span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $order->created_at->format('d/m H:i') }}</span>
                                        @if($order->table_number)
                                            <span class="mt-2 inline-flex items-center gap-1 text-[9px] font-black bg-orange-100 text-orange-600 px-2 py-1 rounded-full uppercase tracking-tighter">Table {{ $order->table_number }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $order->user->name }}" class="w-8 h-8 rounded-lg bg-gray-100">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-[#2C3E3F]">{{ $order->user->name }}</span>
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Client Fidèle</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col gap-1">
                                        @foreach($order->items as $item)
                                            <span class="text-xs font-semibold text-gray-600"><span class="font-black text-[#2C3E3F]">{{ $item->quantity }}x</span> {{ $item->dish->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="font-black text-[#2C3E3F]">{{ number_format($order->total_amount, 0, ',', ' ') }} CFA</span>
                                </td>
                                <td class="px-8 py-6">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-600',
                                            'preparing' => 'bg-blue-100 text-blue-600',
                                            'ready' => 'bg-teal-100 text-teal-600',
                                            'served' => 'bg-green-100 text-green-600',
                                            'closed' => 'bg-gray-100 text-gray-600',
                                            'cancelled' => 'bg-red-100 text-red-600',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'En attente',
                                            'preparing' => 'Préparation',
                                            'ready' => 'Prête',
                                            'served' => 'Servie',
                                            'closed' => 'Terminée',
                                            'cancelled' => 'Annulée',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full status-badge {{ $statusClasses[$order->status] }}">
                                        {{ $statusLabels[$order->status] }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="text-[9px] font-black uppercase tracking-widest {{ $order->payment_status === 'paid' ? 'text-green-500' : 'text-orange-500' }}">
                                            {{ $order->payment_status === 'paid' ? 'Payé' : 'À payer' }}
                                        </span>
                                        <span class="text-[9px] font-bold text-gray-400 uppercase italic">{{ $order->payment_method === 'cash' ? 'Sur place' : 'Ligne (Mobile)' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <form action="{{ route('restaurant.orders.status', $order->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-100 rounded-xl px-4 py-2 text-[10px] font-black uppercase tracking-widest text-[#2C3E3F] outline-none hover:border-[#2C3E3F] transition-all">
                                            @foreach($statusLabels as $value => $label)
                                                <option value="{{ $value }}" {{ $order->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($orders->hasPages())
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </main>

    <script>lucide.createIcons();</script>
</body>
</html>
