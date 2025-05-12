@extends('base')

@section('body')
<div class="min-h-screen bg-gradient-to-r from-yellow-100 via-pink-100 to-purple-100 flex flex-col justify-center items-center px-4 -mt-10">
    <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-md w-full animate__animated animate__fadeInDown">
        <h1 class="text-3xl font-extrabold text-indigo-700 text-center mb-6">🚀 Upgrade to Premium</h1>
        <p class="text-lg text-gray-700 mb-6 text-center">
            Get full access to advanced analytics for just <strong>100 CAD</strong>! Apply discounts and race for free access! 🏁
        </p>

        <!-- Payment Form -->
        <form id="paymentForm" class="space-y-6">
            <div>
                <label for="discountCode" class="block font-semibold text-gray-600 mb-2">Discount Code (Optional)</label>
                <div class="flex space-x-2">
                    <input type="text" id="discountCode" name="discountCode" placeholder="Enter discount code"
                        class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                    <button type="button" onclick="applyDiscount()"
                        class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition">
                        Apply
                    </button>
                </div>
                <p id="discountMessage" class="mt-2 text-sm hidden"></p>
            </div>

            <div class="text-center mt-4">
                <p class="text-lg font-bold text-indigo-700">Amount to Pay: <span id="finalAmount">100.00 CAD</span></p>
            </div>

            <div class="flex justify-center">
                <button type="button" onclick="showPaymentPopup()"
                    class="w-full px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition">
                    Purchase Premium
                </button>
            </div>
        </form>
    </div>

    <!-- Payment Confirmation Popup -->
    <div id="paymentPopup" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-sm w-full text-center animate__animated animate__fadeInUp">
            <h2 class="text-2xl font-bold mb-4 text-indigo-700">Confirm Payment</h2>
            <p class="mb-6 text-gray-700">You are about to pay <span id="popupAmount" class="font-bold">100.00 CAD</span>.</p>
            <div class="flex space-x-4 justify-center">
                <button onclick="closePaymentPopup()"
                    class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition">
                    Cancel
                </button>
                <button onclick="handlePayment()"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Confirm
                </button>
            </div>
        </div>
    </div>

    <!-- Success Popup -->
    <div id="successPopup" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-sm w-full text-center animate__animated animate__zoomIn">
            <h2 class="text-2xl font-bold mb-4 text-green-600">🎉 Success!</h2>
            <p class="text-gray-700 mb-6">You're now a premium user!</p>
            <button onclick="redirectToAnalytics()"
                class="px-6 py-3 bg-green-500 text-white font-bold rounded-lg hover:bg-green-600 transition">
                Go to Analytics
            </button>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
let finalAmount = 100.00;

function applyDiscount() {
    const discountCode = document.getElementById('discountCode').value;
    fetch('{{ route('apply_discount') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ discountCode: discountCode })
    })
    .then(response => response.json())
    .then(data => {
        const discountMessage = document.getElementById('discountMessage');
        discountMessage.classList.remove('hidden');
        if (data.success) {
            finalAmount = data.finalAmount;
            document.getElementById('finalAmount').textContent = `${finalAmount.toFixed(2)} CAD`;
            discountMessage.textContent = data.message;
            discountMessage.className = 'text-green-500 text-sm mt-2';
        } else {
            discountMessage.textContent = data.error;
            discountMessage.className = 'text-red-500 text-sm mt-2';
        }
    })
    .catch(console.error);
}

function showPaymentPopup() {
    document.getElementById('popupAmount').textContent = `${finalAmount.toFixed(2)} CAD`;
    document.getElementById('paymentPopup').classList.remove('hidden');
}

function closePaymentPopup() {
    document.getElementById('paymentPopup').classList.add('hidden');
}

async function handlePayment() {
    const response = await fetch('{{ route('purchase_finalize') }}', { method: 'POST' });
    const data = await response.json();

    if (data.success) {
        document.getElementById('paymentPopup').classList.add('hidden');
        document.getElementById('successPopup').classList.remove('hidden');
    } else {
        alert(data.error || 'Payment failed.');
    }
}

function redirectToAnalytics() {
    window.location.href = '/advanced_analytics';
}
</script>
@endsection
