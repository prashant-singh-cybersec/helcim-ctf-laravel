<!DOCTYPE html>
<html>

<head>
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
        }

        .details-card {
            flex: 1;
            background: white;
            color: #333;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: left;
        }

        .items-section,
        .total-section {
            background: white;
            color: #333;
            margin: 1rem 0;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
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

        .total-section p {
            font-size: 2rem;
            font-weight: bold;
            margin-top: 1rem;
        }

        .download-btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #815AF0, #FFD494);
            color: white;
            font-weight: bold;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .download-btn:hover {
            background: linear-gradient(135deg, #FFD494, #815AF0);
        }
    </style>
</head>

<body>
    <div class="invoice-card">
        <!-- Logo -->
        <div class="invoice-header">
            <div class="invoice-logo-container">
                @if($invoice->logo)
                <img src="file:///{{ public_path('uploads/invoice_logo/' . $invoice->logo) }}" alt="Logo" class="invoice-logo">


                @endif
            </div>
        </div>

        <!-- Details Section -->
        <div class="details-container">
            <!-- Invoice Details -->
            <div class="details-card">
                <h2>Invoice Details</h2>
                <p><strong>Invoice ID:</strong> {{ $invoice->invoice_id }}</p>
                <p><strong>Status:</strong> {{ $invoice->status }}</p>
                <p><strong>Date Issued:</strong> {{ \Carbon\Carbon::parse($invoice->date_issued)->format('Y-m-d') }}</p>
            </div>

            <!-- Customer Details -->
            <div class="details-card">
                <h2>Customer Details</h2>
                <p><strong>Name:</strong> {!! $invoice->customer->cust_name !!}</p>
                <p><strong>Email:</strong> {{ $invoice->customer->email }}</p>
            </div>

            <!-- Organization Details -->
            <div class="details-card">
                <h2>Organization Details</h2>
                <p>{!! $invoice->organization_details !!}</p>
            </div>
        </div>

        <!-- Items Section -->
        <div class="items-section">
            <h2>Items</h2>
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
                    @php
                        $items = (array) $invoice->items;
                    @endphp

                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item['name'] ?? '' }}</td>
                            <td>{{ $item['quantity'] ?? 0 }}</td>
                            <td>{{ $item['price'] ?? 0 }}</td>
                            <td>{{ ($item['quantity'] ?? 0) * ($item['price'] ?? 0) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total -->
        <div class="total-section">
            <h2>Total Amount</h2>
            <p>{{ $invoice->total_amount }}</p>
        </div>
    </div>
</body>

</html>