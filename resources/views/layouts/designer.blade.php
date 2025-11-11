<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Designer Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex bg-gray-50">

    {{-- Sidebar --}}
    <aside class="w-64 bg-purple-800 text-white min-h-screen p-5">
        <h2 class="text-xl font-bold mb-6">Designer Panel</h2>
        <ul>
            <li><a href="{{ route('designer.dashboard') }}" class="block py-2 px-3 rounded hover:bg-purple-700">🏠 Dashboard</a></li>
            <li><a href="{{ route('designer.my-team') }}" class="block py-2 px-3 rounded hover:bg-purple-700">👥 My Team</a></li>
            <li><a href="#" class="block py-2 px-3 rounded hover:bg-purple-700">🎨 My Designs</a></li>
            <li class="mt-4">
                <a href="{{ route('logout') }}" class="block w-full text-left py-2 px-3 rounded bg-red-600 hover:bg-red-700">
                    🚪 Logout
                </a>
            </li>
        </ul>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 p-6">
        @yield('content')
    </main>

</body>
</html>
