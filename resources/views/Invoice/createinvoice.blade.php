@extends('base')

@section('body')
<div class="min-h-screen bg-gradient-to-r from-yellow-100 via-pink-100 to-purple-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 -mt-10">

    <div class="bg-white shadow-2xl rounded-2xl p-10 w-full max-w-4xl">
        <h1 class="text-3xl font-extrabold text-gray-800 mb-8 text-center">🧾 Create & Issue Invoice</h1>

        <!-- Form -->
        <form id="invoiceForm" action="/api/create_invoice" method="POST" enctype="multipart/form-data" class="space-y-8">
            <!-- Logo Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Logo</label>
                <input type="file" id="logo" name="logo" accept=".jpg, .jpeg, .png"
                       class="block w-full file:px-4 file:py-2 file:bg-indigo-100 file:border-0 file:rounded-lg file:text-indigo-700 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <p id="fileError" class="text-red-600 text-sm mt-2 hidden">Invalid file type. Only JPG, JPEG, and PNG are allowed.</p>
            </div>

            <!-- Invoice Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Invoice ID</label>
                    <input type="text" name="invoiceId" value="INV001"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Status</label>
                    <select name="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option>DUE</option>
                        <option>PAID</option>
                        <option>COMPLETED</option>
                        <option>CANCELLED</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date Issued</label>
                <input type="date" name="dateIssued"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Organization Details</label>
                <textarea name="organizationDetails" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <!-- Link to Customer -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Link to Customer</label>
                <select name="customer"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Search or Select Customer</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->cust_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Items Section -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Items</label>
                <div id="itemsContainer" class="space-y-4">
                    <div class="grid grid-cols-3 gap-4 item-row">
                        <input type="text" name="items[0][name]" placeholder="Item"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <input type="number" name="items[0][quantity]" placeholder="Quantity"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <input type="number" name="items[0][price]" placeholder="Price"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <button type="button" id="addItem"
                        class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    ➕ Add Item
                </button>
            </div>

            <!-- Total Amount -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Total Amount</label>
                <input type="number" name="totalAmount" id="totalAmount" readonly
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="w-full py-3 bg-indigo-600 text-white font-semibold text-lg rounded-lg shadow hover:bg-indigo-700">
                🚀 Create Invoice
            </button>
        </form>
    </div>
</div>

<script>
    const logoInput = document.getElementById('logo');
    const fileError = document.getElementById('fileError');

    logoInput.addEventListener('change', () => {
        const file = logoInput.files[0];
        const allowed = ['jpg', 'jpeg', 'png'];
        const ext = file.name.split('.').pop().toLowerCase();

        if (!allowed.includes(ext)) {
            fileError.classList.remove('hidden');
            logoInput.value = '';
        } else {
            fileError.classList.add('hidden');
        }
    });

    const itemsContainer = document.getElementById('itemsContainer');
    const addItemButton = document.getElementById('addItem');
    const totalAmountInput = document.getElementById('totalAmount');

    addItemButton.addEventListener('click', () => {
        const itemCount = itemsContainer.children.length;
        const newItemRow = document.createElement('div');
        newItemRow.classList.add('grid', 'grid-cols-3', 'gap-4', 'item-row');
        newItemRow.innerHTML = `
            <input type="text" name="items[${itemCount}][name]" placeholder="Item" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <input type="number" name="items[${itemCount}][quantity]" placeholder="Quantity" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <input type="number" name="items[${itemCount}][price]" placeholder="Price" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        `;
        itemsContainer.appendChild(newItemRow);
    });

    itemsContainer.addEventListener('input', () => {
        const rows = document.querySelectorAll('.item-row');
        let total = 0;
        rows.forEach(row => {
            const qty = row.children[1].value;
            const price = row.children[2].value;
            if (qty && price) total += qty * price;
        });
        totalAmountInput.value = total.toFixed(2);
    });
</script>
@endsection
