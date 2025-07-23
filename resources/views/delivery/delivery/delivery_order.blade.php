<!DOCTYPE html>
<html>
<head>
    <title>Delivery Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            line-height: 1.4;
        }
        .text-center {
            text-align: center;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            font-size: 11px;
        }
        .do-number {
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
        }
        .info-section {
            margin: 20px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .info-item {
            flex: 1;
        }
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 11px;
        }
        .product-table th {
            border: 1px solid #000;
            padding: 8px 4px;
            text-align: center;
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .product-table td {
            border: 1px solid #000;
            padding: 8px 4px;
            text-align: center;
        }
        .totals-section {
            margin: 20px 0;
            display: flex;
            justify-content: space-between;
        }
        .signatures-section {
            margin: 40px 0;
        }
        .signature-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .signature-item {
            flex: 1;
            font-weight: bold;
        }
        .car-details {
            margin: 15px 0;
            font-weight: bold;
        }
        .notes-section {
            margin-top: 40px;
            font-size: 10px;
        }
        .notes-section strong {
            display: block;
            margin-bottom: 10px;
            font-size: 11px;
        }
        .notes-section ul {
            margin: 0;
            padding-left: 20px;
        }
        .notes-section li {
            margin-bottom: 3px;
        }
        .highlight {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header" style="text-align: center; margin-bottom: 30px;">
        <h2>{{ $first->store_name ?? 'Store Name' }}</h2>
        <p>{{ $first->store_address ?? 'Store Address' }}</p>
        <p>Tel: {{ $first->store_tel_no ?? 'N/A' }} Cell: {{ $first->store_cp_number ?? 'N/A' }}</p>
        <p>Fax: {{ $first->store_fax ?? 'N/A' }} Tin: {{ $first->store_tin ?? 'N/A' }}</p>
    </div>


    <div class="text-center">
        <h3>Deliver Order</h3>
    </div>

    <div class="do-number">
        {{ $first->transact_id ?? 'SAVMGMALL-yymmdd0001' }}
    </div>

    <div class="info-section">
        <div class="info-row">
            <div class="info-item"><strong>Supplier Name:</strong> {{ $first->process_by ?? '(ADMIN)' }}</div>
            <div class="info-item"><strong>Transaction Date:</strong> {{ \Carbon\Carbon::parse($first->transaction_date)->format('Y-m-d') ?? '(date)' }}</div>
        </div>
        <div class="info-row">
            <div class="info-item"><strong>Branch:</strong> {{ $first->store_name }}</div>
            @php
                $expectedFrom = \Carbon\Carbon::parse($first->transaction_date)->addDays(6)->format('Y-m-d');
                $expectedTo = \Carbon\Carbon::parse($first->transaction_date)->addDays(7)->format('Y-m-d');
            @endphp

            <div class="info-item">
                <strong>Expected Delivery:</strong> {{ $expectedFrom }} to {{ $expectedTo }}
            </div>
        </div>
        <div class="info-row">
            <div class="info-item"><strong>Memo:</strong> {{ $first->memo ?? 'None' }}</div>
            <div class="info-item"><strong>Cancellation Date:</strong> {{ \Carbon\Carbon::parse($first->transaction_date)->addDays(8)->format('Y-m-d') }}</div>
        </div>
    </div>

    <table class="product-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Pack</th>
                <th>Unit</th>
                <th>Qty Ord</th>
                <th>Qty Rcvd</th>
                <th>Price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($delivery as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->pack }}</td>
                <td>{{ $item->unit }}</td>
                <td>{{ $item->quantity_ordered }}</td>
                <td>{{ $item->quantity_received ?? '' }}</td>
                <td>{{ number_format($item->price, 2) }}</td>
                <td>{{ number_format($item->amount, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8"><em>No delivery data available</em></td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="totals-section">
        <div><strong>Total order:</strong> {{ $delivery->sum('quantity_ordered') }}</div>
        <div><strong>Total amount:</strong> ₱{{ number_format($delivery->sum('amount'), 2) }}</div>
    </div>

    <div class="signatures-section">
        <div style="display: flex; justify-content: space-between; font-weight: bold; margin-bottom: 10px;">
            <div>Delivered by: {{ $first->delivered_by_name ?? '(blank)' }}</div>
            <div>Prepared by: {{ $first->process_by }}</div>
            <div>Approved by: {{ $first->approved_by_name  ?? '(blank)' }}</div>
            <div>Received by: {!! $first->received_by ?? "<span style='margin-left: 100px'></span>" !!}</div>
        </div>
        <div style="font-weight: bold;">
            Car details: 
            {{ $first->car_name ?? '(blank)' }} 
            @if($first->plate_number)
                ({{ $first->plate_number }})
            @endif
        </div>
    </div>

    <br>
    <br>
    <br>
    <br>
    <br>

    <div class="notes-section">
        <strong>NOTES:</strong>
        <ul>
            <li>Previous Undelivered PO's will be CANCELLED upon the release of a P.O.</li>
            <li>Unclaimed BO Returns will be subject to disposal after 15 days of account deduction.</li>
            <li>For cost discrepancies between P.O. and invoice, the lower price shall prevail.</li>
            <li>For over delivery, excess quantity will be forfeited in our favor.</li>
            <li>For under delivery, request for new Purchase Order. P.O. cannot be re-used.</li>
            <li>Discrepancies due to packing will subject to negotiation before payment is made.</li>
            <li class="highlight">WE WILL PRIORITIZE SEGREGATED INVOICES FOR DELIVERY.</li>
        </ul>
    </div>

</body>
</html>
