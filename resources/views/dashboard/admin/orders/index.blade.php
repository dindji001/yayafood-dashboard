<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Commandes - Admin YayaFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; }
        .sidebar-link.active { background-color: rgba(44, 62, 63, 0.1); color: #2C3E3F; border-right: 4px solid #2C3E3F; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(241, 245, 249, 1); }
        .status-badge { font-size: 0.65rem; font-weight: 800; letter-spacing: 0.05em; text-transform: uppercase; }
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
            <a href="{{ route('admin.orders.index') }}" class="sidebar-link active flex items-center gap-3 p-4 rounded-2xl font-bold transition-all">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                Commandes
            </a>
            <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all">
                <i data-lucide="users" class="w-5 h-5"></i>
                Clients
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="sidebar-link flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all">
                <i data-lucide="star" class="w-5 h-5"></i>
                Avis
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 ml-72 p-10">
        <header class="mb-12">
            <h2 class="text-4xl font-black text-[#2C3E3F] tracking-tight mb-2 uppercase">Flux des Commandes</h2>
            <p class="text-gray-400 font-medium italic">Vision globale de toutes les transactions de l'écosystème.</p>
        </header>

        <!-- FILTRES TEMPORELS & RESTAURANTS -->
        <div class="glass-card rounded-[2.5rem] p-8 mb-10 border border-gray-100 shadow-sm">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap items-center gap-6">
                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Période</label>
                    <select name="period" onchange="this.form.submit()" class="bg-gray-50 border-none rounded-xl px-4 py-3 text-xs font-bold text-[#2C3E3F] outline-none focus:ring-2 focus:ring-[#2C3E3F]/10">
                        <option value="all" {{ request('period') == 'all' ? 'selected' : '' }}>Toutes les périodes</option>
                        <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                        <option value="yesterday" {{ request('period') == 'yesterday' ? 'selected' : '' }}>Hier</option>
                        <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                        <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Ce mois-ci</option>
                        <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Date spécifique...</option>
                    </select>
                </div>

                @if(request('period') == 'custom')
                    <div class="flex flex-col gap-2 animate-in fade-in zoom-in duration-300">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Choisir une date</label>
                        <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()" 
                               class="bg-gray-50 border-none rounded-xl px-4 py-3 text-xs font-bold text-[#2C3E3F] outline-none focus:ring-2 focus:ring-[#2C3E3F]/10">
                    </div>
                @endif

                @if(request('period') == 'today' || request('period') == 'custom')
                    <div class="flex flex-col gap-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Heure</label>
                        <select name="hour" onchange="this.form.submit()" class="bg-gray-50 border-none rounded-xl px-4 py-3 text-xs font-bold text-[#2C3E3F] outline-none focus:ring-2 focus:ring-[#2C3E3F]/10">
                            <option value="">Toute la journée</option>
                            @for($i = 0; $i < 24; $i++)
                                <option value="{{ $i }}" {{ request('hour') !== null && request('hour') != '' && request('hour') == $i ? 'selected' : '' }}>
                                    {{ sprintf('%02d', $i) }}:00 - {{ sprintf('%02d', $i) }}:59
                                </option>
                            @endfor
                        </select>
                    </div>
                @endif

                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Restaurant</label>
                    <select name="restaurant_id" onchange="this.form.submit()" class="bg-gray-50 border-none rounded-xl px-4 py-3 text-xs font-bold text-[#2C3E3F] outline-none focus:ring-2 focus:ring-[#2C3E3F]/10">
                        <option value="">Tous les établissements</option>
                        @foreach(\App\Models\Restaurant::orderBy('name')->get() as $res)
                            <option value="{{ $res->id }}" {{ request('restaurant_id') == $res->id ? 'selected' : '' }}>{{ $res->name }}</option>
                        @endforeach
                    </select>
                </div>

                @if(request()->hasAny(['period', 'hour', 'date', 'restaurant_id']))
                    <div class="flex flex-col gap-2 mt-auto">
                        <a href="{{ route('admin.orders.index') }}" class="text-[10px] font-black text-red-400 uppercase tracking-widest hover:text-red-600 transition-all flex items-center gap-1 py-4 px-2">
                            <i data-lucide="trash-2" class="w-4 h-4"></i> Effacer
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <div class="glass-card rounded-[2.5rem] overflow-hidden shadow-sm border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Date & ID</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Établissement</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Client</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Total</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Statut</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Paiement</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50/50 transition-all group">
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="font-black text-[#2C3E3F]">#{{ $order->id }}</span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $order->created_at->format('d/m H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-[#2C3E3F] font-black text-lg border border-gray-100 shadow-sm">
                                            {{ substr($order->restaurant->name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-[#2C3E3F]">{{ $order->restaurant->name }}</span>
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $order->restaurant->address }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $order->user->name }}" class="w-8 h-8 rounded-lg bg-gray-100">
                                        <span class="font-bold text-[#2C3E3F]">{{ $order->user->name }}</span>
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
                                    @endphp
                                    <span class="px-3 py-1 rounded-full status-badge {{ $statusClasses[$order->status] }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="text-[9px] font-black uppercase tracking-widest {{ $order->payment_status === 'paid' ? 'text-green-500' : 'text-orange-500' }}">
                                            {{ $order->payment_status === 'paid' ? 'Payé' : 'À payer' }}
                                        </span>
                                        <span class="text-[9px] font-bold text-gray-400 uppercase italic">{{ $order->payment_method }}</span>
                                    </div>
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
