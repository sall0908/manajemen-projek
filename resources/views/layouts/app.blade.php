<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Project Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-blue-900 text-white flex flex-col justify-between">
        <div class="p-4">
            <h2 class="text-xl font-bold mb-6">Project Management</h2>
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="block py-2 px-3 hover:bg-blue-700 rounded">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.projects') }}" class="block py-2 px-3 hover:bg-blue-700 rounded">Projects</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users') }}" class="block py-2 px-3 hover:bg-blue-700 rounded">Users</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports.users') }}" class="block py-2 px-3 hover:bg-blue-700 rounded">Reports</a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-3 hover:bg-blue-700 rounded">Settings</a>
                    </li>
                </ul>
            </nav>
        </div>

        {{-- Logout --}}
        <div class="p-4 border-t border-blue-800">
            <form action="{{ route('logout') }}" method="GET">
                <button type="submit" class="w-full bg-red-600 py-2 rounded text-white font-bold hover:bg-red-700 transition">
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
