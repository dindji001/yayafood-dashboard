<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $restaurant->name }} - Analyse Détaillée</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(241, 245, 249, 1); }
    </style>
</head>
<body class="flex min-h-screen">
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
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Tableau de bord
            </a>
            <a href="{{ route('admin.restaurants.index') }}" class="flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all">
                <i data-lucide="store" class="w-5 h-5"></i>
                Liste des Restaurants
            </a>
        </nav>
    </aside>

    <main class="flex-1 ml-72 p-10">
        <header class="flex justify-between items-center mb-12">
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.restaurants.index') }}" class="p-3 bg-white rounded-xl border border-gray-100 shadow-sm hover:bg-gray-50 transition-all">
                    <i data-lucide="chevron-left" class="w-5 h-5 text-[#2C3E3F]"></i>
                </a>
                <div>
                    <h2 class="text-4xl font-extrabold text-[#2C3E3F] tracking-tight uppercase">{{ $restaurant->name }}</h2>
                    <p class="text-gray-400 font-medium">Analyse approfondie des performances.</p>
                </div>
            </div>
            <div class="flex gap-3">
                <span class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-xs font-black uppercase tracking-widest flex items-center gap-2">
                    <i data-lucide="package" class="w-4 h-4"></i> {{ $dishStats['available'] }}/{{ $dishStats['total'] }} Plats
                </span>
                <span class="px-4 py-2 bg-green-50 text-green-600 rounded-xl text-xs font-black uppercase tracking-widest flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> {{ $orderStats['paid'] }} Payées
                </span>
            </div>
        </header>

        <!-- Stats Overview -->
        <div class="grid grid-cols-4 gap-8 mb-12">
            <div class="glass-card p-8 rounded-[2.5rem] shadow-sm">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-6">
                    <i data-lucide="trending-up" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-[10px] tracking-widest uppercase mb-2">Chiffre d'Affaires</p>
                <h3 class="text-3xl font-black text-[#2C3E3F]">{{ number_format($orderStats['total_revenue'], 0, ',', ' ') }} <span class="text-xs">CFA</span></h3>
            </div>
            <div class="glass-card p-8 rounded-[2.5rem] shadow-sm">
                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center mb-6">
                    <i data-lucide="x-circle" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-[10px] tracking-widest uppercase mb-2">Annulées / Échouées</p>
                <h3 class="text-3xl font-black text-[#2C3E3F]">{{ $orderStats['cancelled'] + $orderStats['failed'] }}</h3>
            </div>
            <div class="glass-card p-8 rounded-[2.5rem] shadow-sm">
                <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center mb-6">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-[10px] tracking-widest uppercase mb-2">En Attente</p>
                <h3 class="text-3xl font-black text-[#2C3E3F]">{{ $orderStats['pending'] }}</h3>
            </div>
            <div class="glass-card p-8 rounded-[2.5rem] shadow-sm">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6">
                    <i data-lucide="star" class="w-6 h-6"></i>
                </div>
                <p class="text-gray-400 font-bold text-[10px] tracking-widest uppercase mb-2">Note Moyenne</p>
                <h3 class="text-3xl font-black text-[#2C3E3F]">{{ number_format($restaurant->averageRating(), 1) }} ★</h3>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-10">
            <!-- Traffic Chart -->
            <div class="col-span-8">
                <div class="glass-card rounded-[3rem] p-10 shadow-sm">
                    <div class="flex justify-between items-center mb-10">
                        <h3 class="text-xl font-extrabold text-[#2C3E3F] uppercase tracking-tight">Activité des 7 derniers jours</h3>
                        <i data-lucide="bar-chart-2" class="w-6 h-6 text-gray-200"></i>
                    </div>
                    <div id="chartData" 
                         data-labels="{{ json_encode($visitsPerDay->pluck('date')) }}" 
                         data-values="{{ json_encode($visitsPerDay->pluck('count')) }}">
                    </div>
                    <canvas id="trafficChart" height="150"></canvas>
                </div>
            </div>

            <!-- Recent Reviews -->
            <div class="col-span-4">
                <div class="glass-card rounded-[3rem] p-10 shadow-sm h-full">
                    <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-8 uppercase tracking-tight">Derniers Avis</h3>
                    <div class="space-y-6">
                        @forelse($restaurant->reviews->take(4) as $review)
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-xs font-black text-[#2C3E3F]">{{ $review->user->name }}</p>
                                    <span class="text-orange-500 font-bold text-[10px]">{{ $review->rating }} ★</span>
                                </div>
                                <p class="text-[10px] text-gray-500 italic">"{{ Str::limit($review->comment, 60) }}"</p>
                            </div>
                        @empty
                            <p class="text-center text-gray-400 text-xs py-10 italic">Aucun avis pour le moment.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Dishes Table -->
        <div class="mt-12 glass-card rounded-[3rem] shadow-sm overflow-hidden">
            <div class="p-10 border-b border-gray-50">
                <h3 class="text-xl font-extrabold text-[#2C3E3F] uppercase tracking-tight">Inventaire du Menu</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50">
                        <tr class="text-gray-400 text-[10px] font-extrabold uppercase tracking-[0.15em]">
                            <th class="px-10 py-5">Plat</th>
                            <th class="px-10 py-5">Catégorie</th>
                            <th class="px-10 py-5">Prix</th>
                            <th class="px-10 py-5">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($restaurant->categories as $cat)
                            @foreach($cat->dishes as $dish)
                                <tr class="hover:bg-gray-50/30 transition-all">
                                    <td class="px-10 py-6">
                                        <p class="text-sm font-extrabold text-[#2C3E3F]">{{ $dish->name }}</p>
                                    </td>
                                    <td class="px-10 py-6">
                                        <span class="px-3 py-1 bg-[#2C3E3F]/5 text-[#2C3E3F] rounded-lg text-[10px] font-bold">{{ $cat->name }}</span>
                                    </td>
                                    <td class="px-10 py-6">
                                        <p class="text-xs font-black text-gray-600">{{ number_format($dish->price, 0) }} CFA</p>
                                    </td>
                                    <td class="px-10 py-6">
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $dish->is_available ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                            {{ $dish->is_available ? 'En Stock' : 'Épuisé' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();

        const chartElement = document.getElementById('chartData');
        const labels = JSON.parse(chartElement.dataset.labels);
        const values = JSON.parse(chartElement.dataset.values);

        const ctx = document.getElementById('trafficChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Commandes / Visites',
                    data: values,
                    borderColor: '#2C3E3F',
                    backgroundColor: 'rgba(44, 62, 63, 0.05)',
                    borderWidth: 4,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#2C3E3F',
                    pointBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { display: false } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
</body>
</html>