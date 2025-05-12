@extends('base')

@section('body')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <div class="p-6 text-center bg-gradient-to-r from-[#815AF0] to-[#FFD494] rounded-lg shadow-md">
            <h1 class="text-2xl font-bold text-white mb-4">Success!</h1>
            <p class="text-lg text-white">{{ $message }}</p>
        </div>
        <div class="mt-6 text-center">
            <a href="/issue_invoice" class="px-6 py-3 bg-indigo-500 text-white rounded-lg font-semibold hover:bg-indigo-600">
                Create Another Invoice
            </a>
        </div>

        <div class="mt-10 text-center">
            <a href="/list_invoices" class="px-6 py-3 bg-indigo-500 text-white rounded-lg font-semibold hover:bg-indigo-600">
                List Available Invoices
            </a>
        </div>
    </div>
</div>
@endsection
