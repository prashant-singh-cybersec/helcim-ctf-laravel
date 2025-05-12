@extends('base')

@section('body')
<div class="min-h-screen bg-gradient-to-r from-yellow-100 via-purple-100 to-pink-100 flex flex-col  -mt-3">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-purple-600 to-yellow-400 text-white p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Analytics Dashboard</h1>
            <div class="flex items-center space-x-4 bg-white text-gray-800 px-4 py-2 rounded-full shadow-md">
                <div class="rounded-full bg-gradient-to-r from-yellow-400 to-purple-600 w-10 h-10 flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr(Auth::user()->email, 0, 1)) }}
                </div>
                <div class="text-center">
                    <div class="text-sm">Welcome,</div>
                    <div class="font-bold">{{ Auth::user()->email }}</div>
                    <div class="text-sm mt-1">
                        @if(Auth::user()->is_paid_user)
                            <span class="text-green-600 font-semibold">Premium User</span>
                        @else
                            <span class="text-red-600 font-semibold">Free User</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto flex flex-1 mt-6">
        <!-- Sidebar -->
        <aside class="w-1/4 bg-white p-6 rounded-xl shadow-lg mr-6">
            <ul class="space-y-4 font-semibold text-indigo-700">
                <li><a href="/dashboard" class="hover:underline">Dashboard</a></li>
                <li><a href="/advanced_analytics" class="font-bold hover:underline">Advanced Reporting</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 bg-white p-8 rounded-xl shadow-lg animate-fade-in">
            <h2 class="text-3xl font-extrabold text-gray-800 text-center mb-8">📊 Analytics Overview</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                <!-- Total Invoices -->
                <div class="bg-gradient-to-br from-indigo-200 to-indigo-400 p-6 rounded-lg text-white shadow-md transform transition hover:scale-105">
                    <h3 class="text-lg font-semibold mb-2">Total Invoices</h3>
                    <p class="text-4xl font-bold">{{ $totalInvoices }}</p>
                </div>

                <!-- Total Revenue -->
                <div class="bg-gradient-to-br from-green-200 to-green-500 p-6 rounded-lg text-white shadow-md transform transition hover:scale-105">
                    <h3 class="text-lg font-semibold mb-2">Total Revenue</h3>
                    <p class="text-4xl font-bold">${{ number_format($totalRevenue, 2, '.', ',') }}</p>
                </div>
            </div>

            <!-- Status Breakdown -->
            <div class="mt-10">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">📌 Invoice Status Breakdown</h3>
                <ul class="space-y-3">
                    @foreach ($statusBreakdown as $status => $count)
                        <li class="bg-gray-100 p-4 rounded-lg shadow-sm flex justify-between">
                            <span class="font-medium text-gray-700">{{ $status }}</span>
                            <span class="font-bold text-indigo-600">{{ $count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Revenue by Month -->
            <div class="mt-10">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">📅 Revenue by Month</h3>
                <table class="w-full text-left bg-white border border-gray-300 rounded-lg shadow-md">
                    <thead class="bg-indigo-100 text-indigo-700">
                        <tr>
                            <th class="px-4 py-2">Month</th>
                            <th class="px-4 py-2 text-right">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($revenueByMonth as $month => $revenue)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $month }}</td>
                                <td class="px-4 py-2 text-right font-semibold">${{ number_format($revenue, 2, '.', ',') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<style>
    @keyframes fade-in {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.8s ease-out;
    }
</style>
@endsection
