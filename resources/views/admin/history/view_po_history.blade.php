<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Purchase Order Details</title>
    <link href="{{ asset('partials/css/style.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: white !important;
            color: black !important;
        }
        .supplier-info p {
            margin: 2px 0;
        }
        .table th, .table td {
            text-align: center;
        }
        @media print {
            button, .btn {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div id="purchaseOrderContent" class="container mt-4">

    <div class="supplier-info mb-4">
        <p style="font-size: 20px" class="text-center mb-1">{{ $supplier->supplier_name }}</p>
        <p style="font-size: 15px" class="text-center mb-1">{{ $supplier->supplier_address }}</p>
        <p class="text-center mb-1">Cel no.: {{ $supplier->supplier_contact_num }} <br>Email address: {{ $supplier->supplier_email_add }}</p>
    </div>

    <h3 class="text-center">Purchase Order</h3>
    <p class="text-center"><strong>{{ $po_number }}</strong></p>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th style="color: black">Product Name</th>
                <th style="color: black">Quantity</th>
                <th style="color: black">Unit</th>
                <th style="color: black">Price</th>
                <th style="color: black">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchaseOrderItems as $item)
                <tr>
                    <td style="color: black">{{ $item->product_name }}</td>
                    <td style="color: black">{{ $item->quantity }}</td>
                    <td style="color: black">{{ $item->unit }}</td>
                    <td style="color: black">₱{{ number_format($item->price, 2) }}</td>
                    <td style="color: black">₱{{ number_format($item->amount, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4"><strong></strong></td>
                <td style="color: black"><strong>Total Amount: ₱{{ number_format($purchaseOrderItems->first()->total_amount, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>
    <p><strong>Processed by:</strong> {{ $purchaseOrderItems->first()->process_by }}</p>

    <div class="float-right mt-4">
<button id="exportButton" onclick="confirmPinBeforePrint();" class="btn btn-primary">Print / Download</button>
    </div>

</div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
            function confirmPinBeforePrint() {
                Swal.fire({
                    title: 'Enter Your PIN',
                    input: 'password',
                    inputAttributes: {
                        autocapitalize: 'off',
                        maxlength: 6
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                    showLoaderOnConfirm: true,
                    preConfirm: (pin) => {
                        return fetch('/admin/validate-pin', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ 
                                pin: pin,
                                po_number: '{{ $po_number }}' 
                            })
                        })
                        .then(async response => {
                            if (!response.ok) {
                                const errorData = await response.json();
                                throw new Error(errorData.message || 'Invalid PIN');
                            }
                            return response.json();
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `PIN validation failed: ${error.message}`
                            );
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
            const button = document.getElementById('exportButton');
            const element = document.getElementById('purchaseOrderContent');

            // Hide the button
            button.style.display = 'none';

            const opt = {
                margin:       0.5,
                filename:     '{{ $po_number }}.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            html2pdf().from(element).set(opt).save().then(() => {
                button.style.display = 'inline-block';
            });
        }

        });
    }
</script>



</body>
</html>
