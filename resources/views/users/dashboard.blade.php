@extends('layouts.user')

@section('content')
<div class="text-center mt-20">
    <h1 class="text-3xl font-bold text-blue-700">
        Selamat datang, {{ $user->full_name }}!
    </h1>
    <p class="text-gray-700 mt-2">
        Role kamu saat ini: <strong>{{ $user->role }}</strong>
        @if(isset($isWorking) && $isWorking)
            <span class="ml-3 inline-block text-xs px-2 py-1 rounded-full bg-red-100 text-red-700">Working</span>
        @else
            <span class="ml-3 inline-block text-xs px-2 py-1 rounded-full bg-green-100 text-green-700">Available</span>
        @endif
    </p>


</div>
@endsection
