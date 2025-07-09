<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen">
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="glass w-full max-w-md p-8 text-center">
            <h1 class="text-3xl font-bold mb-4 text-white tracking-wide">Bienvenue sur votre SchoolBook !</h1>
            <p class="text-gray-300 mb-6">Vous êtes connecté. Utilisez le menu pour naviguer dans l'application.</p>
            <a href="/codes" class="inline-block bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded transition mb-2">Gestion des codes d'inscription</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="mt-4 w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">Se déconnecter</button>
            </form>
        </div>
    </div>
</body>

</html>