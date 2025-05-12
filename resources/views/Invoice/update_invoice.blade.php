@extends('base')

@section('body')
    <div class="min-h-screen flex flex-col bg-gray-100 relative">
        <!-- Navbar 
            <nav class="p-4 shadow-md text-white bg-gradient-to-r from-[#815AF0] to-[#FFD494]">
                <div class="container mx-auto flex justify-between items-center">
                    <h1 class="text-xl font-bold text-gray-800">Update Invoice</h1>
                    <div class="flex items-center space-x-4 bg-white text-gray-800 px-4 py-2 rounded-lg shadow-md">
                        <div
                            class="rounded-full bg-gradient-to-r from-[#FFD494] to-[#815AF0] w-10 h-10 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(auth()->user()->email, 0, 1)) }}
                        </div>
                        <div class="flex flex-col text-center">
                            <span class="text-sm font-medium">Welcome,</span>
                            <span class="text-lg font-bold">{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                </div>
            </nav> -->

    

        <div class="container mx-auto flex flex-1 mt-6">
            <!-- Sidebar -->
            <aside class="w-1/4 bg-white p-6 rounded-lg shadow-md mr-6">
                <ul class="space-y-4">
                    <li><a href="/dashboard" class="block text-indigo-600 hover:underline">Dashboard</a></li>
                    <li><a href="/issue_invoice" class="block text-indigo-600 hover:underline">Create Invoice</a></li>
                    <li><a href="/update_invoice" class="block text-indigo-600 font-bold hover:underline">Update Invoice</a>
                    </li>
                    <li><a href="/list_invoices" class="block text-indigo-600 hover:underline">List Issued Invoices</a></li>
                </ul>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-semibold mb-4">Search and Update Invoice</h2>

                <!-- Search Invoice -->
                <div class="mb-6">
                    <label for="invoiceSearch" class="block text-gray-600 font-medium mb-2">Search Invoice</label>
                    <input type="text" id="invoiceSearch" placeholder="Search by Invoice ID..."
                        class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        oninput="filterInvoices()" />
                    <ul id="invoiceDropdown"
                        class="bg-white mt-2 border rounded-lg shadow-md max-h-40 overflow-auto hidden">
                        @foreach ($invoices as $invoice)
                            <li class="px-4 py-2 hover:bg-indigo-100 cursor-pointer"
                                onclick="selectInvoice('{{ $invoice->id }}', '{{ $invoice->invoice_id }}')">
                                {{ $invoice->invoice_id }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Update Form -->
                <div id="updateFormContainer" class="hidden">
                    <form id="updateInvoiceForm" method="POST" action="/api/v2/update_invoice" enctype="multipart/form-data"
                        class="bg-gradient-to-r from-[#FFD494] to-[#815AF0] p-8 rounded-lg shadow-lg">
                        @csrf
                        <h3 class="text-2xl font-bold mb-6 text-white text-center">Update Invoice</h3>

                        <!-- Logo Field -->
                        <div class="flex flex-col items-center mb-6">
                            <span id="logoLabel" class="text-sm text-gray-600 mb-2">Loading...</span>

                            <label for="logo" class="relative w-24 h-24 bg-white rounded-full shadow-md overflow-hidden">
                                <input type="file" id="logo" name="logo" class="absolute inset-0 opacity-0 cursor-pointer">
                                <div id="logoContainer"
                                    class="absolute inset-0 bg-gray-100 flex items-center justify-center">
                                    <span class="text-gray-500 text-sm">Upload</span>
                                </div>
                            </label>
                        </div>


                        <div class="grid grid-cols-2 gap-6">
                            <!-- Invoice ID -->
                            <div>
                                <label for="invoiceId" class="block text-white font-medium mb-2">Invoice ID</label>
                                <input type="text" id="invoiceId" name="invoiceId"
                                    class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    readonly>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-white font-medium mb-2">Invoice Status</label>
                                <select id="status" name="status"
                                    class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="DUE">DUE</option>
                                    <option value="PAID">PAID</option>
                                    <option value="COMPLETED">COMPLETED</option>
                                    <option value="CANCELLED">CANCELLED</option>
                                </select>
                            </div>
                        </div>

                        <!-- Date Issued -->
                        <div class="mb-4">
                            <label for="dateIssued" class="block text-white font-medium mb-2">Date Issued</label>
                            <input type="date" id="dateIssued" name="dateIssued"
                                class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <!-- Organization Details -->
                        <div class="mb-4">
                            <label for="organizationDetails" class="block text-white font-medium mb-2">Organization
                                Details</label>
                            <textarea id="organizationDetails" name="organizationDetails" rows="3"
                                class="w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>

                        <!-- Items Table -->
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-white mb-4">Items</h3>
                            <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="py-2 px-4 border-b text-left text-gray-600">Item</th>
                                        <th class="py-2 px-4 border-b text-left text-gray-600">Quantity</th>
                                        <th class="py-2 px-4 border-b text-left text-gray-600">Price</th>
                                        <th class="py-2 px-4 border-b text-left text-gray-600">Total</th>
                                        <th class="py-2 px-4 border-b text-center text-gray-600">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsTable"></tbody>
                            </table>
                            <button type="button" onclick="addNewItemRow()"
                                class="mt-4 px-6 py-2 bg-white text-gray-800 font-bold rounded-lg shadow-md hover:bg-gray-100 transition">
                                Add Item
                            </button>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-center">
                            <button type="submit"
                                class="px-6 py-3 bg-white text-gray-800 font-bold rounded-lg shadow-md hover:bg-gray-100 transition">
                                Update Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Initialize DOM elements
        const invoiceSearch = document.getElementById('invoiceSearch');
        const invoiceDropdown = document.getElementById('invoiceDropdown');
        const updateFormContainer = document.getElementById('updateFormContainer');
        const invoiceIdInput = document.getElementById('invoiceId');
        const itemsTable = document.getElementById('itemsTable');

        // Filter Invoices
        function filterInvoices() {
            const query = invoiceSearch.value.toLowerCase();
            invoiceDropdown.classList.toggle('hidden', !query);

            Array.from(invoiceDropdown.children).forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(query) ? 'block' : 'none';
            });
        }

        function selectInvoice(id, invoiceId) {
            invoiceSearch.value = invoiceId;
            invoiceIdInput.value = invoiceId;

            fetch(`/api/invoice/${id}`)
                .then(response => response.json())
                .then(data => {
                    // Populate form fields
                    document.getElementById('status').value = data.status;
                    document.getElementById('organizationDetails').value = data.organizationDetails;
                    document.getElementById('dateIssued').value = data.dateIssued;

                    // Populate invoice items
                    itemsTable.innerHTML = '';
                    data.items.forEach(item => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                                        <td class="py-2 px-4"><input type="text" name="itemName[]" value="${item.name}" class="w-full px-2 py-1 border rounded"></td>
                                        <td class="py-2 px-4"><input type="number" name="quantity[]" value="${item.quantity}" class="w-full px-2 py-1 border rounded" onchange="updateTotal(this)"></td>
                                        <td class="py-2 px-4"><input type="number" name="price[]" value="${item.price}" class="w-full px-2 py-1 border rounded" onchange="updateTotal(this)"></td>
                                        <td class="py-2 px-4"><input type="number" name="total[]" value="${item.total}" class="w-full px-2 py-1 border rounded" readonly></td>
                                        <td class="py-2 px-4 text-center"><button type="button" onclick="removeItemRow(this)" class="px-4 py-2 bg-red-500 text-white rounded">Remove</button></td>
                                    `;
                        itemsTable.appendChild(row);
                    });

                    // Populate logo
                    const logoLabel = document.getElementById('logoLabel');
                    const logoContainer = document.getElementById('logoContainer');

                    if (data.logo) {
                        const logoUrl = `/uploads/invoice_logo/${data.logo}`;
                        logoLabel.textContent = `Click on the logo to select a new image.`;
                        logoContainer.innerHTML = `<img src="${logoUrl}" class="w-full h-full object-cover" />`;
                    } else {
                        logoLabel.textContent = 'No logo uploaded';
                        logoContainer.innerHTML = `<span class="text-gray-500 text-sm">Upload</span>`;
                    }
                });

            invoiceDropdown.classList.add('hidden');
            updateFormContainer.classList.remove('hidden');

            // Logo input change: preview new image & delink previous
            document.getElementById('logo').addEventListener('change', function (e) {
                const [file] = e.target.files;
                if (!file) return;

                const reader = new FileReader();
                reader.onload = ev => {
                    const logoContainer = document.getElementById('logoContainer');
                    const logoLabel = document.getElementById('logoLabel');

                    // Clear old logo and show new preview
                    logoContainer.innerHTML = '';
                    const img = document.createElement('img');
                    img.src = ev.target.result;
                    img.className = 'w-full h-full object-cover';
                    logoContainer.appendChild(img);

                    logoLabel.textContent = `Selected: ${file.name}`;
                };
                reader.readAsDataURL(file);
            });
        }




        // Add New Item Row
        function addNewItemRow() {
            const row = document.createElement('tr');
            row.innerHTML = `
                                            <td class="py-2 px-4"><input type="text" name="itemName[]" class="w-full px-2 py-1 border rounded"></td>
                                            <td class="py-2 px-4"><input type="number" name="quantity[]" class="w-full px-2 py-1 border rounded" onchange="updateTotal(this)"></td>
                                            <td class="py-2 px-4"><input type="number" name="price[]" class="w-full px-2 py-1 border rounded" onchange="updateTotal(this)"></td>
                                            <td class="py-2 px-4"><input type="number" name="total[]" class="w-full px-2 py-1 border rounded" readonly></td>
                                            <td class="py-2 px-4 text-center"><button type="button" onclick="removeItemRow(this)" class="px-4 py-2 bg-red-500 text-white rounded">Remove</button></td>
                                        `;
            itemsTable.appendChild(row);
        }

        // Remove Item Row
        function removeItemRow(button) {
            button.closest('tr').remove();
        }

        // Update Total
        function updateTotal(input) {
            const row = input.closest('tr');
            const quantity = row.querySelector('input[name="quantity[]"]').value || 0;
            const price = row.querySelector('input[name="price[]"]').value || 0;
            row.querySelector('input[name="total[]"]').value = (quantity * price).toFixed(2);
        }

        // Form Submission Handler
        document.addEventListener('DOMContentLoaded', () => {
            const updateInvoiceForm = document.getElementById('updateInvoiceForm');

            if (updateInvoiceForm) {
                updateInvoiceForm.addEventListener('submit', async (event) => {
                    event.preventDefault(); // Prevent default form submission

                    const formData = new FormData(updateInvoiceForm);

                    try {
                        const response = await fetch('/api/v2/update_invoice', {
                            method: 'POST',
                            body: formData,
                        });

                        if (response.ok) {
                            showAlert('Success', 'Invoice successfully updated!', true);
                        } else {
                            const errorData = await response.json();
                            showAlert('Error', errorData.error || 'An error occurred while updating the invoice.', false);
                        }
                    } catch (error) {
                        showAlert('Error', 'Network error. Please try again.', false);
                    }
                });
            }
        });



        // Select Invoice
        /* function selectInvoice(id, invoiceId, customerId) {
                invoiceSearch.value = invoiceId;
                invoiceIdInput.value = invoiceId;

                fetch(`/api/customers?customerid=${customerId}`)
                    .then(response => response.json())
                    .then(customer => {
                        customerNameInput.value = customer.CustName;
                    });

                updateFormContainer.classList.remove('hidden');
            }

        */

        // Show Alert
        function showAlert(title, message, isSuccess) {
            const alertBox = document.createElement('div');
            alertBox.className = `fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50`;
            alertBox.innerHTML = ` <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md text-center">
                                    <h3 class="text-lg font-semibold mb-4 ${isSuccess ? 'text-[#815AF0]' : 'text-red-600'}">${title}</h3>
                                    <p class="text-gray-600 mb-4">${message}</p>
                                    <button onclick="closeAlert(this)" class="px-4 py-2 ${isSuccess ? 'bg-[#815AF0]' : 'bg-red-500'} text-white rounded hover:bg-opacity-80">
                                        OK
                                    </button>
                                </div>
                                        `;
            document.body.appendChild(alertBox);
        }

        // Close Alert
        function closeAlert(button) {
            button.closest('.fixed').remove();
        }


    </script>
@endsection