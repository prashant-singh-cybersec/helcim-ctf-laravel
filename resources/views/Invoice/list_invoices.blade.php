@extends('base')

@section('body')
<div class="min-h-screen flex flex-col bg-gray-100 relative">
    <!-- Navbar -->
    

    <div class="container mx-auto flex flex-1 mt-6">
        <!-- Sidebar -->
        <aside class="w-1/4 bg-white p-6 rounded-lg shadow-md mr-6">
            <ul class="space-y-4">
                <li><a href="/dashboard" class="block text-indigo-600 hover:underline">Dashboard</a></li>
                <li><a href="/issue_invoice" class="block text-indigo-600 hover:underline">Create Invoice</a></li>
                <li><a href="/update_invoice" class="block text-indigo-600 hover:underline">Update Invoice</a></li>
                <li><a href="/list_invoices" class="block text-indigo-600 font-bold hover:underline">List Issued Invoices</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold mb-4">Issued Invoices</h2>

            @if($invoices->count() > 0)
                <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-2 px-4 border-b text-left text-gray-600">Invoice ID</th>
                            <th class="py-2 px-4 border-b text-left text-gray-600">Date Issued</th>
                            <th class="py-2 px-4 border-b text-center text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr class="border-b">
                                <td class="py-2 px-4">{{ $invoice->invoice_id }}</td>
                                <td class="py-2 px-4">{{ \Carbon\Carbon::parse($invoice->dateIssued)->format('Y-m-d') }}</td>
                                <td class="py-2 px-4 text-center">
                                    <a href="/order?token={{ $invoice->token }}"
                                       class="px-4 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                        View Invoice
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No invoices issued yet.</p>
            @endif
        </main>
    </div>
</div>
@endsection
