@extends('layouts.app')
@section('css')
    <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="pagetitle">
        <div class="row">
            <div class="col-8">
                <h1>Orders</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">Orders</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <form class="" id="bulk-order-shipped" action="{{ route('bulk-order-review') }}" id="sort_orders" method="get">
                        
                        <div class="card-header row gutters-5">
                            <div class="row col-12">
                                <div class="col-sm-3">
                                    <h6 class="d-inline-block pt-10px">Assign To Shipping Company</h6>
                                </div>
                                <div class="col-sm-2">
                                    <span> Best Express </span>
                                    {{-- <select class="form-select aiz-selectpicker" name="shipping_company"
                                        id="shipping_company">
                                        <option value="0">Choose Shipping Company</option>
                                        <option value="1">Best Express</option>
                                        <option value="2">Sprint</option>
                                    </select> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card-header row gutters-5">
                            <div class="row col-12">
                                <div class="col-sm-3">
                                    <h6 class="d-inline-block pt-10px">Total Orders Ready To be Shipped</h6>
                                </div>
                                <div class="col-sm-4">
                                    <h6 class="d-inline-block pt-10px">{{ $orders_count }} Orders</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Reviewed Orders</h5>
                            <div class="container mt-5">
                                <h2>Scan Order / Enter Order Number</h2>
                                <div class="row">
                                    <div class="form-group col-11">
                                        <input type="text" id="order_number" class="form-control"
                                            placeholder="Enter Order Number">
                                    </div>
                                    <div class="col-1">
                                        <button class="btn btn-danger" id="submit_pickup_btn" onclick="submitPickup()">Submit</button>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <table class="table table-bordered" id="orders_table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="form-group">
                                                        <div class="aiz-checkbox-inline">
                                                            <label class="aiz-checkbox">
                                                                <input type="checkbox" class="check-all">
                                                                <span class="aiz-square-check"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </th>
                                                <th scope="col">Order No</th>
                                                <th scope="col">Customer Name</th>
                                                <th scope="col" class="text-center">Payment Status</th>
                                                <th scope="col" class="text-center">Subtotal</th>
                                                <th scope="col" class="text-center">Shipping</th>
                                                <th scope="col" class="text-center">Total</th>
                                                <th scope="col">Customer Phone</th>
                                                <th scope="col" class="text-center">Delivery Status</th>

                                                <th scope="col">Created Date</th>
                                                <th scope="col">Options</th>
                                            </tr>
                                        </thead>
                                        <tbody id="orders_tbody">
                                            <!-- Search results will be appended here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        $("ul#operation").siblings('a').attr('aria-expanded', 'true');
        $("ul#operation").addClass("show");
        $("#reviewed").addClass("active");
        $(document).on("change", ".check-all", function() {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });


        function submitPickup()
        { 
            if (confirm('Are You Sure to Assign These order to Best Express?')) {

                $("#submit_pickup_btn").prop('disabled',true);
                document.getElementById('submit_pickup_btn').innerHTML = "Uploading";
                $("#bulk-order-shipped").submit();
            }
        }
        $("#shipping_company").change(function() {
            var data = new FormData($('#sort_orders')[0]);
            var selected_name = $(this).find("option:selected").text();
            var selected_user = this.value;
            data.append('emp_name', selected_name);

            if (selected_user == 0) {

            } else {
                if (confirm('Are You Sure to Assign These order to ' + selected_name)) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('bulk-order-shipped') }}",
                        type: 'POST',
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response == 0) {
                                window.location.href =
                                    '{{ route('pickups.index', ['msg' => 'success']) }}';
                            } else {
                                window.location.href =
                                    '{{ route('pickups.index', ['msg' => 'failed']) }}';
                            }
                        }
                    });
                } else {}
            }

            console.log(selected_name);
            console.log(selected_user);
            console.log(data);
        });
        document.addEventListener('DOMContentLoaded', (event) => {
            document.querySelectorAll('form').forEach((form) => {
                form.addEventListener('keypress', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                    }
                });
            });
        });
        $(document).ready(function() {
            var orders = [];
            $('#order_number').on('change', function(e) {
                e.preventDefault();
                var orderNumber = $('#order_number').val();
                orderNumber=orderNumber.replace("Lvs", '');
                orderNumber=orderNumber.replace("lvs", '');
                orderNumber=orderNumber.replace("LVS", '');
                orderNumber=orderNumber.replace(" ", '');

                $.ajax({
                    url: '{{ url('search/order') }}',
                    method: 'GET',
                    data: {
                        order_number: orderNumber,
                        orders: orders,
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            var html = response.html;
                            orders.push(orderNumber);
                            $('#orders_tbody').prepend(html);
                            $('#order_number').val("");
                        } else {
                            alert(response.message);
                            $('#order_number').val("");
                        }
                    },
                    error: function() {
                        alert("An Error Occured");
                    }
                });
            });
        });
    </script>
@endsection
