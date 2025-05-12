@extends('base')

@section('body')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-yellow-100 to-purple-100 py-16 px-6 -mt-10">
    <div class="bg-white p-10 rounded-2xl shadow-2xl w-full max-w-4xl transition-all duration-300">
        <!-- Title -->
        <h1 class="text-4xl font-extrabold text-indigo-700 mb-8 text-center drop-shadow-sm">
            🔍 Universal Search Portal
        </h1>

        <!-- Search Bar -->
        <form id="searchForm" method="GET" action="{{ route('universal_search') }}" class="relative mb-8">
            <input type="text" name="query" id="searchQuery"
                   placeholder="Search invoices, customers, users..."
                   value="{{ request('query') }}"
                   class="w-full px-5 py-4 border-2 border-indigo-300 rounded-full text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-400 shadow-md">
            <button type="submit"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 px-5 py-2 bg-indigo-600 text-white font-semibold rounded-full shadow hover:bg-indigo-700 transition">
                Search
            </button>
        </form>

        <!-- Results -->
        <div id="searchResults">
            @if (!empty($query))
                @if (!empty($results))
                    <h2 class="text-2xl font-semibold text-indigo-800 mb-6 text-center">Results for: <span class="text-purple-600 italic">{!! $query !!}</span></h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        @foreach ($results as $result)
                            <div class="p-6 bg-white rounded-xl shadow hover:shadow-xl border border-indigo-100 transition-all">
                                <p class="text-indigo-600 font-semibold mb-2">📁 Type: {{ $result['type'] }}</p>
                                <p class="text-gray-700"><strong>Name:</strong> {!! $result['name'] !!}</p>
                                <p class="text-gray-700"><strong>ID:</strong> {{ $result['id'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center mt-10">
                        <h2 class="text-xl font-semibold text-red-600">❌ No Results Found</h2>
                        <p class="text-gray-600 mt-2">We couldn’t find anything matching <span class="italic font-medium text-indigo-600">{!! $query !!}</span>. Try again with different keywords.</p>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
