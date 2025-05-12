@extends('base')

@section('body')
<div class="min-h-screen flex flex-col bg-gradient-to-br from-yellow-50 to-purple-100 relative">
    <!-- Top Navbar -->
    <nav class="p-4 shadow-md bg-gradient-to-r from-yellow-300 to-purple-400 text-white -mt-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex-1 flex items-center justify-center">
                <div class="flex items-center space-x-4 bg-white text-gray-800 px-5 py-3 rounded-lg shadow-lg">
                    <div class="rounded-full bg-gradient-to-r from-yellow-300 to-purple-600 w-12 h-12 flex items-center justify-center text-white font-bold text-lg">
                        {{ strtoupper(substr(Auth::user()->email, 0, 1)) }}
                    </div>
                    <div class="flex flex-col text-center">
                        <span class="text-sm font-medium">Welcome,</span>
                        <span class="text-lg font-bold">{{ Auth::user()->email }}</span>
                        <span class="text-sm font-medium mt-1">
                            @if(Auth::user()->is_paid_user)
                                <span class="text-green-600 font-bold">Premium User</span>
                            @else
                                <span class="text-red-600 font-bold">Free Trial User</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Layout Content -->
    <div class="container mx-auto flex flex-1 mt-6">
        <!-- Sidebar -->
        <aside class="w-1/4 bg-white p-6 rounded-lg shadow-xl mr-6 sticky top-24 self-start">
            <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Navigation</h3>
            <ul class="space-y-4 text-indigo-600 font-medium">
                @if(in_array('ROLE_ADMIN', (array) Auth::user()->roles))
                    <li><a href="/customer_create" class="hover:underline">➕ Create Customer</a></li>
                    <li class="relative" x-data="{ expandInvoice: false }">
                        <div @mouseenter="expandInvoice = true" @mouseleave="expandInvoice = false" class="cursor-pointer">
                            <span class="hover:underline">📄 Invoice Page</span>
                            <ul x-show="expandInvoice" class="mt-2 ml-4 space-y-2 text-sm text-indigo-500">
                                <li><a href="/issue_invoice" class="hover:underline">Issue Invoice</a></li>
                                <li><a href="/update_invoice" class="hover:underline">Update Invoice</a></li>
                                <li><a href="/list_invoices" class="hover:underline">List Invoices</a></li>
                            </ul>
                        </div>
                    </li>
                @endif
                <li><a href="/user_role_settings" class="hover:underline">🔧 Manage Roles</a></li>
                <li><a href="/update_settings" class="hover:underline">🛠️ User Settings</a></li>
                <li><a href="/advanced_analytics" class="hover:underline">📊 Advanced Analytics</a></li>
                <li><a href="/search" class="hover:underline">🔍 Universal Search</a></li>
                <li><a href="/feedback" class="hover:underline">💬 Submit Feedback</a></li>
                <li><a href="/feature-request" class="hover:underline">🚀 Request A Feature</a></li>
                
            </ul>
        </aside>

        <!-- Main Panel -->
        <main class="flex-1 bg-white p-8 rounded-lg shadow-xl">
            <h2 class="text-3xl font-extrabold text-indigo-800 mb-6 text-center">📁 Organization & Customer Info</h2>

            <!-- Organization -->
            <div class="mb-10 bg-gradient-to-r from-yellow-200 to-purple-200 p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-bold mb-2 text-gray-800">🏢 Organization Details</h3>
                <p><strong>Name:</strong> {{ Auth::user()->organization->org_name }}</p>
                <p><strong>ID:</strong> {{ Auth::user()->organization->id }}</p>
            </div>

            <!-- Customer List -->
            <div>
                <h3 class="text-lg font-bold mb-4 text-gray-800">👥 Customer List</h3>
                @if(Auth::user()->organization->customers->count())
                    <table class="w-full bg-white border border-gray-200 rounded-lg shadow-md overflow-hidden">
                        <thead class="bg-indigo-100 text-gray-700">
                            <tr>
                                <th class="py-2 px-4 border-b text-left">Name</th>
                                <th class="py-2 px-4 border-b text-left">Email</th>
                                <th class="py-2 px-4 border-b text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(Auth::user()->organization->customers as $customer)
                                <tr id="customer-{{ $customer->id }}" class="hover:bg-indigo-50 border-b">
                                    <td class="py-2 px-4">{!! $customer->cust_name !!}</td>
                                    <td class="py-2 px-4">{{ $customer->email }}</td>
                                    <td class="py-2 px-4 text-center">
                                        <button class="px-4 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition"
                                            data-customer-id="{{ $customer->id }}"
                                            data-customer-name="{{ $customer->cust_name }}"
                                            onclick="openEditFormFromButton(this)">
                                            Edit
                                        </button>
                                    </td>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-500 italic">No customers available.</p>
                @endif
            </div>
        </main>
    </div>

    <!-- Edit Modal -->
    <div id="editFormContainer" class="hidden fixed inset-0 bg-gray-800 bg-opacity-60 flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md">
            <h3 class="text-2xl font-bold mb-6 text-indigo-600 text-center">✏️ Edit Customer</h3>
            <form id="editForm" onsubmit="submitEditForm(event)">
                <label for="editCustName" class="block text-gray-700 font-medium mb-2">Customer Name</label>
                <input type="text" id="editCustName" name="CustName" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="closeEditForm()"
                        class="px-5 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</button>
                    <button type="submit"
                        class="px-5 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alert Popup -->
    <div id="alertPopup" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-xl text-center shadow-xl border-t-4 border-purple-500">
            <h3 id="alertTitle" class="text-2xl font-semibold text-purple-600 mb-3">Success</h3>
            <p id="alertMessage" class="text-gray-700 text-lg mb-5">Customer updated successfully!</p>
            <button onclick="closeAlert()" class="px-6 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">OK</button>
        </div>
    </div>
</div>

<script>
    let editCustomerId = null;

    function openEditFormFromButton(button) {
        const customerId = button.getAttribute('data-customer-id');
        const customerName = button.getAttribute('data-customer-name');
        openEditForm(customerId, customerName);
    }

    function openEditForm(customerId, customerName) {
        editCustomerId = customerId;
        document.getElementById('editCustName').value = customerName;
        document.getElementById('editFormContainer').classList.remove('hidden');
    }

    function closeEditForm() {
        editCustomerId = null;
        document.getElementById('editFormContainer').classList.add('hidden');
    }

    function showAlert(title, message) {
        document.getElementById('alertTitle').textContent = title;
        document.getElementById('alertMessage').textContent = message;
        document.getElementById('alertPopup').classList.remove('hidden');
    }

    function closeAlert() {
        document.getElementById('alertPopup').classList.add('hidden');
    }

    async function submitEditForm(event) {
        event.preventDefault();
        const customerName = document.getElementById('editCustName').value;

        try {
            const response = await fetch(`/api/edit/customer/${editCustomerId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ CustName: customerName }),
            });

            if (!response.ok) throw new Error('Failed to update customer.');

            const data = await response.json();
            if (data.success) {
                document.querySelector(`#customer-${editCustomerId} td:first-child`).textContent = customerName;
                showAlert('Success', 'Customer updated successfully!');
            } else {
                throw new Error(data.error || 'Unknown error occurred.');
            }
        } catch (error) {
            showAlert('Error', error.message);
        } finally {
            closeEditForm();
        }
    }
</script>
@endsection
