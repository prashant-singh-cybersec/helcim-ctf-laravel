<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@fortawesome/fontawesome-free@6.4.0/js/all.js" crossorigin="anonymous"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        fadeIn: "fadeIn 0.5s ease-out forwards",
                        slideDown: "slideDown 0.4s ease-in-out",
                    },
                    keyframes: {
                        fadeIn: {
                            from: { opacity: 0 },
                            to: { opacity: 1 },
                        },
                        slideDown: {
                            from: { opacity: 0, transform: "translateY(-10px)" },
                            to: { opacity: 1, transform: "translateY(0)" },
                        },
                    },
                },
            }
        };
    </script>
</head>

<body class="bg-gradient-to-br from-yellow-200 via-orange-100 to-pink-100 min-h-screen text-gray-800 font-sans">

    <!-- Navbar -->
    <nav class="bg-white shadow-lg py-4 px-8">
        <div class="flex justify-between items-center max-w-7xl mx-auto">
            <h1 class="text-2xl font-extrabold text-indigo-700 tracking-wide">🚀 Admin Panel</h1>
            <div class="flex items-center space-x-4">
                <span class="text-sm">Welcome, <span class="font-semibold">Admin</span></span>
                <span class="bg-indigo-100 text-indigo-600 px-3 py-1 text-xs rounded-full font-semibold">SuperAdmin</span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-8 px-6 animate-fadeIn">
        <h2 class="text-3xl font-bold text-center mb-10">📊 Organization Overview</h2>

        <div class="grid gap-6">
            @forelse ($data as $org)
                <div class="bg-white rounded-xl shadow-lg p-6 transition duration-300 hover:shadow-2xl">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-indigo-700">{{ $org->org_name }}</h3>
                        <button onclick="toggleSection('org-{{ $org->id }}')"
                            class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded transition duration-200">
                            View Details
                        </button>
                    </div>

                    <div id="org-{{ $org->id }}" class="hidden mt-4 animate-slideDown">
                        <p class="text-sm text-gray-600 mb-2">Organization ID: <strong>{{ $org->id }}</strong></p>

                        <h4 class="text-lg font-semibold mt-4 mb-2 text-gray-700">👥 Customers</h4>
                        @forelse ($org->customers as $customer)
                            <div class="bg-gray-100 p-4 rounded-lg mb-3 shadow">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p><strong>Name:</strong> {!! $customer->cust_name !!}</p>
                                        <p><strong>Email:</strong> {{ $customer->email }}</p>
                                    </div>
                                    <button onclick="toggleSection('customer-{{ $customer->id }}')"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded mt-2">
                                        View Invoices
                                    </button>
                                </div>

                                <div id="customer-{{ $customer->id }}" class="hidden mt-3">
                                    <h5 class="text-sm font-bold mb-2">💸 Invoices</h5>
                                    @forelse ($customer->invoices as $invoice)
                                        <div class="bg-white p-3 rounded-lg shadow-inner mb-2 border border-gray-200">
                                            <p><strong>ID:</strong> {{ $invoice->invoice_id }}</p>
                                            <p><strong>Status:</strong>
                                                <span class="text-sm px-2 py-1 rounded-full font-semibold
                                                    @if($invoice->status == 'PAID') bg-green-200 text-green-800
                                                    @elseif($invoice->status == 'DUE') bg-yellow-200 text-yellow-800
                                                    @elseif($invoice->status == 'CANCELLED') bg-red-200 text-red-700
                                                    @else bg-blue-200 text-blue-800
                                                    @endif">
                                                    {{ $invoice->status }}
                                                </span>
                                            </p>
                                            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->date_issued)->format('M d, Y') }}</p>
                                            <p><strong>Amount:</strong> ${{ number_format($invoice->total_amount, 2) }}</p>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500">No invoices for this customer.</p>
                                    @endforelse
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No customers in this organization.</p>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-600 text-lg">No organizations found.</div>
            @endforelse
        </div>
    </div>

    <!-- Toggle Logic -->
    <script>
        function toggleSection(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');
        }
    </script>
</body>
</html>
