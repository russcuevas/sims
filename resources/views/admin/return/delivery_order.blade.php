<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Delivery Order</title>
    <link href="{{ asset('partials/css/style.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #fdfdfd;
        color: #333;
    }

    h2, h3 {
        color: #3d3d3d;
    }

    .header, .do-number {
        text-align: center;
    }

    .header h2 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .header p {
        font-size: 12px;
        color: #555;
        margin: 2px 0;
    }

    .do-number {
        font-size: 16px;
        color: #444;
        margin: 10px 0 20px;
        border-bottom: 1px solid #ccc;
        padding-bottom: 5px;
    }

    .info-section {
        margin: 30px 0;
        padding: 15px;
        background-color: #f8f8f8;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
    }

    .info-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .info-item {
        flex: 1;
        min-width: 200px;
        font-size: 13px;
        color: #333;
    }

    .product-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 13px;
        background-color: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .product-table th {
        background-color: #e0e0e0;
        color: #333;
        font-weight: bold;
        padding: 10px;
        border: 1px solid #ccc;
    }

    .product-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    .qty-return {
        width: 60px;
        padding: 4px;
        text-align: center;
    }

    .btn-success {
        background-color: #28a745;
        border: none;
        color: white;
        padding: 6px 12px;
        font-size: 14px;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    #backButton {
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        color: #333;
        padding: 6px 14px;
        border-radius: 4px;
        cursor: pointer;
    }

    #backButton:hover {
        background-color: #ddd;
    }

    .signatures-section {
        margin-top: 50px;
        padding-top: 15px;
        border-top: 1px solid #ccc;
        font-size: 13px;
    }

    .signatures-section div {
        margin-bottom: 10px;
    }

    .notes-section {
        font-size: 11px;
        margin-top: 40px;
        padding: 15px;
        background-color: #fffbe6;
        border-left: 4px solid #ffcc00;
    }

    .notes-section ul {
        margin: 10px 0;
        padding-left: 20px;
    }

    .notes-section li {
        margin-bottom: 5px;
    }

    .highlight {
        color: #d9534f;
        font-weight: bold;
    }

    @media print {
        #backButton, .btn-success {
            display: none;
        }
    }
</style>


        <div class="nav-header">
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>

        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">

                        </div>

                        <ul class="navbar-nav header-right">



                            


                            
                        </ul>
                    </div>
                </nav>
            </div>
        </div>


        {{-- left sidebar --}}
        @include('admin.left_sidebar')
        {{-- left sidebar end --}}
        <div id="deliveryOrderContent" style="
            padding: 30px 40px;
            max-width: 1000px;
            margin: 50px auto 100px auto;
        ">
        <div style="text-align: right; margin:20px;">
            <button id="backButton" onclick="window.location.href='/admin/return_item'" style="padding: 8px 20px; font-size: 14px;">Back</button>
        </div>


        <div style="text-align: center; margin-bottom: 30px;">
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
                <th>Qty Return</th>
                <th>Price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
<form method="POST" action="{{ route('admin.return.update', ['transact_id' => $first->transact_id]) }}">
            @csrf
                @foreach ($delivery as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->pack }}</td>
                <td>{{ $item->unit }}</td>
                <td>{{ $item->quantity_ordered }}</td>
                <td>
<input
    type="number"
    name="returns[{{ $item->id }}]"
    value="{{ $item->quantity_returned ?? 0 }}"
    min="0"
    class="qty-return"
    data-price="{{ $item->price }}"
>
                </td>
                <td>₱{{ number_format($item->price, 2) }}</td>
                <td>₱{{ number_format($item->amount, 2) }}</td>
            </tr>
        @endforeach

        <tfoot>
        <tr>
            <td colspan="3" style="border: none;"></td>
            <td style="border:none ; font-weight: bold;">Total Ordered: {{ $delivery->sum('quantity_ordered') }}</td>
            <td style="border:none ; font-weight: bold;">
                Price Returned: ₱<span id="price-returned-total">0.00</span>
                <button type="submit" class="btn btn-success">UPDATE</button>
            </td>
            <td style="border: none;"></td>
            <td style="border:none ; font-weight: bold;">Total Amount: ₱{{ number_format($delivery->sum('amount'), 2) }}</td>
        </tr>
        </tfoot>
    </form>
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
    </div>
        <script src="{{ asset('partials/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('partials/js/quixnav-init.js') }}"></script>
    <script src="{{ asset('partials/js/custom.min.js') }}"></script>
    <!-- JQUERY VALIDATION -->
    <script src="{{ asset('partials/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    <!-- Bootstrap 5 JS + Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- html2pdf.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    function calculateTotalReturned() {
        let total = 0;
        const qtyInputs = document.querySelectorAll('.qty-return');

        qtyInputs.forEach(input => {
            const quantity = parseFloat(input.value) || 0;
            const price = parseFloat(input.dataset.price) || 0;
            total += quantity * price;
        });

        document.getElementById('price-returned-total').textContent = total.toLocaleString('en-PH', {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    document.querySelectorAll('.qty-return').forEach(input => {
        input.addEventListener('input', calculateTotalReturned);
    });

    calculateTotalReturned();
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
