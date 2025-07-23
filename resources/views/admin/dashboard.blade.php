<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sales & Inventory Management System </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="">
    <link rel="stylesheet" href="{{ asset('partials/vendor/owl-carousel/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('partials/vendor/owl-carousel/css/owl.theme.default.min.css') }}">
    <link href="{{ asset('partials/vendor/jqvmap/css/jqvmap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('partials/css/style.css') }}" rel="stylesheet">
</head>

<body>

    {{-- loader start --}}
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    {{-- end loader --}}
    <div id="main-wrapper">
        {{-- right sidebar --}}
        @include('admin.right_sidebar')
        {{-- end right sidebar --}}

        {{-- left sidebar --}}
        @include('admin.left_sidebar')
        {{-- left sidebar end --}}

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
            <div class="container-fluid">
                <h1>Dashboard</h1>
                <div class="row">
                    <!-- Pending Orders -->
                    <div class="col-md-4">
                        <div class="card text-white bg-warning shadow">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-1 text-white">Pending Orders</h4>
                                    <h2 class="mb-0 text-white">12</h2>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-hourglass-half fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Completed Orders -->
                    <div class="col-md-4">
                        <div class="card text-white bg-success shadow">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-1 text-white">Completed Orders</h4>
                                    <h2 class="mb-0 text-white">12</h2>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Return Orders -->
                    <div class="col-md-4">
                        <div class="card text-white bg-danger shadow">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-1 text-white">Return Orders</h4>
                                    <h2 class="mb-0 text-white">12</h2>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-undo fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-3 text-white">Sales Graph</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 float-right">
                                <label for="salesYear" class="form-label" style="color: black">Select Year: </label>
                                    <select id="salesYear" class="form-select form-select-sm w-auto bg-white text-dark border-0 shadow-sm">
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                    <option value="2028">2028</option>
                                    <option value="2029">2029</option>
                                    <option value="2030">2030</option>
                                </select>
                            </div>
                            <canvas id="salesBarChart" height="200"></canvas>
                        </div>
                    </div>
                </div>


                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-3 text-white">Available Product</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="productPieChart" height="200"></canvas>
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
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{ asset('partials/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('partials/js/quixnav-init.js') }}"></script>
    <script src="{{ asset('partials/js/custom.min.js') }}"></script>


    <!-- Vectormap -->
    <script src="{{ asset('partials/vendor/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('partials/vendor/morris/morris.min.js') }}"></script>


    <script src="{{ asset('partials/vendor/circle-progress/circle-progress.min.js') }}"></script>
    <script src="{{ asset('partials/vendor/chart.js/Chart.bundle.min.js') }}"></script>

    <script src="{{ asset('partials/vendor/gaugeJS/dist/gauge.min.js') }}"></script>

    <!--  flot-chart js -->
    <script src="{{ asset('partials/vendor/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('partials/vendor/flot/jquery.flot.resize.js') }}"></script>

    <!-- Owl Carousel -->
    <script src="{{ asset('partials/vendor/owl-carousel/js/owl.carousel.min.js') }}"></script>

    <!-- Counter Up -->
    <script src="{{ asset('partials/vendor/jqvmap/js/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('partials/vendor/jqvmap/js/jquery.vmap.usa.js') }}"></script>
    <script src="{{ asset('partials/vendor/jquery.counterup/jquery.counterup.min.js') }}"></script>


    <script src="{{ asset('partials/js/dashboard/dashboard-1.js') }}"></script>
    <script>
        const ctx = document.getElementById('salesBarChart').getContext('2d');
        const salesBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Sales',
                    data: [5000, 7000, 3000, 8000, 6500, 9000],
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Monthly Sales Overview'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script>
    const productCtx = document.getElementById('productPieChart').getContext('2d');
    const productPieChart = new Chart(productCtx, {
        type: 'doughnut',
        data: {
            labels: ['Product A', 'Product B', 'Product C', 'Product D'],
            datasets: [{
                label: 'Product Availability',
                data: [30, 20, 25, 25], // replace with real data
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Product Availability'
                },
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>


</body>

</html>