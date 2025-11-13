@extends('layouts.user')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-4 sm:py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mt-8 sm:mt-12 lg:mt-20">
            <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 max-w-2xl mx-auto border border-blue-100">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-blue-700 mb-4">
                    Selamat datang, {{ $user->full_name }}!
                </h1>
                <p class="text-gray-700 text-base sm:text-lg mb-4">
                    Role kamu saat ini: <strong class="text-blue-600">{{ $user->role }}</strong>
                </p>
                <div class="mt-4">
                    @if(isset($isWorking) && $isWorking)
                        <span class="inline-block text-sm sm:text-base px-4 py-2 rounded-full bg-red-100 text-red-700 font-semibold border border-red-200">
                            🔴 Working
                        </span>
                    @else
                        <span class="inline-block text-sm sm:text-base px-4 py-2 rounded-full bg-green-100 text-green-700 font-semibold border border-green-200">
                            🟢 Available
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
