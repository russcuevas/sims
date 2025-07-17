<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sales & Inventory Management System </title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('partials/images/favicon.png') }}">
    <link href="{{ asset('partials/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('partials/css/style.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
    body {
        background-color: white !important;
        color: black !important;
    }
    .col-form-label {
        color: black;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
        cursor: default;
        padding-left: 2px;
        padding-right: 5px;
        color: black !important;
    }
    </style>
</head>
<body>
<form action="">

<div class="container mt-4">

    <p class="text-center mb-1">Company name</p>
    <p class="text-center mb-1">Address</p>
    <p class="text-center mb-1">Tel no.: Cell no.:<br>Email address:</p>

    <div class="text-center my-2">
            <h3 class="text-center">Purchase Order</h3>

        <strong>{{ $poNumber }}</strong>
    </div>

    <table class="table table-bordered text-center mt-3" style="color: black !important">
        <thead>
            <tr>
                <th>Product name</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($lowStockProducts as $product)
                @php
                    $amount = $product->quantity * $product->price;
                    $total += $amount;
                @endphp
                <tr>
                    <td>{{ $product->product_name }}</td>
                    <td contenteditable="true">{{ $product->quantity }}</td>
                    <td>{{ $product->stock_unit_id }}</td>
                    <td contenteditable="true">₱{{ number_format($product->price, 2) }}</td>
                    <td>₱{{ number_format($amount, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-right font-weight-bold">Total Amount:</td>
                <td><strong>₱{{ number_format($total, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="d-flex justify-content-between mt-4">
        <p>Process by: {{ $user->employee_firstname }} {{ $user->employee_lastname }}</p>
        <p>Approved by:
            <select>
                <option>Choose Admin</option>
                <option>Admin A</option>
                <option>Admin B</option>
            </select>
        </p>
    </div>

    <div class="text-right">
        <button class="btn btn-primary">Download PDF</button>
    </div>

</div>

</body>

</html>