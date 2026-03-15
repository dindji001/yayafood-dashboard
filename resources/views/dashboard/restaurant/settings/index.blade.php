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

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-12">
            <!-- Services Toggles -->
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
            </div>

            <!-- QR Code Section -->
            <div class="glass-card rounded-[2.5rem] p-10 shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center">
                <div class="mb-8">
                    <h3 class="text-2xl font-black text-[#2C3E3F] mb-2 uppercase">Menu Digital QR</h3>
                    <p class="text-gray-400 font-medium italic">Vos clients scannent et commandent en 1 clic.</p>
                </div>

                <div class="w-64 h-64 bg-gray-50 rounded-[2rem] p-6 border border-gray-100 shadow-inner mb-10 flex items-center justify-center relative overflow-hidden group">
                    @if($restaurant->qr_code)
                        <img src="{{ $restaurant->qr_code_url }}" class="w-full h-full object-contain relative z-10" alt="QR Code">
                    @else
                        <div class="text-center p-8">
                            <i data-lucide="qr-code" class="w-16 h-16 text-gray-200 mx-auto mb-4"></i>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Aucun QR Code généré</p>
                        </div>
                    @endif
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

    <script>lucide.createIcons();</script>
</body>
</html>
