<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - YayaFood Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }
        .brand-gradient {
            background: linear-gradient(135deg, #2C3E3F 0%, #1A2829 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .input-focus:focus {
            ring-color: #2C3E3F;
            border-color: #2C3E3F;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 brand-gradient relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-orange-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-teal-500/10 rounded-full blur-3xl"></div>

    <div class="w-full max-w-[1100px] grid grid-cols-1 lg:grid-cols-2 glass-card rounded-[3rem] overflow-hidden shadow-2xl relative z-10">
        
        <!-- Left Side: Branding/Image -->
        <div class="hidden lg:flex flex-col justify-between p-16 brand-gradient text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-full h-full opacity-10 pointer-events-none">
                <svg width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path>
                </svg>
            </div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-12">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-[#2C3E3F]">
                        <i data-lucide="chef-hat" class="w-7 h-7"></i>
                    </div>
                    <h1 class="text-3xl font-black tracking-tighter">YayaFood<span class="text-orange-400">.</span></h1>
                </div>
                
                <h2 class="text-5xl font-extrabold leading-tight mb-6">Simplifiez la gestion de votre <span class="text-orange-400 text-6xl">restaurant.</span></h2>
                <p class="text-gray-300 text-lg font-medium max-w-md">Prenez le contrôle de vos commandes, menus et avis clients depuis une interface intuitive et puissante.</p>
            </div>

            <div class="relative z-10 flex items-center gap-6">
                <div class="flex -space-x-4">
                    <img class="w-12 h-12 rounded-full border-4 border-[#2C3E3F] bg-gray-200" src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" alt="User">
                    <img class="w-12 h-12 rounded-full border-4 border-[#2C3E3F] bg-gray-200" src="https://api.dicebear.com/7.x/avataaars/svg?seed=Aneka" alt="User">
                    <img class="w-12 h-12 rounded-full border-4 border-[#2C3E3F] bg-gray-200" src="https://api.dicebear.com/7.x/avataaars/svg?seed=James" alt="User">
                </div>
                <p class="text-sm font-bold text-gray-300 tracking-wide uppercase">+500 Restaurateurs nous font confiance</p>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="p-8 lg:p-20 bg-white flex flex-col justify-center">
            <div class="mb-10">
                <div class="lg:hidden flex items-center gap-2 mb-8">
                    <div class="w-10 h-10 bg-[#2C3E3F] rounded-xl flex items-center justify-center text-white">
                        <i data-lucide="chef-hat" class="w-6 h-6"></i>
                    </div>
                    <h1 class="text-2xl font-black tracking-tighter text-[#2C3E3F]">YayaFood<span class="text-orange-500">.</span></h1>
                </div>
                <h3 class="text-3xl font-black text-[#2C3E3F] mb-2 uppercase tracking-tight">Bon retour !</h3>
                <p class="text-gray-400 font-bold text-sm uppercase tracking-widest">Connectez-vous pour gérer votre établissement</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl text-green-600 text-sm font-bold flex items-center gap-3">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Adresse Email</label>
                    <div class="relative group">
                        <div class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#2C3E3F] transition-colors">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="exemple@yayafood.app"
                               class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl pl-14 pr-6 py-4 text-sm font-bold text-[#2C3E3F] outline-none focus:border-[#2C3E3F] focus:ring-4 focus:ring-[#2C3E3F]/5 transition-all">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-[10px] font-black uppercase tracking-wider mt-1 ml-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center px-2">
                        <label for="password" class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Mot de passe</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[10px] font-black text-orange-500 hover:text-orange-600 uppercase tracking-widest transition-colors">Oublié ?</a>
                        @endif
                    </div>
                    <div class="relative group">
                        <div class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#2C3E3F] transition-colors">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>
                        <input id="password" type="password" name="password" required placeholder="••••••••"
                               class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl pl-14 pr-6 py-4 text-sm font-bold text-[#2C3E3F] outline-none focus:border-[#2C3E3F] focus:ring-4 focus:ring-[#2C3E3F]/5 transition-all">
                    </div>
                    @error('password')
                        <p class="text-red-500 text-[10px] font-black uppercase tracking-wider mt-1 ml-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center gap-3 px-2">
                    <label class="relative flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="sr-only peer">
                        <div class="w-10 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#2C3E3F]"></div>
                    </label>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Se souvenir de moi</span>
                </div>

                <button type="submit" class="w-full brand-gradient text-white py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:shadow-xl hover:shadow-[#2C3E3F]/20 transition-all flex items-center justify-center gap-3 group">
                    <span>Se connecter au dashboard</span>
                    <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                </button>
            </form>

            <footer class="mt-12 text-center">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">&copy; {{ date('Y') }} YayaFood. Tous droits réservés.</p>
            </footer>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
