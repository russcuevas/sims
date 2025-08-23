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

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->


    <!--**********************************
            Main wrapper start
        ***********************************-->
    <div id="main-wrapper">
        {{-- right sidebar --}}
        @include('manager.right_sidebar')
        {{-- end right sidebar --}}

        {{-- left sidebar --}}
        @include('manager.left_sidebar')
        {{-- left sidebar end --}}

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">

                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a style="color: #A16D28;"
                                    href="{{ route('manager.dashboard.page')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Delivery Management</li>
                        </ol>
                    </div>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" style="font-size: 20px; color: #A16D28;">
                                    Delivery Management
                                </h4>
                            </div>

                            <div class="container my-4">
                                <!-- Status Buttons aligned to the right -->
                                <div class="row mb-3 justify-content-center">
                                    <div class="col-auto px-1">
                                        <a href="{{ route('manager.delivery.management.page') }}"
                                            class="btn btn-outline-primary" id="status_preparing">Preparing</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="{{ route('manager.payment.item.page') }}" class="btn btn-primary" id="status_payment">Payment</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="{{ route('manager.return.item.page') }}" class="btn btn-outline-primary"
                                            id="status_to_ship">Return item</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="{{ route('manager.pending.management.page') }}"
                                            class="btn btn-outline-primary" id="status_delivered">Pending delivery</a>
                                    </div>
                                    <div class="col-auto px-1">
                                        <a href="{{ route('manager.delivery.status.page') }}"
                                            class="btn btn-outline-primary" id="status_return">Delivery Status</a>
                                    </div>
                                </div>
                            </div>


                            <div class="card-body">
                                <div class="container my-4">
                                    <form action="{{ route('manager.payment.fetch') }}" method="GET">
                                        <div class="d-flex gap-2" style="width: 100%;">
                                            <select id="returnCompleted" name="transact_id" class="form-control">
                                                <option value="">-- Select Transaction --</option>
                                                @foreach ($returnedDeliveryOrders as $transact_id => $items)
                                                    <option value="{{ $transact_id }}" {{ request('transact_id') == $transact_id ? 'selected' : '' }}>
                                                        {{ $transact_id }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>

                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center align-middle" style="font-size: 14px;">
                                        <thead>
                                            <tr style="color: #A16D28">
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
                                            @php
                                                $totalOrdered = 0;
                                                $totalReturnedPrice = 0;
                                                $totalAmount = 0;
                                                $transactId = $deliveryOrders->first()->transact_id ?? null;
                                            @endphp

                                            @isset($deliveryOrders)
                                                @forelse ($deliveryOrders as $order)
                                                    @php
                                                        $qtyOrdered = $order->quantity_ordered ?? 0;
                                                        $qtyReturned = $order->quantity_returned ?? 0;
                                                        $lineAmount = $qtyOrdered * $order->price;

                                                        $totalOrdered += $qtyOrdered;
                                                        $totalReturnedPrice += $qtyReturned * $order->price;
                                                        $totalAmount += $lineAmount;
                                                    @endphp
                                                    <tr style="color: black">
                                                        <td>{{ $order->product_name }}</td>
                                                        <td>{{ $order->pack }}</td>
                                                        <td>{{ $order->unit }}</td>
                                                        <td>{{ $qtyOrdered }}</td>
                                                        <td>{{ $qtyReturned }}</td>
                                                        <td>₱{{ number_format($order->price, 2) }}</td>
                                                        <td>₱{{ number_format($lineAmount, 2) }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center text-muted">No delivery orders available.</td>
                                                    </tr>
                                                @endforelse
                                            @endisset
                                        </tbody>

                                        <tfoot>
                                            <tr style="color: black">
                                                <td colspan="3"></td>
                                                <td><strong>Total Ordered: {{ $totalOrdered }}</strong></td>
                                                <td><strong>Price Returned: ₱{{ number_format($totalReturnedPrice, 2) }}</strong></td>
                                                <td></td>
                                                <td><strong>Total Amount: ₱{{ number_format($totalAmount, 2) }}</strong></td>
                                            </tr>

                                            @if ($transactId)
                                                <tr>
                                                    <td colspan="7" class="text-center">
                                                        <form method="POST" action="{{ route('manager.payment.updateTransact') }}" class="d-flex justify-content-center gap-2">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="transact_id" value="{{ $transactId }}">
                                                            <input type="number" step="0.01" name="payment_amount" class="form-control w-25"
                                                                placeholder="Enter payment" required>
                                                            <button type="submit" class="btn btn-success">Save Amount</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tfoot>
                                    </table>
                                </div>

                            <hr class="my-4">

                            <h5 class="text-center text-primary">History</h5>

                            <!-- Search bar and actions -->
                            <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
                                <form method="GET" action="" class="d-flex flex-wrap justify-content-center gap-2 mb-3"
                                    id="filterSortForm">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="form-control w-auto" placeholder="Search product here">
                                    <button type="submit" class="btn btn-primary mr-2">Search</button>

                                    <select name="process_by" class="btn btn-outline-primary dropdown-toggle mr-2"
                                        onchange="document.getElementById('filterSortForm').submit()">
                                        <option value="">Filter by Processor</option>
                                    </select>

                                    <select name="sort" class="btn btn-outline-primary dropdown-toggle"
                                        onchange="document.getElementById('filterSortForm').submit()">
                                        <option value="">Sort by Date</option>
                                        <option value="newest" {{ request('sort')=='newest' ? 'selected' : '' }}>Newest
                                            First</option>
                                        <option value="oldest" {{ request('sort')=='oldest' ? 'selected' : '' }}>Oldest
                                            First</option>
                                    </select>
                                </form>

                            </div>

                            <div class="table-responsive">
                            <table id="delivery-table" class="table table-bordered text-center align-middle">
                                <thead class="table-light fw-bold">
                                    <tr>
                                        <th style="width: 10%; color: #A16D28;">Details</th>
                                        <th style="width: 15%; color: #A16D28;">Transaction Date</th>
                                        <th style="width: 20%; color: #A16D28;">Processed By</th>
                                        <th style="width: 20%; color: #A16D28;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Paid Orders --}}
                                    @foreach ($paidDeliveryOrders as $transactId => $orders)
                                        @php
                                            $firstOrder = $orders->first();
                                        @endphp
                                        <tr style="color: black">
                                            <td>
                                            <a href="{{ route('manager.delivery.payment.print', ['transact_id' => $firstOrder->transact_id]) }}" class="btn btn-outline-primary btn-sm">
                                                View
                                            </a>
                                            </td>
                                            <td>{{ $firstOrder->transaction_date}}</td>
                                            <td>{{ $firstOrder->process_by ?? 'N/A' }}</td>
                                            <td>
                                                <span>Paid</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>




                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <!--**********************************
            Content body end
        ***********************************-->

    <!--**********************************
           Support ticket button start
        ***********************************-->

    <!--**********************************
           Support ticket button end
        ***********************************-->


    </div>

    <!-- SCRIPT -->
    <!-- REQUIRED VENDORS -->
    <script src="{{ asset('partials/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('partials/js/quixnav-init.js') }}"></script>
    <script src="{{ asset('partials/js/custom.min.js') }}"></script>
    <!-- JQUERY VALIDATION -->
    <script src="{{ asset('partials/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('partials/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
                <script>
        $(document).ready(function () {
            $('#delivery-table').DataTable({
                pageLength: 10,
                searching: false, // remove search box
                order: [], // disable initial ordering
            });
        });
    </script>
    <script>
        function calculateTotals() {
            let totalAmount = 0;

            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const quantityInput = row.querySelector('input[name="quantity[]"]');
                const priceCell = row.querySelector('td:nth-child(4)');
                const amountDisplay = row.querySelector('.amount-display');
                const amountInput = row.querySelector('input[name="amount[]"]');

                if (!quantityInput || !priceCell || !amountDisplay) return; // skip row if missing

                const priceText = priceCell.innerText.replace(/[^\d.]/g, '');
                const quantity = parseInt(quantityInput.value) || 0;
                const price = parseFloat(priceText) || 0;

                const amount = quantity * price;

                amountDisplay.textContent = '₱ ' + amount.toFixed(2);

                if (amountInput) {
                    amountInput.value = amount.toFixed(2);
                }

                totalAmount += amount;
            });

            // Update total display
            const totalDisplay = document.getElementById('total_amount_display');
            if (totalDisplay) {
                totalDisplay.textContent = '₱ ' + totalAmount.toFixed(2);
            }

            // Update hidden input (if any)
            const totalInput = document.getElementById('total_amount_input');
            if (totalInput) {
                totalInput.value = totalAmount.toFixed(2);
            }
        }

        // Recalculate totals whenever quantity changes
        document.addEventListener('input', function (event) {
            if (event.target.matches('input[name="quantity[]"]')) {
                calculateTotals();
            }
        });

        // Run once on page load
        window.addEventListener('load', calculateTotals);

    </script>




    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if ($errors -> any())
            @foreach($errors -> all() as $error)
        toastr.error("{{ $error }}");
        @endforeach
        @endif
    </script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function () {
            $('#returnCompleted').select2({
                placeholder: "Search and select products",
                allowClear: true,
                width: 'resolve'
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

</html>