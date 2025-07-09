<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: radial-gradient(circle at 20% 20%, #232946 40%, #181c2f 100%);
            min-height: 100vh;
        }
        .glass {
            background: rgba(36, 40, 59, 0.85);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 1.5rem;
            border: 1px solid rgba(255,255,255,0.08);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="glass w-full max-w-md p-8 text-center">
            <h1 class="text-3xl font-bold mb-8 text-white tracking-wide">Inscription</h1>
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf
                <div class="text-left">
                    <label for="name" class="block text-sm font-medium text-gray-200 mb-1">Nom</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" class="w-full px-4 py-2 rounded bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('name')
                        <div class="text-red-400 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-left">
                    <label for="email" class="block text-sm font-medium text-gray-200 mb-1">Adresse email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" class="w-full px-4 py-2 rounded bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('email')
                        <div class="text-red-400 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-left">
                    <label for="password" class="block text-sm font-medium text-gray-200 mb-1">Mot de passe</label>
                    <input id="password" name="password" type="password" required autocomplete="new-password" class="w-full px-4 py-2 rounded bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('password')
                        <div class="text-red-400 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-left">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-200 mb-1">Confirmer le mot de passe</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="w-full px-4 py-2 rounded bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('password_confirmation')
                        <div class="text-red-400 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-left">
                    <label for="code_inscription" class="block text-sm font-medium text-gray-200 mb-1">Code d'inscription</label>
                    <input id="code_inscription" name="code_inscription" type="text" value="{{ old('code_inscription') }}" required class="w-full px-4 py-2 rounded bg-gray-800 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('code_inscription')
                        <div class="text-red-400 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="w-full py-2 rounded bg-indigo-500 hover:bg-indigo-600 text-white font-semibold shadow transition">S'inscrire</button>
            </form>
            <div class="mt-6 flex flex-col items-center">
                <a href="{{ route('login') }}" class="text-indigo-300 hover:underline text-sm">Déjà inscrit ?</a>
            </div>
        </div>
    </div>
</body>
</html> 