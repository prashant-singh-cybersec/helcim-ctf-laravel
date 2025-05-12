@extends('base')

@section('body')
<div class="min-h-screen bg-gradient-to-br from-yellow-200 via-orange-100 to-pink-100 flex items-center justify-center text-center -mt-5">
    <div class="bg-white shadow-lg p-8 rounded-lg max-w-md">
        <h2 class="text-2xl font-bold text-red-600 mb-4">Access Denied</h2>
        <p class="text-gray-700">{{ $message }}</p>
    </div>
</div>
@endsection
