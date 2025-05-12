@extends('base')

@section('body')
    <style>
        .invoice-card {
            background: linear-gradient(135deg, #FFD494, #815AF0);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .invoice-header {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .invoice-logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .invoice-logo {
            max-width: 90%;
            max-height: 90%;
        }

        .details-container {
            display: flex;
            gap: 1.5rem;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .details-card {
            flex: 1 1 30%;
            background: white;
            color: #333;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: left;
            min-width: 280px;
        }

        .items-section {
            background: white;
            color: #333;
            margin: 1rem 0;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .total-section {
            background: white;
            color: #333;
            margin: 1rem 0;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th,
        .items-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .items-table th {
            background: #f9f9f9;
        }

        .download-btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #FFD494, #FFD494);
            color: black;
            font-weight: bold;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .download-btn:hover {
            background: linear-gradient(135deg, #FFD494, #815AF0);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }
    </style>

    <div class="min-h-screen bg-gray-100 py-10 -mt-10">
        <div class="max-w-5xl mx-auto invoice-card">

            <!-- Header -->
            <div class="invoice-header">
                <div class="invoice-logo-container">
                    @if($invoice->logo)
                        <img src="{{ asset('uploads/invoice_logo/' . $invoice->logo) }}" alt="Logo" class="invoice-logo">
                    @endif
                </div>
            </div>

            <!-- Details Cards -->
            <div class="details-container">
                <div class="details-card">
                    <h2 class="text-lg font-semibold">Invoice Details</h2>
                    <p><strong>Invoice ID:</strong> {{ $invoice->invoice_id }}</p>
                    <p><strong>Status:</strong> {{ $invoice->status }}</p>
                    <p><strong>Date Issued:</strong> {{ \Carbon\Carbon::parse($invoice->date_issued)->format('Y-m-d') }}</p>
                </div>

                <div class="details-card">
                    <h2 class="text-lg font-semibold">Customer Details</h2>
                    <p><strong>Name:</strong> {!! $invoice->customer->cust_name !!}</p>
                    <p><strong>Email:</strong> {{ $invoice->customer->email }}</p>
                </div>

                <div class="details-card">
                    <h2 class="text-lg font-semibold">Organization Details</h2>
                    <p>{!! $invoice->organization_details !!}</p>
                </div>
            </div>

            <!-- Items Table -->
            <div class="items-section">
                <h2 class="text-lg font-semibold">Items</h2>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>{{ $item['price'] }}</td>
                                <td>{{ $item['quantity'] * $item['price'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Total -->
            <div class="total-section">
                <h2 class="text-lg font-semibold mb-2">Total Amount</h2>
                <p class="text-3xl font-bold">{{ $invoice->total_amount }}</p>
            </div>

            <!-- Download -->
            <div class="text-center mt-6">
                <a href="{{ url('/download_invoice?token=' . $invoice->token . '&download=1') }}" class="download-btn">
                    Download Invoice
                </a>
            </div>
        </div>
    </div>
@endsection