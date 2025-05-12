{{-- resources/views/auth/login.blade.php --}}
@extends('base')

@section('body')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-yellow-100 via-pink-100 to-purple-100 flex py-12 px-4 sm:px-6 lg:px-8 -mt-10">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg -mt-10">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-indigo-600 mb-2">Welcome Back!</h2>
            <p class="text-sm text-gray-600">Login to your Invotek Dashboard</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-4">
                {{ session('error') }}
            </div>
        @endif

        <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input id="email" name="email" type="email" autocomplete="email" required autofocus
                    class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required
                    class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
            </div>

            <div class="flex items-center justify-between">
                <a href="/register" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Don't have an account?</a>
            </div>

            <div>
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Sign In
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
