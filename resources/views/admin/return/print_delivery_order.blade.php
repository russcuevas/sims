<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    <div id="deliveryOrderContent"> 
    <div style="text-align: right; margin:20px;">
        <button id="backButton" onclick="window.location.href='/admin/return_item'" style="padding: 8px 20px; font-size: 14px;">Back</button>
        <button id="btn-validate-pin" style="padding: 8px 20px; font-size: 14px;">Print</button>
    </div>


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
            <th>Qty Returned</th>
            <th>Price</th>
            <th>Amount</th>
        </tr>
    </thead>
<tbody>
    @foreach ($delivery as $item)
    <tr data-price="{{ $item->price }}" data-qty="{{ $item->quantity_returned ?? 0 }}">
        <td>{{ $item->product_name }}</td>
        <td>{{ $item->pack }}</td>
        <td>{{ $item->unit }}</td>
        <td>{{ $item->quantity_ordered }}</td>
        <td>{{ $item->quantity_returned ?? '' }}</td>
        <td>₱{{ number_format($item->price, 2) }}</td>
        <td class="row-amount">₱{{ number_format($item->amount, 2) }}</td>
    </tr>
    @endforeach
</tbody>

<tfoot>
    <tr>
        <td colspan="3" style="border: none;"></td>
        <td style="border:none; font-weight: bold;">Total Ordered: {{ $delivery->sum('quantity_ordered') }}</td>
        <td id="priceReturned" style="border:none; font-weight: bold;">Price Returned: ₱0.00</td>
        <td style="border: none;"></td>
        <td style="border:none; font-weight: bold;">Total Amount: ₱{{ number_format($delivery->sum('amount'), 2) }}</td>
    </tr>
</tfoot>

</table>


    <div class="signatures-section">
        <div style="display: flex; justify-content: space-between; font-weight: bold; margin-bottom: 10px;">
            <div>Delivered by: {{ $first->delivered_by_name ?? '(blank)' }}</div>
            <div>Prepared by: {{ $first->process_by }}</div>
            <div>Approved by: {{ $first->approved_by_name  ?? '(blank)' }}
            @if ($first->approved_by_assigned === 3)
                [SIGNED]
            @elseif($first->approved_by_assigned === 2)
                
            @else

            @endif</div>
            <div>Received by: {!! $first->received_by ?? "<span style='margin-left: 100px'></span>" !!}</div>
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
    </div>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- html2pdf.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    function calculatePriceReturned() {
        let totalReturned = 0;

        document.querySelectorAll('tbody tr').forEach(row => {
            const qty = parseFloat(row.dataset.qty) || 0;
            const price = parseFloat(row.dataset.price) || 0;
            totalReturned += qty * price;
        });

        document.getElementById('priceReturned').textContent = `Price Returned: ₱${totalReturned.toFixed(2)}`;
    }

    // Run this after the page loads
    window.addEventListener('DOMContentLoaded', calculatePriceReturned);
</script>


<script>
    document.getElementById('btn-validate-pin').addEventListener('click', async function () {
        const doNumber = `{{ $first->transact_id ?? 'SAVMGMALL-yymmdd0001' }}`;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        Swal.fire({
            title: 'Enter Your PIN',
            input: 'password',
            inputAttributes: {
                maxlength: 6,
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Verify',
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            preConfirm: (pin) => {
                return fetch('/admin/validate-pin-delivery', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ 
                        pin: pin,
                        do_number: doNumber
                    })
                })
                .then(async (response) => {
                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Invalid PIN');
                    }
                    return response.json();
                })
                .catch((error) => {
                    Swal.showValidationMessage(`PIN validation failed: ${error.message}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value?.message === 'PIN verified') {
                const printButton = document.getElementById('btn-validate-pin');
                const backButton = document.getElementById('backButton');
                const element = document.getElementById('deliveryOrderContent') || document.body;

                printButton.style.display = 'none';
                backButton.style.display = 'none';

                const opt = {
                    margin: 0.5,
                    filename: `${doNumber}.pdf`,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                };

                html2pdf().from(element).set(opt).save().then(() => {
                    printButton.style.display = 'inline-block';
                    backButton.style.display = 'inline-block';
                });
            }
        });
    });
</script>


</body>
</html>
