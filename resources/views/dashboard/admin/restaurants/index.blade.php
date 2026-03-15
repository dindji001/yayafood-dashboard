<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Restaurants - Admin YayaFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border: 1px solid rgba(241, 245, 249, 1); }
    </style>
</head>
<body class="flex min-h-screen">
    <!-- Sidebar (Reprise du design existant) -->
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
            <a href="{{ route('admin.restaurants.index') }}" class="bg-[#2C3E3F]/10 text-[#2C3E3F] border-r-4 border-[#2C3E3F] flex items-center gap-3 p-4 rounded-2xl font-bold transition-all">
                <i data-lucide="store" class="w-5 h-5"></i>
                Liste des Restaurants
            </a>
            <a href="{{ route('dashboard') }}#users" class="flex items-center gap-3 p-4 rounded-2xl font-semibold text-gray-500 hover:bg-gray-50 transition-all">
                <i data-lucide="users" class="w-5 h-5"></i>
                Utilisateurs
            </a>
        </nav>
    </aside>

    <main class="flex-1 ml-72 p-10">
        <header class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-4xl font-extrabold text-[#2C3E3F] tracking-tight mb-2">ÉCOSYSTÈME RESTAURANTS</h2>
                <p class="text-gray-400 font-medium">Analyse et performance de vos partenaires.</p>
            </div>
            <button onclick="openModal('createRestaurantModal')" class="bg-[#2C3E3F] hover:bg-orange-500 text-white px-8 py-4 rounded-[1.5rem] font-bold transition-all flex items-center gap-3 shadow-lg shadow-[#2C3E3F]/10">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                Nouveau Restaurant
            </button>
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

        @if($errors->any())
            <div class="mb-8 p-6 bg-red-50 border border-red-100 rounded-[2rem] flex items-center gap-4 text-red-600 animate-in fade-in slide-in-from-top-4 duration-500">
                <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center">
                    <i data-lucide="alert-circle" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="font-black uppercase text-[10px] tracking-widest mb-0.5">Erreur</p>
                    <ul class="list-disc list-inside font-bold">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            @foreach($restaurants as $r)
                <div class="glass-card rounded-[2.5rem] p-8 hover:shadow-2xl transition-all group">
                    <div class="flex justify-between items-start mb-6">
                        @if($r->logo)
                            <div class="w-16 h-16 rounded-2xl overflow-hidden border border-gray-100 shadow-sm bg-white">
                                <img src="{{ $r->logo_url }}" alt="{{ $r->name }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-16 h-16 bg-[#F1F5F4] rounded-2xl flex items-center justify-center text-[#2C3E3F] font-black text-2xl border border-gray-100 shadow-sm">
                                {{ substr($r->name, 0, 1) }}
                            </div>
                        @endif
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $r->is_active ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                            {{ $r->is_active ? 'Opérationnel' : 'Suspendu' }}
                        </span>
                    </div>

                    <h3 class="text-xl font-extrabold text-[#2C3E3F] mb-2">{{ $r->name }}</h3>
                    <p class="text-xs text-gray-400 font-medium mb-6 flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-3 h-3"></i> {{ Str::limit($r->address, 30) }}
                    </p>

                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="bg-gray-50 p-4 rounded-2xl text-center">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Commandes</p>
                            <p class="text-lg font-black text-[#2C3E3F]">{{ $r->orders_count }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-2xl text-center">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Note Moy.</p>
                            <p class="text-lg font-black text-orange-500">{{ number_format($r->averageRating(), 1) }} ★</p>
                        </div>
                    </div>

                    <a href="{{ route('admin.restaurants.show', $r->id) }}" class="w-full bg-[#2C3E3F] text-white py-4 rounded-2xl font-extrabold text-xs uppercase tracking-[0.2em] flex items-center justify-center gap-2 group-hover:bg-orange-500 transition-all">
                        Voir les statistiques <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            @endforeach
        </div>
    </main>

    <!-- Modal de création -->
    <div id="createRestaurantModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-[#0F172A]/40 backdrop-blur-sm transition-opacity" onclick="closeModal('createRestaurantModal')"></div>
            
            <div class="relative bg-white rounded-[2.5rem] w-full max-w-2xl p-10 shadow-2xl border border-gray-100 overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-teal-500 to-orange-500"></div>
                
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h3 class="text-3xl font-extrabold text-[#2C3E3F] tracking-tight">Nouveau Partenaire</h3>
                        <p class="text-gray-400 font-medium">Configurez un nouvel établissement yayaFood.</p>
                    </div>
                    <button onclick="closeModal('createRestaurantModal')" class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <form action="{{ route('admin.restaurants.create') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-[#2C3E3F] ml-2">Nom du Restaurant</label>
                            <input type="text" name="name" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all font-semibold" placeholder="ex: Le Petit Bistro">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-[#2C3E3F] ml-2">Téléphone</label>
                            <input type="text" name="phone" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all font-semibold" placeholder="+33 ...">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-[#2C3E3F] ml-2">Email Administrateur</label>
                            <input type="email" name="email" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all font-semibold" placeholder="admin@restaurant.com">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-[#2C3E3F] ml-2">Mot de passe (8 chiffres)</label>
                            <div class="relative">
                                <input type="text" name="password" id="generatedPassword" required maxlength="8" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all font-semibold" placeholder="12345678">
                                <button type="button" onclick="generatePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-teal-600 hover:text-teal-700 font-bold text-[10px] uppercase tracking-wider">Générer</button>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-[#2C3E3F] ml-2">Adresse Complète</label>
                        <input type="text" name="address" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all font-semibold" placeholder="ex: 123 Rue de la Gastronomie, Paris">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-[#2C3E3F] ml-2">Description</label>
                        <textarea name="description" rows="3" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all font-semibold resize-none" placeholder="Décrivez le restaurant en quelques mots..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-[#2C3E3F] ml-2">Logo <span class="text-orange-500">(Max 10Mo)</span></label>
                            <input type="file" name="logo" accept="image/*" onchange="checkFileSize(this)" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-[#2C3E3F] file:text-white hover:file:bg-orange-500 transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-[#2C3E3F] ml-2">Image de Couverture <span class="text-orange-500">(Max 10Mo)</span></label>
                            <input type="file" name="banner" accept="image/*" onchange="checkFileSize(this)" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-[#2C3E3F] file:text-white hover:file:bg-orange-500 transition-all">
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" onclick="closeModal('createRestaurantModal')" class="flex-1 bg-gray-100 text-gray-500 py-4 rounded-2xl font-extrabold text-xs uppercase tracking-[0.2em] hover:bg-gray-200 transition-all">Annuler</button>
                        <button type="submit" class="flex-[2] bg-teal-600 text-white py-4 rounded-2xl font-extrabold text-xs uppercase tracking-[0.2em] hover:bg-teal-700 transition-all shadow-lg shadow-teal-600/20">Créer le restaurant</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            if (id === 'createRestaurantModal') {
                generatePassword();
            }
        }

        function generatePassword() {
            const password = Math.floor(10000000 + Math.random() * 90000000).toString();
            document.getElementById('generatedPassword').value = password;
        }

        function checkFileSize(input) {
            if (input.files && input.files[0]) {
                const fileSize = input.files[0].size / 1024 / 1024; // in MB
                if (fileSize > 10) {
                    alert('Le fichier est trop volumineux. La taille maximale autorisée est de 10Mo.');
                    input.value = '';
                }
            }
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeModal('createRestaurantModal');
            }
        });
    </script>
</body>
</html>