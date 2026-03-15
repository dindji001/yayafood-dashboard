<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changement de mot de passe obligatoire - YayaFood</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; color: #0F172A; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 bg-[#2C3E3F]">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-orange-500 to-teal-500"></div>
            
            <div class="text-center mb-10">
                <div class="w-20 h-20 bg-[#2C3E3F] rounded-3xl flex items-center justify-center text-white mx-auto mb-6 shadow-xl shadow-[#2C3E3F]/20">
                    <i data-lucide="shield-check" class="w-10 h-10"></i>
                </div>
                <h1 class="text-3xl font-black text-[#2C3E3F] mb-3">Sécurité</h1>
                <p class="text-gray-400 font-medium">Pour votre première connexion, vous devez définir un nouveau mot de passe personnel.</p>
            </div>

            <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-[#2C3E3F] ml-2">Nouveau mot de passe</label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input type="password" name="password" required 
                               class="w-full bg-gray-50 border border-gray-100 rounded-2xl pl-14 pr-6 py-4 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all font-semibold" 
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-[#2C3E3F] ml-2">Confirmer le mot de passe</label>
                    <div class="relative">
                        <i data-lucide="check-circle-2" class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input type="password" name="password_confirmation" required 
                               class="w-full bg-gray-50 border border-gray-100 rounded-2xl pl-14 pr-6 py-4 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all font-semibold" 
                               placeholder="••••••••">
                    </div>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-100 rounded-2xl p-4">
                        <ul class="text-xs text-red-600 font-bold list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <button type="submit" class="w-full bg-[#2C3E3F] hover:bg-orange-500 text-white py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all shadow-lg shadow-[#2C3E3F]/10 flex items-center justify-center gap-3">
                    Mettre à jour <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </button>
            </form>

            <div class="mt-8 text-center">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-gray-400 hover:text-red-500 transition-colors uppercase tracking-widest">
                        Se déconnecter
                    </button>
                </form>
            </div>
        </div>
        
        <p class="text-center text-white/40 text-[10px] font-bold uppercase tracking-[0.3em] mt-8">YayaFood Security System v2.0</p>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>