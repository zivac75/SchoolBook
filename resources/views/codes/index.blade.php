<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codes d'inscription</title>
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
        <div class="glass w-full max-w-4xl p-8 text-center">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-white">Codes d'inscription</h2>
                <a href="{{ route('codes.create') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded transition">Créer un code</a>
            </div>
            @if(session('success'))
                <div class="bg-green-600 bg-opacity-20 border border-green-400 text-green-200 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Rôle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Créé par</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-900 divide-y divide-gray-700">
                        @foreach($codes as $code)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-100">
                                    {{ $code->code }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                                    {{ $code->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                                    {{ $code->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                                    {{ $code->role }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $code->utilise ? 'bg-red-600 text-red-100' : 'bg-green-600 text-green-100' }}">
                                        {{ $code->utilise ? 'Utilisé' : 'Disponible' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                                    {{ $code->creePar ? $code->creePar->name : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-200">
                                    <form action="{{ route('codes.toggle', $code) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-indigo-300 hover:text-indigo-500">
                                            {{ $code->utilise ? 'Marquer comme disponible' : 'Marquer comme utilisé' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $codes->links() }}
            </div>
        </div>
    </div>
</body>
</html> 