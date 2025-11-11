<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User - Project Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex">

    {{-- Sidebar --}}
    <aside class="w-64 bg-blue-700 text-white min-h-screen flex flex-col justify-between">
        <div class="p-4">
            <h2 class="text-xl font-bold mb-6">Project Management</h2>
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('users.dashboard') }}" class="block py-2 px-3 hover:bg-blue-600 rounded">Dashboard</a>
                    </li>
                    {{-- Tambahkan menu lain untuk user jika ada --}}
                    <li>
                        <a href="#" class="block py-2 px-3 hover:bg-blue-600 rounded">My Projects</a>
                    </li>
                </ul>
            </nav>
        </div>

        {{-- Logout button --}}
        <div class="p-4 border-t border-blue-600">
            <form action="{{ route('logout') }}" method="GET">
                <button
                    type="submit"
                    class="w-full bg-red-600 py-2 rounded text-white font-bold hover:bg-red-700 transition">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Content --}}
    <main class="flex-1 p-6">
        @yield('content')
    </main>

</body>
</html>
