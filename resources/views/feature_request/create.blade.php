@extends('base')

@section('title', 'Request a New Feature')

@section('body')
    <div class="flex flex-col md:flex-row min-h-screen shadow-md bg-gradient-to-r from-yellow-300 to-purple-400 px-4 py-10 -mt-5">
        <!-- Sidebar -->
        <div class="w-full md:w-1/4 bg-white rounded-xl shadow-lg p-6 mb-6 md:mb-0 md:mr-6">
            <h3 class="text-2xl font-extrabold text-purple-700 mb-6 border-b pb-2">📋 BOARDS</h3>
            <ul class="space-y-4">
                <li>
                    <a href="#" class="text-lg font-semibold text-purple-600 hover:text-purple-800 transition">📝 Request A
                        Feature</a>
                </li>
            </ul>

        </div>

        <!-- Main content -->
        <div class="flex-1">
            <div class="bg-white rounded-2xl p-8 shadow-2xl max-w-4xl mx-auto animate-fade-in">
                <h2 class="text-3xl font-bold mb-2 text-purple-800">🚀 Request A Feature</h2>
                <p class="text-gray-600 mb-6 text-sm">We’re eager to know all the new features you’d like to use!</p>

                @if(session('success'))
                    <div class="bg-green-100 text-green-800 text-sm px-4 py-3 rounded mb-4 shadow">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('feature-request.store') }}" enctype="multipart/form-data"
                    class="space-y-5">
                    @csrf

                    <input type="text" name="title" placeholder="Short, descriptive title" required
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:outline-none shadow-sm">

                    <textarea name="details" rows="4" placeholder="Any additional details..."
                        class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-purple-500 focus:outline-none shadow-sm"></textarea>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Attach .txt file (optional)</label>
                        <input type="file" name="attachment" accept=".txt"
                            class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200 transition" />
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button type="reset"
                            class="px-5 py-2 rounded-md bg-gray-300 text-gray-700 hover:bg-gray-400 transition">Cancel</button>
                        <button type="submit"
                            class="px-6 py-2 rounded-md bg-purple-600 text-white hover:bg-purple-700 transition shadow-md hover:shadow-lg">Submit
                            Request</button>
                    </div>
                </form>
            </div>

            <!-- Submitted Requests -->
            <div class="mt-10 max-w-4xl mx-auto space-y-6">
                @foreach($requests as $req)
                    <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition transform hover:scale-[1.01]">
                        <h4 class="text-xl font-bold text-purple-800">{{ $req->title }}</h4>
                        <p class="text-sm text-gray-700 mt-2">{!! $req->details !!}</p>
                        @php
    if ($req->attachment) {
        $token = basename($req->attachment); // e.g., "a1s2c3c4v4vv43.txt"
    }
@endphp
                        @if($req->attachment)
                            <a href="{{ url('/download?filename=' . urlencode($token) . '&token=' . urlencode(pathinfo($token, PATHINFO_FILENAME))) }}"
                                class="text-blue-600 text-sm mt-2 inline-block hover:underline">
                                📎 View Attachment
                            </a>
                        @endif
                        <p class="text-xs text-gray-400 mt-2">Submitted by User #{{ $req->user_id }} on
                            {{ $req->created_at->format('d M Y, H:i') }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out;
        }
    </style>
@endsection