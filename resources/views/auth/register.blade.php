@extends('base')

@section('title', 'Register')

@section('body')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-yellow-100 via-pink-100 to-purple-100 flex py-12 px-4 sm:px-6 lg:px-8 -mt-10">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-indigo-600 mb-2">Create Your Account</h2>
            <p class="text-sm text-gray-600">Register to access the Invotek Invoice Management CTF</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded mt-4">
                <ul class="text-sm list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input id="email" name="email" type="email" required autofocus
                    class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" name="password" type="password" required
                    class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="agree_terms" id="agree_terms" required
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="agree_terms" class="ml-2 block text-sm text-gray-700">
                    I agree to the terms and conditions
                </label>
            </div>

            <div>
                <button type="submit"
                    class="w-full py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition">
                    Register
                </button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">Already have an account? <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Login here</a>.</p>
        </div>
    </div>
</div>
@endsection
