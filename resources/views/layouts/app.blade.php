<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Project Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
</body>
<body class="bg-gray-100 min-h-screen">

    {{-- Sidebar (fixed) --}}
    <aside class="fixed left-0 top-0 bottom-0 w-64 bg-blue-900 text-white overflow-hidden p-4">
        <div>
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
                    <li class="mt-4">
                        <form action="{{ route('logout') }}" method="GET">
                            <button type="submit" class="w-full bg-red-600 py-2 rounded text-white font-bold hover:bg-red-700 transition">Logout</button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    {{-- Content (shifted right) --}}
    <main class="ml-64 p-6">
        @yield('content')
    </main>

</body>
</html>
