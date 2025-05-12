@extends('base')

@section('body')
<div class="flex items-center justify-center min-h-screen bg-gradient-to-r from-yellow-100 via-pink-100 to-purple-100 py-16 px-4 animate__animated animate__fadeIn -mt-10">
    <div class="bg-white rounded-3xl shadow-2xl p-10 w-full max-w-lg animate__animated animate__zoomIn">

        <div class="text-center mb-10">
            <h2 class="text-4xl font-extrabold text-indigo-700">Create New Customer</h2>
            <p class="mt-2 text-gray-600">Fill in the details to add a new customer to your organization.</p>
        </div>

        <form id="customerForm" class="space-y-8">

            <!-- Customer Name -->
            <div>
                <label for="CustName" class="block text-sm font-bold text-gray-700 mb-2">👤 Customer Name</label>
                <input type="text" id="CustName" name="CustName" required
                       class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
            </div>

            <!-- Customer Email -->
            <div>
                <label for="email" class="block text-sm font-bold text-gray-700 mb-2">📧 Email Address</label>
                <input type="email" id="email" name="email" required
                       class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
            </div>

            <!-- Hidden Organization ID -->
            <input type="hidden" id="organization_id" name="organization_id" value="1">

            <!-- CSRF Token -->
            <input type="hidden" id="csrf_token" name="csrf_token" value="{{ csrf_token() }}">

            <!-- Submit Button -->
            <div class="text-center">
                <button type="button" onclick="submitCustomerForm()"
                        class="w-full py-3 px-6 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-full shadow-lg transform transition hover:scale-105">
                    ➕ Create Customer
                </button>
            </div>
        </form>

        <!-- Feedback Message -->
        <div id="message" class="mt-8 text-center hidden text-lg animate__animated animate__fadeIn"></div>

    </div>
</div>

<script>
    async function submitCustomerForm() {
        const customerName = document.getElementById('CustName').value;
        const email = document.getElementById('email').value;
        const organizationId = document.getElementById('organization_id').value;
        const csrfToken = document.getElementById('csrf_token').value;
        const messageDiv = document.getElementById('message');

        try {
            const response = await fetch("/api/customers", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    CustName: customerName,
                    email: email,
                    organization_id: parseInt(organizationId, 10),
                    csrf_token: csrfToken
                })
            });

            const data = await response.json();

            messageDiv.classList.remove('hidden');
            if (response.ok) {
                messageDiv.textContent = "🎉 Customer created successfully!";
                messageDiv.className = "text-green-600 font-semibold mt-8 animate__animated animate__fadeIn";
                document.getElementById('customerForm').reset();
            } else {
                throw new Error(data.error || "An error occurred");
            }
        } catch (error) {
            messageDiv.textContent = `⚠️ ${error.message}`;
            messageDiv.className = "text-red-600 font-semibold mt-8 animate__animated animate__fadeIn";
            messageDiv.classList.remove('hidden');
        }
    }
</script>
@endsection
