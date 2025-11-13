<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Developer Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    {{-- Sidebar (fixed) --}}
    <aside class="fixed left-0 top-0 bottom-0 w-64 bg-gray-900 text-white p-5 overflow-hidden">
        <h2 class="text-xl font-bold mb-6">Developer Panel</h2>
        <ul>
            <li><a href="{{ route('developer.dashboard') }}" class="block py-2 px-3 rounded hover:bg-gray-700">🏠 Dashboard</a></li>
            <li><a href="{{ route('developer.my-team') }}" class="block py-2 px-3 rounded hover:bg-gray-700">👥 My Team</a></li>
            <li><a href="#" class="block py-2 px-3 rounded hover:bg-gray-700">💼 My Tasks</a></li>
            <li class="mt-4">
                <a href="{{ route('logout') }}" class="block w-full text-left py-2 px-3 rounded bg-red-600 hover:bg-red-700">🚪 Logout</a>
            </li>
        </ul>
    </aside>

    {{-- Main Content --}}
    <main class="ml-64 p-6">
        @yield('content')
    </main>

    {{-- Scripts stack from child views --}}
    @stack('scripts')

</body>
</html>
