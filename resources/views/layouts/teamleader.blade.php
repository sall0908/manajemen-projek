<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Leader Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    {{-- Sidebar (fixed) --}}
    <aside class="fixed left-0 top-0 bottom-0 w-64 bg-blue-700 text-white p-5 overflow-hidden">
        <div>
            <h2 class="text-2xl font-bold mb-6">Team Leader Panel</h2>
            <ul>
                <li>
                    <a href="{{ route('teamleader.dashboard') }}" class="block py-2 px-3 rounded hover:bg-blue-600">📋 Dashboard</a>
                </li>
                <li>
                    <a href="{{ route('teamleader.my-team') }}" class="block py-2 px-3 rounded hover:bg-blue-600">👥 My Team</a>
                </li>
                <li>
                    <a href="{{ route('teamleader.project.boards', 1) }}" class="block py-2 px-3 rounded hover:bg-blue-600">📁 Projects</a>
                </li>
                <li>
                    <a href="{{ route('teamleader.blockers.index') }}" class="block py-2 px-3 rounded hover:bg-blue-600">🚨 Blockers</a>
                </li>
                <li class="mt-4">
                    <form action="{{ route('logout') }}" method="GET">
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded transition">🚪 Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="ml-64 p-6">
        @yield('content')
    </main>

</body>
</html>
