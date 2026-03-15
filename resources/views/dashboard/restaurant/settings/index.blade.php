<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres Restaurant - YayaFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; }
        .sidebar-link.active { background-color: #2C3E3F; color: white; box-shadow: 0 10px 15px -3px rgba(44, 62, 63, 0.2); }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(241, 245, 249, 1); }
        .toggle-checkbox:checked { right: 0; border-color: #059669; }
        .toggle-checkbox:checked + .toggle-label { background-color: #10B981; }
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
            <p class="text-gray-400 font-medium italic">Gérez la visibilité et les services de votre établissement.</p>
        </header>

        <!-- NOUVEL EMPLACEMENT DES HORAIRES (TRÈS VISIBLE) -->
        <div class="mb-12">
            <div class="glass-card rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-8 flex items-center gap-3">
                    <i data-lucide="clock" class="w-6 h-6 text-blue-500"></i>
                    Horaires d'Ouverture & Disponibilité
                </h3>

                <form action="{{ route('restaurant.opening-hours.update') }}" method="POST" class="space-y-6" id="openingHoursForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Quick Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <button type="button" onclick="setAll24h(true)" class="p-4 bg-blue-50 border border-blue-100 rounded-2xl text-blue-600 font-bold text-xs uppercase tracking-widest hover:bg-blue-100 transition-all flex items-center justify-center gap-2">
                            <i data-lucide="infinity" class="w-4 h-4"></i> Ouvert 24h/7j
                        </button>
                        <button type="button" onclick="resetHours()" class="p-4 bg-gray-50 border border-gray-100 rounded-2xl text-gray-600 font-bold text-xs uppercase tracking-widest hover:bg-gray-100 transition-all flex items-center justify-center gap-2">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Réinitialiser les horaires
                        </button>
                        <button type="submit" class="p-4 bg-[#2C3E3F] text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-[#1A2829] transition-all flex items-center justify-center gap-2 shadow-lg shadow-[#2C3E3F]/20">
                            <i data-lucide="save" class="w-4 h-4"></i> Enregistrer les horaires
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @php
                            $days = [
                                1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 
                                4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 0 => 'Dimanche'
                            ];
                            $hours = $restaurant->openingHours->keyBy('day_of_week');
                        @endphp

                        @foreach($days as $num => $label)
                            @php $h = $hours->get($num); @endphp
                            <div class="p-5 bg-gray-50 rounded-[2rem] border border-gray-100 transition-all day-row">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-sm font-black text-[#2C3E3F] uppercase tracking-tighter">{{ $label }}</span>
                                    <div class="flex gap-2">
                                        <!-- Toggle 24h -->
                                        <label class="cursor-pointer group" title="Ouvert 24h/24">
                                            <input type="checkbox" name="hours[{{ $loop->index }}][is_24h]" value="1" {{ $h && $h->is_24h ? 'checked' : '' }} 
                                                   class="hidden peer is-24h-checkbox" onchange="toggle24h(this)">
                                            <div class="w-8 h-8 border-2 border-gray-200 rounded-lg peer-checked:bg-blue-500 peer-checked:border-blue-500 flex items-center justify-center transition-all">
                                                <span class="text-[8px] font-black text-gray-400 peer-checked:text-white uppercase">24h</span>
                                            </div>
                                        </label>
                                        <!-- Toggle Closed -->
                                        <label class="cursor-pointer group" title="Fermé">
                                            <input type="checkbox" name="hours[{{ $loop->index }}][is_closed]" value="1" {{ $h && $h->is_closed ? 'checked' : '' }} 
                                                   class="hidden peer is-closed-checkbox" onchange="toggleClosed(this)">
                                            <div class="w-8 h-8 border-2 border-gray-200 rounded-lg peer-checked:bg-red-500 peer-checked:border-red-500 flex items-center justify-center transition-all">
                                                <i data-lucide="x" class="w-3 h-3 text-gray-400 peer-checked:text-white"></i>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                
                                <input type="hidden" name="hours[{{ $loop->index }}][day_of_week]" value="{{ $num }}">
                                
                                <div class="space-y-2 time-inputs {{ ($h && ($h->is_24h || $h->is_closed)) ? 'hidden' : '' }}">
                                    <input type="time" name="hours[{{ $loop->index }}][open_time]" value="{{ $h ? substr($h->open_time, 0, 5) : '09:00' }}" 
                                           class="w-full bg-white border-none rounded-xl px-3 py-2 text-xs font-bold text-[#2C3E3F] focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none">
                                    <div class="text-center text-[8px] font-black text-gray-300 uppercase">à</div>
                                    <input type="time" name="hours[{{ $loop->index }}][close_time]" value="{{ $h ? substr($h->close_time, 0, 5) : '22:00' }}" 
                                           class="w-full bg-white border-none rounded-xl px-3 py-2 text-xs font-bold text-[#2C3E3F] focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none">
                                </div>

                                <div class="status-label h-20 flex items-center justify-center {{ (!($h && ($h->is_24h || $h->is_closed))) ? 'hidden' : '' }}">
                                    <span class="status-badge text-[10px] font-black uppercase tracking-widest text-center leading-tight">
                                        @if($h && $h->is_24h) <span class="text-blue-500">24h/24</span>
                                        @elseif($h && $h->is_closed) <span class="text-red-500">Fermé</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-12">
            <!-- Restaurant Profile Information -->
            <div class="space-y-8">
                <div class="glass-card rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                    <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-8 flex items-center gap-3">
                        <i data-lucide="user" class="w-6 h-6 text-[#2C3E3F]"></i>
                        Profil du Restaurant
                    </h3>

                    <form action="{{ route('restaurant.info.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-2">Nom commercial</label>
                            <input type="text" name="name" value="{{ $restaurant->name }}" required class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-[#2C3E3F] focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-2">Description</label>
                            <textarea name="description" rows="3" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-[#2C3E3F] focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none" placeholder="Décrivez votre restaurant...">{{ $restaurant->description }}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-2">Adresse</label>
                                <input type="text" name="address" value="{{ $restaurant->address }}" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-[#2C3E3F] focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-2">Téléphone</label>
                                <input type="text" name="phone" value="{{ $restaurant->phone }}" class="w-full bg-gray-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-[#2C3E3F] focus:ring-2 focus:ring-[#2C3E3F]/10 outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-2">Logo</label>
                                <div class="relative group h-32 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center overflow-hidden transition-all hover:border-[#2C3E3F]">
                                    @if($restaurant->logo)
                                        <img src="{{ $restaurant->logo_url }}" class="w-full h-full object-cover">
                                    @else
                                        <i data-lucide="image" class="w-6 h-6 text-gray-300 mb-1"></i>
                                        <span class="text-[8px] font-bold text-gray-400 uppercase">Logo</span>
                                    @endif
                                    <input type="file" name="logo" class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-2">Bannière</label>
                                <div class="relative group h-32 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center overflow-hidden transition-all hover:border-[#2C3E3F]">
                                    @if($restaurant->banner)
                                        <img src="{{ $restaurant->banner_url }}" class="w-full h-full object-cover">
                                    @else
                                        <i data-lucide="layout-template" class="w-6 h-6 text-gray-300 mb-1"></i>
                                        <span class="text-[8px] font-bold text-gray-400 uppercase">Image Fond</span>
                                    @endif
                                    <input type="file" name="banner" class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-[#2C3E3F] text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-[#1A2829] transition-all shadow-lg shadow-[#2C3E3F]/20">
                            Enregistrer les modifications
                        </button>
                    </form>
                </div>

                <!-- Account Deletion -->
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
                            Demander la suppression
                        </button>
                    </form>
                </div>
            </div>

            <!-- Services Toggles & QR -->
            <div class="space-y-8">
                <div class="glass-card rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                    <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-8 flex items-center gap-3">
                        <i data-lucide="toggle-right" class="w-6 h-6 text-orange-500"></i>
                        Activation des services
                    </h3>

                    <div class="space-y-6">
                        <!-- Restaurant Status -->
                        <div class="flex items-center justify-between p-6 bg-gray-50 rounded-[1.5rem] border border-gray-100">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-[#2C3E3F] shadow-sm">
                                    <i data-lucide="power" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-[#2C3E3F]">Visibilité Application</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Rendre le restaurant visible</p>
                                </div>
                            </div>
                            <form action="{{ route('restaurant.settings.toggle') }}" method="POST">
                                @csrf
                                <input type="hidden" name="setting" value="is_active">
                                <button type="submit" class="relative inline-flex items-center cursor-pointer">
                                    <div class="w-14 h-8 {{ $restaurant->is_active ? 'bg-green-500' : 'bg-gray-300' }} rounded-full transition-colors duration-300"></div>
                                    <div class="absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-300 transform {{ $restaurant->is_active ? 'translate-x-6' : '' }}"></div>
                                </button>
                            </form>
                        </div>

                        <!-- Pay on spot -->
                        <div class="flex items-center justify-between p-6 bg-gray-50 rounded-[1.5rem] border border-gray-100">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-[#2C3E3F] shadow-sm">
                                    <i data-lucide="hand-coins" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-[#2C3E3F]">Payer sur place</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Activer le service de paiement direct</p>
                                </div>
                            </div>
                            <form action="{{ route('restaurant.settings.toggle') }}" method="POST">
                                @csrf
                                <input type="hidden" name="setting" value="allow_pay_on_spot">
                                <button type="submit" class="relative inline-flex items-center cursor-pointer">
                                    <div class="w-14 h-8 {{ $restaurant->allow_pay_on_spot ? 'bg-green-500' : 'bg-gray-300' }} rounded-full transition-colors duration-300"></div>
                                    <div class="absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-300 transform {{ $restaurant->allow_pay_on_spot ? 'translate-x-6' : '' }}"></div>
                                </button>
                            </form>
                        </div>

                        <!-- Online Payment -->
                        <div class="flex items-center justify-between p-6 bg-gray-50 rounded-[1.5rem] border border-gray-100">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-[#2C3E3F] shadow-sm">
                                    <i data-lucide="credit-card" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-[#2C3E3F]">Paiement en ligne</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Mobile Money / Cartes bancaires</p>
                                </div>
                            </div>
                            <form action="{{ route('restaurant.settings.toggle') }}" method="POST">
                                @csrf
                                <input type="hidden" name="setting" value="allow_online_payment">
                                <button type="submit" class="relative inline-flex items-center cursor-pointer">
                                    <div class="w-14 h-8 {{ $restaurant->allow_online_payment ? 'bg-green-500' : 'bg-gray-300' }} rounded-full transition-colors duration-300"></div>
                                    <div class="absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-300 transform {{ $restaurant->allow_online_payment ? 'translate-x-6' : '' }}"></div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Opening Hours -->
            </div>

            <!-- QR Code Section -->
            <div class="glass-card rounded-[2.5rem] p-10 shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center">
                <div class="mb-8">
                    <h3 class="text-2xl font-black text-[#2C3E3F] mb-2 uppercase">Menu Digital QR</h3>
                    <p class="text-gray-400 font-medium italic">Vos clients scannent et commandent en 1 clic.</p>
                </div>

                <div class="w-64 h-64 bg-white rounded-[2rem] p-6 border border-gray-100 shadow-inner mb-10 flex items-center justify-center relative overflow-hidden group">
                    <div class="relative z-10">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->margin(1)->errorCorrection('H')->generate(config('app.url') . '/r/' . $restaurant->id) !!}
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full max-w-sm">
                    <form action="{{ route('restaurant.qrcode.regenerate') }}" method="POST" class="col-span-2">
                        @csrf
                        <button type="submit" class="w-full bg-[#2C3E3F] text-white px-6 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-[#1A2829] transition-all flex items-center justify-center gap-2 mb-2 shadow-lg shadow-[#2C3E3F]/20">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                            Générer Automatiquement
                        </button>
                    </form>

                    <form action="{{ route('restaurant.info.update') }}" method="POST" enctype="multipart/form-data" class="contents">
                        @csrf
                        <label class="cursor-pointer bg-white border border-gray-200 text-[#2C3E3F] px-6 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-50 transition-all flex items-center justify-center gap-2 shadow-sm">
                            <i data-lucide="upload" class="w-4 h-4"></i>
                            Importer
                            <input type="file" name="qr_code" class="hidden" onchange="this.form.submit()">
                        </label>
                    </form>
                    
                    @if($restaurant->qr_code)
                        <a href="{{ $restaurant->qr_code_url }}" download class="bg-orange-500 text-white px-6 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-orange-600 transition-all flex items-center justify-center gap-2 shadow-lg shadow-orange-500/20">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            Télécharger
                        </a>
                    @endif
                </div>
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
                statusBadge.textContent = 'Ouvert non-stop (24h)';
                statusBadge.className = 'px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest status-badge bg-blue-100 text-blue-600';
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
                statusBadge.textContent = 'Établissement fermé';
                statusBadge.className = 'px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest status-badge bg-red-100 text-red-600';
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
