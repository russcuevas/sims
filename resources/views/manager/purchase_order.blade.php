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
    <div id="main-wrapper">

<style>
    .header-notification .mdi-bell-outline {
        font-size: 1.5rem;
        position: relative;
    }

    .header-notification .badge {
        position: absolute;
        top: 10px;
        right: -5px;
        font-size: 0.8rem;
    }

    .dropdown-menu-right {
        width: 300px;
    }

    #notifications-list {
        max-height: 200px;
        overflow-y: auto;
        padding: 10px;
    }

    .dropdown-item {
        font-size: 0.9rem;
    }

    .notification-item {
        padding: 8px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
    }

    .notification-item span {
        font-size: 0.85rem;
        color: #555;
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


<script>
    const logoutUser = (isInactivity = false) => {
        if (isInactivity) {
            sessionStorage.setItem('logout_message', 'You have been automatically logged out due to inactivity.');
            sessionStorage.setItem('logout_type', 'warning');
        } else {
            sessionStorage.setItem('logout_message', 'You have been logged out.');
            sessionStorage.setItem('logout_type', 'success');
        }

        fetch("{{ route('logout.request') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            credentials: "same-origin"
        }).then(() => {
            window.location.href = "{{ route('login.page') }}";
        });
    };

    let inactivityTime = function () {
        let time;
        const logoutAfter = 5 * 60 * 1000; 

        function resetTimer() {
            clearTimeout(time);
            time = setTimeout(() => logoutUser(true), logoutAfter);
        }

        window.onload = resetTimer;
        document.onmousemove = resetTimer;
        document.onkeydown = resetTimer;
        document.onclick = resetTimer;
        document.onscroll = resetTimer;
    };

    inactivityTime();

    document.getElementById('manual-logout-btn').addEventListener('click', function () {
        logoutUser();
    });
</script>




        
        {{-- left sidebar --}}
        @include('manager.left_sidebar')
    <form style="margin-top: 100px" method="POST" action="{{ route('manager.stock.submit.po') }}" onsubmit="calculateAmounts();">
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
                        {{-- <th>Product ID</th> --}}
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
                            {{-- CHANGES REVISION --}}
                            <input type="hidden" name="products[{{ $index }}][product_id]" value="{{ $product->product_id }}">
                            {{-- CHANGES REVISION --}}
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
                    <tr>
                        <td colspan="1" class="text-right">
                            <button type="button" class="btn btn-success" onclick="addProductRow()">+ Add Product</button>
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

        {{-- CHANGES REVISION --}}
        @php
            $lowStockIds = $lowStockProducts->pluck('id')->toArray();
        @endphp

        <select id="product_template" class="d-none">
            @foreach($allRawMaterials as $product)
                @if (!in_array($product->id, $lowStockIds))
                    <option value="{{ $product->product_id }}"
                        data-name="{{ $product->product_name }}"
                        data-unit="{{ $product->stock_unit_id }}"
                        data-price="{{ $product->price }}">
                        {{ $product->product_name }}
                    </option>
                @endif
            @endforeach
        </select>
        {{-- CHANGES REVISION --}}


    </form>
    </div>
        <!-- REQUIRED VENDORS -->
    <script src="{{ asset('partials/vendor/global/global.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('partials/js/quixnav-init.js') }}"></script>
    <script src="{{ asset('partials/js/custom.min.js') }}"></script>
    <!-- JQUERY VALIDATION -->
    <script src="{{ asset('partials/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    <!-- Bootstrap 5 JS + Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <!-- JQUERY VALIDATION -->
    <script src="{{ asset('partials/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('partials/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
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

    {{-- CHANGES REVISION --}}
    <script>
        let productIndex = {{ count($lowStockProducts) }};

        function getAlreadySelectedProductIds() {
            const selectedIds = [];

            document.querySelectorAll('.product-select').forEach(select => {
                if (select.value) {
                    selectedIds.push(select.value);
                }
            });

            return selectedIds;
        }

        function addProductRow() {
            const selectedProductIds = getAlreadySelectedProductIds();

            // Clone the hidden select and filter out selected options
            const selectTemplate = document.getElementById('product_template');
            const selectClone = document.createElement('select');
            selectClone.classList.add('form-control', 'product-select');
            selectClone.name = `products[${productIndex}][product_id]`;
            selectClone.setAttribute('onchange', `fillProductDetails(this, ${productIndex})`);

            // Copy options except already selected
            const defaultOption = document.createElement('option');
            defaultOption.text = 'Please select product';
            defaultOption.value = '';
            selectClone.appendChild(defaultOption);

            // Copy options except already selected
            Array.from(selectTemplate.options).forEach(option => {
                if (!selectedProductIds.includes(option.value)) {
                    selectClone.appendChild(option.cloneNode(true));
                }
            });

            // If no available products left to select
            if (selectClone.options.length === 0) {
                alert('All raw materials are already selected.');
                return;
            }

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <div class="product-name" id="product_name_${productIndex}">--</div>
                    <input type="hidden" name="products[${productIndex}][product_name]" id="input_name_${productIndex}">
                </td>
                <td><input type="number" name="products[${productIndex}][quantity]" class="form-control quantity" min="1" value="1" required></td>
                <td>
                    <div id="unit_${productIndex}">--</div>
                    <input type="hidden" name="products[${productIndex}][unit]" id="input_unit_${productIndex}" required>
                </td>
                <td><input type="number" name="products[${productIndex}][price]" class="form-control price" step="0.01" min="0" value="0" required></td>
                <td><input type="text" class="form-control amount" name="products[${productIndex}][amount]" value="0.00" readonly></td>
            `;

            row.children[0].prepend(selectClone);

            document.querySelector('#products-table-body').insertBefore(
                row,
                document.querySelector('#products-table-body').lastElementChild.previousElementSibling
            );

            row.querySelectorAll('.quantity, .price').forEach(input => {
                input.addEventListener('input', calculateAmounts);
            });

            productIndex++;
        }

        function fillProductDetails(select, index) {
            const selected = select.options[select.selectedIndex];
            if (!selected.value) return;
            const name = selected.dataset.name;
            const unit = selected.dataset.unit;
            const price = selected.dataset.price;

            document.getElementById(`product_name_${index}`).innerText = name;
            document.getElementById(`input_name_${index}`).value = name;

            document.getElementById(`unit_${index}`).innerText = unit;
            document.getElementById(`input_unit_${index}`).value = unit;

            const priceInput = select.closest('tr').querySelector(`input[name="products[${index}][price]"]`);
            priceInput.value = price;

            calculateAmounts();
        }
        </script>

</body>
</html>
