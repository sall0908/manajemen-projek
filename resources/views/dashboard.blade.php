<h1>Selamat datang, {{ Auth::user()->full_name }}</h1>

@if(Auth::user()->role === 'admin')
    <p>Anda adalah <b>Admin</b>.</p>
@else
    <p>Anda adalah <b>{{ Auth::user()->sub_role ?? 'Users' }}</b>.</p>
@endif

<a href="{{ route('logout') }}">Logout</a>
