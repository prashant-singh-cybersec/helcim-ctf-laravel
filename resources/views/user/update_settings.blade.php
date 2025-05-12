@extends('base')

@section('body')
<div class="min-h-screen bg-gradient-to-r from-yellow-100 via-pink-100 to-purple-100 py-16 px-6 -mt-5">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-10 animate__animated animate__fadeIn">
        
        <!-- Sidebar -->
        <aside class="w-full md:w-1/4 bg-white rounded-2xl shadow-xl p-6">
            <h2 class="text-2xl font-extrabold text-indigo-700 mb-6 text-center">Navigation</h2>
            <ul class="space-y-4 text-center">
                <li>
                    <a href="/dashboard" class="block text-indigo-600 hover:text-indigo-800 hover:underline font-semibold transition">🏠 Dashboard</a>
                </li>
                <li>
                    <a href="/update_settings" class="block text-indigo-800 font-bold hover:underline transition">⚙️ Settings</a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="w-full md:w-3/4 bg-white rounded-2xl shadow-2xl p-10">
            <h2 class="text-4xl font-extrabold text-indigo-700 text-center mb-8 animate__animated animate__bounceIn">Update Your Settings</h2>

            <form id="updateSettingsForm" enctype="multipart/form-data" class="space-y-10">

                <!-- Mobile Number -->
                <div>
                    <label for="mobileNumber" class="block text-lg font-semibold text-gray-700 mb-2">📱 Mobile Number</label>
                    <input type="tel" id="mobileNumber" name="mobileNumber" value="{{ $user->mobile_number }}"
                        class="w-full px-5 py-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-gray-50">
                </div>

                <!-- Logo Upload & Preview -->
                <div class="flex flex-col items-center gap-2">
                    <label for="logo" class="relative group w-40 h-40 rounded-full shadow-xl overflow-hidden border-4 border-indigo-100 cursor-pointer transition hover:scale-105">
                        <input type="file" id="logo" name="logo" class="absolute inset-0 opacity-0 z-10" onchange="showFilename(this)">
                        @if ($user->image_path)
                            <img src="{{ $user->image_path }}" alt="Current Logo" class="object-cover w-full h-full transition-all duration-300 group-hover:opacity-70">
                        @else
                            <div class="flex items-center justify-center w-full h-full bg-gray-200 text-gray-500 text-lg">
                                Upload
                            </div>
                        @endif
                        <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs py-2 text-center hidden group-hover:block">
                            Click to Upload New
                        </div>
                    </label>
                    <p id="filenameDisplay" class="text-sm text-gray-700"></p>
                </div>

                <!-- Image URL -->
                <div>
                    <label for="imageUrl" class="block text-lg font-semibold text-gray-700 mb-2">🌐 Image URL (Optional)</label>
                    <input type="url" id="imageUrl" name="imageUrl" placeholder="https://example.com/image.jpg"
                        class="w-full px-5 py-3 border border-gray-300 rounded-xl shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-2">Provide a link if you don't want to upload a file manually.</p>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="button" onclick="submitSettingsForm()"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-lg py-3 px-10 rounded-full shadow-lg transition duration-300">
                        💾 Save Changes
                    </button>
                </div>
            </form>

            <!-- Alert Message -->
            <div id="alertMessage" class="hidden mt-8 text-center text-white py-3 px-6 rounded-lg shadow-lg animate__animated animate__fadeIn"></div>
        </main>
    </div>
</div>

<script>
    function showFilename(input) {
        const file = input.files[0];
        const filenameDisplay = document.getElementById('filenameDisplay');
        filenameDisplay.textContent = file ? file.name : 'No file chosen';
    }

    async function submitSettingsForm() {
        const form = document.getElementById('updateSettingsForm');
        const formData = new FormData(form);
        const alertMessage = document.getElementById('alertMessage');

        try {
            const response = await fetch('/api/update_settings', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                alertMessage.textContent = data.message;
                alertMessage.className = 'bg-green-500 text-white text-center py-3 px-6 rounded-lg shadow-lg animate__animated animate__fadeIn';
            } else {
                throw new Error(data.error || 'Something went wrong.');
            }

        } catch (error) {
            alertMessage.textContent = error.message;
            alertMessage.className = 'bg-red-500 text-white text-center py-3 px-6 rounded-lg shadow-lg animate__animated animate__fadeIn';
        }

        alertMessage.classList.remove('hidden');
    }
</script>
@endsection
