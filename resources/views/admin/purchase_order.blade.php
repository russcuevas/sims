<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sales & Inventory Management System </title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="{{ asset('partials/css/style.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        body {
            background-color: white !important;
            color: black !important;
        }
        .col-form-label, .form-control {
            color: black;
        }
        table td input {
            width: 100%;
        }
    </style>
</head>
<body>
<form method="POST" action="{{ route('admin.stock.submit.po') }}" onsubmit="calculateAmounts();">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="container mt-4">
            <div class="text-center my-2">
                <div class="d-inline-block" style="min-width: 300px;">
            <select name="supplier_id" id="supplier_id" class="form-control select2" required>
                <option value="">-- Select Supplier --</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}"
                        data-name="{{ $supplier->supplier_name }}"
                        data-address="{{ $supplier->supplier_address }}"
                        data-contact="{{ $supplier->supplier_contact_num }}"
                        data-email="{{ $supplier->supplier_email_add }}">
                        {{ $supplier->supplier_name }}
                    </option>
                @endforeach
            </select>

                </div>

                <h3 class="mt-3">Purchase Order</h3>
                <strong>{{ $poNumber }}</strong>
            </div>

            <table class="table table-bordered mt-3 text-center" style="color: black !important;">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Price</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody id="products-table-body">
                    @foreach($lowStockProducts as $index => $product)
                        <tr>
                            <td>
                                {{ $product->product_name }}
                                <input type="hidden" name="products[{{ $index }}][product_name]" value="{{ $product->product_name }}">
                            </td>
                            <td>
                                <input type="number" class="form-control quantity" name="products[{{ $index }}][quantity]" value="{{ $product->quantity }}" min="0" required>
                            </td>
                            <td>
                                {{ $product->stock_unit_id }}
                                <input type="hidden" class="form-control" name="products[{{ $index }}][unit]" value="{{ $product->stock_unit_id }}" required>
                            </td>
                            <td>
                                <input type="number" class="form-control price" name="products[{{ $index }}][price]" value="{{ $product->price }}" step="0.01" min="0" required>
                            </td>
                            <td>
                                <input style="background-color:gray; color: white; cursor: none;" type="text" class="form-control amount" name="products[{{ $index }}][amount]" value="0.00" readonly>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-right font-weight-bold">Total Amount:</td>
                        <td>
                            <input style="background-color:gray; color: white; cursor: none;" type="text" id="display_total" class="form-control" readonly>
                            <input type="hidden" name="total_amount" id="total_amount" value="0">
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="d-flex justify-content-between mt-4">
                <p>Processed by: {{ $user->employee_firstname }} {{ $user->employee_lastname }}</p>
                <p>Approved by:
                    <select name="approved_by" id="approved_by" class="form-control" required>
                        <option value="">Choose Admin</option>
                        @foreach ($admins as $admin)
                            <option value="{{ $admin->id }}">{{ $admin->employee_firstname }} {{ $admin->employee_lastname }}</option>
                        @endforeach
                    </select>
                </p>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-primary">Download</button>
            </div>
        </div>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    $('#supplier_id').select2({
        templateResult: function (data) {
            if (!data.id) return data.text;

            var name = $(data.element).data('name');
            var address = $(data.element).data('address');
            var contact = $(data.element).data('contact');
            var email = $(data.element).data('email');

            var $result = $(`
                <div>
                    <strong>${name}</strong><br>
                    Address: ${address}<br>
                    Contact: ${contact}<br>
                    Email: ${email}
                </div>
            `);

            return $result;
        }
    });

    </script>

    <!-- JavaScript to calculate amounts -->
    <script>
        function calculateAmounts() {
            let total = 0;

            document.querySelectorAll('#products-table-body tr').forEach(function(row) {
                const quantityInput = row.querySelector('.quantity');
                const priceInput = row.querySelector('.price');
                const amountInput = row.querySelector('.amount');

                if (quantityInput && priceInput && amountInput) {
                    const quantity = parseFloat(quantityInput.value) || 0;
                    const price = parseFloat(priceInput.value) || 0;
                    const amount = quantity * price;
                    amountInput.value = amount.toFixed(2);
                    total += amount;
                }
            });

            document.getElementById('display_total').value = total.toFixed(2);
            document.getElementById('total_amount').value = total.toFixed(2);
        }

        document.addEventListener('DOMContentLoaded', function () {
            calculateAmounts(); // initial calculation on page load

            document.querySelectorAll('.quantity, .price').forEach(input => {
                input.addEventListener('input', calculateAmounts);
            });
        });
    </script>
</body>
</html>
