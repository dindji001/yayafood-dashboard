<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horaires Restaurant - YayaFood</title>
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
            <p class="text-gray-400 font-medium italic">Définissez quand vos clients peuvent commander.</p>
        </header>

        <!-- Settings Tabs Navigation -->
        <div class="flex items-center gap-8 border-b border-gray-200 mb-12">
            <a href="{{ route('restaurant.settings.profile') }}" class="nav-tab px-4 py-4 font-bold text-sm uppercase tracking-widest text-gray-400 hover:text-[#2C3E3F] transition-all">Profil Restaurant</a>
            <a href="{{ route('restaurant.settings.hours') }}" class="nav-tab active px-4 py-4 font-bold text-sm uppercase tracking-widest transition-all">Horaires</a>
            <a href="{{ route('restaurant.settings.services') }}" class="nav-tab px-4 py-4 font-bold text-sm uppercase tracking-widest text-gray-400 hover:text-[#2C3E3F] transition-all">Services & QR</a>
        </div>

        <div class="max-w-6xl">
            <div class="glass-card rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-8 flex items-center gap-3">
                    <i data-lucide="clock" class="w-6 h-6 text-blue-500"></i>
                    Horaires d'Ouverture & Disponibilité
                </h3>

                <form action="{{ route('restaurant.opening-hours.update') }}" method="POST" class="space-y-6" id="openingHoursForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Quick Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10">
                        <button type="button" onclick="setAll24h(true)" class="p-5 bg-blue-50 border border-blue-100 rounded-2xl text-blue-600 font-bold text-xs uppercase tracking-widest hover:bg-blue-100 transition-all flex items-center justify-center gap-3">
                            <i data-lucide="infinity" class="w-5 h-5"></i> Tout Ouvert 24h/7j
                        </button>
                        <button type="button" onclick="resetHours()" class="p-5 bg-gray-50 border border-gray-100 rounded-2xl text-gray-600 font-bold text-xs uppercase tracking-widest hover:bg-gray-100 transition-all flex items-center justify-center gap-3">
                            <i data-lucide="rotate-ccw" class="w-5 h-5"></i> Réinitialiser la semaine
                        </button>
                        <button type="submit" class="p-5 bg-[#2C3E3F] text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-[#1A2829] transition-all flex items-center justify-center gap-3 shadow-xl shadow-[#2C3E3F]/20">
                            <i data-lucide="save" class="w-5 h-5"></i> Sauvegarder l'organisation
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @php
                            $days = [
                                1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 
                                4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 0 => 'Dimanche'
                            ];
                            $hours = $restaurant->openingHours->keyBy('day_of_week');
                        @endphp

                        @foreach($days as $num => $label)
                            @php $h = $hours->get($num); @endphp
                            <div class="p-6 bg-gray-50 rounded-[2.5rem] border border-gray-100 transition-all day-row relative overflow-hidden group">
                                <div class="flex items-center justify-between mb-6 relative z-10">
                                    <span class="text-sm font-black text-[#2C3E3F] uppercase tracking-tighter">{{ $label }}</span>
                                    <div class="flex gap-2">
                                        <!-- Toggle 24h -->
                                        <label class="cursor-pointer" title="Ouvert 24h/24">
                                            <input type="checkbox" name="hours[{{ $loop->index }}][is_24h]" value="1" {{ $h && $h->is_24h ? 'checked' : '' }} 
                                                   class="hidden peer is-24h-checkbox" onchange="toggle24h(this)">
                                            <div class="w-10 h-10 border-2 border-gray-200 rounded-xl bg-white peer-checked:bg-blue-500 peer-checked:border-blue-500 flex items-center justify-center transition-all shadow-sm">
                                                <span class="text-[10px] font-black text-gray-400 peer-checked:text-white uppercase">24h</span>
                                            </div>
                                        </label>
                                        <!-- Toggle Closed -->
                                        <label class="cursor-pointer" title="Fermé">
                                            <input type="checkbox" name="hours[{{ $loop->index }}][is_closed]" value="1" {{ $h && $h->is_closed ? 'checked' : '' }} 
                                                   class="hidden peer is-closed-checkbox" onchange="toggleClosed(this)">
                                            <div class="w-10 h-10 border-2 border-gray-200 rounded-xl bg-white peer-checked:bg-red-500 peer-checked:border-red-500 flex items-center justify-center transition-all shadow-sm">
                                                <i data-lucide="x" class="w-4 h-4 text-gray-400 peer-checked:text-white"></i>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                
                                <input type="hidden" name="hours[{{ $loop->index }}][day_of_week]" value="{{ $num }}">
                                
                                <div class="space-y-3 time-inputs relative z-10 {{ ($h && ($h->is_24h || $h->is_closed)) ? 'hidden' : '' }}">
                                    <div class="relative">
                                        <i data-lucide="door-open" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-300"></i>
                                        <input type="time" name="hours[{{ $loop->index }}][open_time]" value="{{ $h ? substr($h->open_time, 0, 5) : '09:00' }}" 
                                               class="w-full bg-white border-none rounded-2xl pl-12 pr-4 py-4 text-sm font-bold text-[#2C3E3F] focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none shadow-sm">
                                    </div>
                                    <div class="text-center text-[10px] font-black text-gray-300 uppercase tracking-widest">à</div>
                                    <div class="relative">
                                        <i data-lucide="door-closed" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-300"></i>
                                        <input type="time" name="hours[{{ $loop->index }}][close_time]" value="{{ $h ? substr($h->close_time, 0, 5) : '22:00' }}" 
                                               class="w-full bg-white border-none rounded-2xl pl-12 pr-4 py-4 text-sm font-bold text-[#2C3E3F] focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none shadow-sm">
                                    </div>
                                </div>

                                <div class="status-label h-32 flex items-center justify-center relative z-10 {{ (!($h && ($h->is_24h || $h->is_closed))) ? 'hidden' : '' }}">
                                    <span class="status-badge text-xs font-black uppercase tracking-[0.2em] text-center leading-loose">
                                        @if($h && $h->is_24h) <span class="text-blue-500">Ouvert<br>Non-stop</span>
                                        @elseif($h && $h->is_closed) <span class="text-red-500">Établissement<br>Fermé</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();

        function toggle24h(checkbox) {
            const row = checkbox.closest('.day-row');
            const timeInputs = row.querySelector('.time-inputs');
            const statusLabel = row.querySelector('.status-label');
            const statusBadge = row.querySelector('.status-badge');
            const closedCheckbox = row.querySelector('.is-closed-checkbox');

            if (checkbox.checked) {
                closedCheckbox.checked = false;
                timeInputs.classList.add('hidden');
                statusLabel.classList.remove('hidden');
                statusBadge.innerHTML = '<span class="text-blue-500">Ouvert<br>Non-stop</span>';
            } else {
                timeInputs.classList.remove('hidden');
                statusLabel.classList.add('hidden');
            }
        }

        function toggleClosed(checkbox) {
            const row = checkbox.closest('.day-row');
            const timeInputs = row.querySelector('.time-inputs');
            const statusLabel = row.querySelector('.status-label');
            const statusBadge = row.querySelector('.status-badge');
            const checkbox24h = row.querySelector('.is-24h-checkbox');

            if (checkbox.checked) {
                checkbox24h.checked = false;
                timeInputs.classList.add('hidden');
                statusLabel.classList.remove('hidden');
                statusBadge.innerHTML = '<span class="text-red-500">Établissement<br>Fermé</span>';
            } else {
                timeInputs.classList.remove('hidden');
                statusLabel.classList.add('hidden');
            }
        }

        function setAll24h(value) {
            document.querySelectorAll('.is-24h-checkbox').forEach(cb => {
                cb.checked = value;
                toggle24h(cb);
            });
        }

        function resetHours() {
            document.querySelectorAll('.is-24h-checkbox, .is-closed-checkbox').forEach(cb => {
                cb.checked = false;
                toggle24h(cb);
            });
        }
    </script>
</body>
</html>
