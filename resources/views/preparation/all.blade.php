@extends('layouts.app')
@section('css')
    <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
    <style>
        :root {
            --shadow-color: #ccc;
            --shadow-size: 3px 3px 5px 6px;
            --shadow-radius: 8%;
            --highlight-bg: rgb(239, 208, 99);
            --prepared-bg: rgb(47, 45, 203);
            --reviewed-bg: rgb(27, 35, 51);
            --hold-bg: rgb(231, 138, 66);
            --distributed-bg: rgb(39, 140, 187);
            --returned-bg: rgb(47, 45, 46);
            --Returned-bg: rgb(47, 45, 46);
            --delivered-bg: rgb(5, 75, 47);
            --cancelled-bg: rgb(209, 29, 23);
            --processing-bg: rgb(209, 29, 23);
            --partial_return-bg: rgb(90, 86, 86);
            --fulfilled-bg: rgb(5, 64, 85);
            --shipped-bg: rgb(12, 164, 101);
        }

        .shadow-box {
            box-shadow: var(--shadow-size) var(--shadow-color);
            border-radius: var(--shadow-radius);
            width: 130px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            font-weight: bold;
        }

        .shadow-box-large {
            width: 130px;
            height: 115px;
        }

        .order-status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-weight: 600;
            transition: transform 0.2s ease-in-out;
        }

        .order-status-badge:hover {
            transform: scale(1.05);
        }

        .btn-group .btn {
            margin: 0 5px;
        }

        .table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            text-align: center;
        }

        .table tbody td {
            vertical-align: middle;
        }

        .custom-filter-select {
            background-color: #f4f7fa;
            border-radius: 4px;
            padding: 5px;
        }

        .btn-action {
            font-size: 14px;
            padding: 5px 10px;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .btn-action:hover {
            background-color: #007bff;
            color: white;
        }

        .custom-filter-select,
        #daterange {
            background-color: #f4f7fa;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 8px;
            font-size: 14px;
        }

        .form-label {
            font-weight: bold;
            font-size: 12px;
            color: #555;
            margin-bottom: 4px;
        }

        .card-header {
            background-color: #fafbfc;
            padding: 15px;
            border-bottom: 1px solid #e3e6ea;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        @media (max-width: 768px) {
            .form-group {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')

    <div class="pagetitle">
        <div class="row">
            <div class="col-8">
                <h1>Orders</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">Assigned Orders</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <form action="" id="sort_orders" method="GET">
                        {{--  <div class="card-header row gutters-5">
                            <div class="row col-12">
                                @foreach (['processing' => '--highlight-bg', 'prepared' => '--prepared-bg', 'hold' => '--hold-bg', 'cancelled' => '--cancelled-bg', 'fulfilled' => '--fulfilled-bg', 'shipped' => '--shipped-bg'] as $status => $bg)
                                    <div class="col-2 d-flex justify-content-center align-items-center">
                                        <div class="shadow-box" style="background-color: var({{ $bg }});">
                                            {{ ucfirst($status) }} Orders
                                            <br>
                                            {{ isset($all_orders[$status]) ? $all_orders[$status] : 0 }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>  --}}

                        <div class="card-header row align-items-center">

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="delivery_status" class="form-label">Delivery Status</label>
                                    <select class="form-select custom-filter-select" name="delivery_status"
                                        id="delivery_status">
                                        <option value="">Select Delivery Status</option>
                                        @foreach (['processing', 'distributed', 'prepared', 'hold', 'reviewed', 'shipped', 'cancelled', 'fulfilled', 'returned', 'delivered', 'partial_return'] as $status)
                                            <option value="{{ $status }}"
                                                @if ($delivery_status == $status) selected @endif>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="daterange" class="form-label">Date Range</label>
                                    <input type="text" value="{{ $daterange }}" id="daterange" name="daterange"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="payment_method" class="form-label">Payment Method</label>
                                    <select class="form-select custom-filter-select" name="payment_status"
                                        id="payment_method">
                                        <option value="">Select Payment Method</option>
                                        <option value="visa" @if ($payment_status == 'visa') selected @endif>Visa
                                        </option>
                                        <option value="cod" @if ($payment_status == 'cash') selected @endif>Cash
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="staff_id" class="form-label">Prepare Staff</label>
                                    <select class="form-select custom-filter-select" name="prepare_staff" id="staff_id">
                                        <option value="">Select Prepare Staff</option>
                                        @foreach ($prepare_users as $key => $prepare_user)
                                            <option value="{{ $prepare_user->id }}"
                                                @if ($prepare_staff == $prepare_user->id) selected @endif>
                                                {{ ucfirst($prepare_user->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="staff_id" class="form-label">Search for Order</label>

                                    <input type="text" class="form-control" name="search" value="{{ $sort_search }}"
                                        placeholder="Enter Order Number and Press Enter">
                                </div>
                            </div>
                            <div class="col-auto">
                                <input class="btn btn-success" type="submit" value="filter" name="button">
                            </div>
                        </div>



                        <div class="card-body">
                            <div class="row col-12">
                                <div class="col-6">
                                    <h5 class="card-title">Assigned Orders</h5>
                                </div>
                                <div class="col-2">
                                    <h5 class="card-title">Re-Assign:</h5>
                                </div>
                                <div class="col-2">
                                    <select class="form-select mt-2" name="prepare_emp" id="prepare_emp">
                                        <option value="0">Choose Prepare Emp</option>
                                        @foreach ($prepare_users_list['name'] as $key => $user_prepare)
                                            <option value="{{ $prepare_users_list['id'][$key] }}">{{ $user_prepare }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <input class="btn btn-warning m-2" type="submit" value="export" name="button">
                                </div>
                            </div>

                            <!-- Orders Table -->
                            <table class="table table-bordered table-striped datatable" id="example">
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
                                        <th>Order No.</th>
                                        <th>Order Source</th>
                                        <th>Customer Data</th>
                                        <th>Channel</th>
                                        <th class="text-center">Payment Type</th>
                                        <th class="text-center">Subtotal</th>
                                        <th class="text-center">Shipping</th>
                                        <th class="text-center">Total</th>
                                        <th>Delivery Status</th>
                                        <th class="text-center">Assigned To</th>
                                        <th>Created Date</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($orders)
                                        @foreach ($orders as $order)
                                            @if (!$order->order)
                                                @continue
                                            @endif
                                            @php
                                                $total_shipping = collect($order->order['shipping_lines'])->sum(
                                                    'price',
                                                );
                                                $returns = 0;
                                                $return = \App\Models\ReturnedOrder::where(
                                                    'order_number',
                                                    $order->order->order_number,
                                                )->first();
                                                if ($return) {
                                                    $returns = \App\Models\ReturnDetail::where(
                                                        'return_id',
                                                        $return->id,
                                                    )->sum('amount');
                                                }
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="aiz-checkbox-inline">
                                                            <label class="aiz-checkbox">
                                                                <input type="checkbox" class="check-one" name="id[]"
                                                                    value="{{ $order->order_id }}">
                                                                <span class="aiz-square-check"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><a
                                                        href="{{ route('shopify.order.prepare', $order->order->order_number) }}">Lvs{{ $order->order->order_number }}</a>
                                                </td>
                                                <td>{{ $order->source_name ?? 'Online' }}</td>
                                                <td>
                                                    <p>{{ $order->order['shipping_address']['name'] ?? '' }} /</p>
                                                    <p>{{ $order->order['shipping_address']['phone'] ?? '' }}</p>
                                                </td>
                                                <td>{{ $order->channel ?? 'Online' }}</td>
                                                <td class="text-center">
                                                    {{ $order->order->financial_status == 'paid' ? 'Paid' : 'Cash' }}</td>
                                                <td class="text-center">{{ $order->order->subtotal_price }}</td>
                                                <td class="text-center">{{ $total_shipping }}</td>
                                                <td class="text-center">{{ $order->order->total_price }}</td>
                                                <td class="text-center">
                                                    <span class="order-status-badge"
                                                        style="background-color: var(--{{ $order->order->fulfillment_status ?? 'fulfilled' }}-bg); color:white">{{ ucfirst($order->order->fulfillment_status ?? 'fulfilled') }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    {{ $order->user->name ?? 'N/A' }}
                                                </td>
                                                <td>{{ $order->order->created_at }}</td>
                                                @include('layouts.order-actions', ['order' => $order])

                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                                {{ $orders->links() }}
                            </table>
                        </div><!-- End Table Card -->
                    </form>
                </div><!-- End Card -->
            </div>
        </div><!-- End Row -->
        <div class="modal fade" id="cancel-order-modal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cancel Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body fulfillment_form">
                        <form action="{{ route('orders.update_delivery_status') }}" class="row g-3" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" />
                            <input type="hidden" name="status" value="cancelled" />

                            <div class="col-md-6">
                                <label for="reason">Reason</label>
                                <select class="form-select" name="reason" data-minimum-results-for-search="Infinity">
                                    <option value="" disabled="">Select</option>
                                    <option value="CUSTOMER_REQUEST">Customer changed or canceled order</option>
                                    <option value="BROUGHT_FROM_STORE">Customer Brought From Store</option>
                                    <option value="ORDER_LATE">Order Late Recieve</option>
                                    <option value="WRONG_SHIPPING_INFO">Wrong Shipping Info</option>
                                    <option value="REPEATED_ORDER">Repeated Order</option>
                                    <option value="FAKE_ORDER">FAKE ORDER</option>
                                    <option value="ORDER_CONFIRMED_BY_MISTAKE">Client Confirmed Order By Mistake</option>
                                    <option value="INVENTORY">Items unavailable</option>
                                    <option value="ORDER_UPDATED_AFTER_SHIPPING">Client Updated the Order After Being
                                        Shipped
                                    </option>
                                    <option value="OTHER">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="note">Cancelling Note*</label>
                                <input type="text" name="note" class="form-control"
                                    placeholder="Enter Reason and Hit Enter" required>
                            </div>
                            <div class="col-4 justify-content-center">
                                <button type="submit" class="btn btn-danger">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- End Section -->

@endsection

@section('scripts')
    <script type="text/javascript">
        $("ul#operation").siblings('a').attr('aria-expanded', 'true');
        $("ul#operation").addClass("show");
        $("#prepares").addClass("active");
        $(document).ready(function() {
            $('#daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'MM/DD/YYYY'
                }
            });
            $('#daterange').val('');
        });

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
        $("#prepare_emp").change(function() {
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
                        url: "{{ route('bulk-order-assign') }}",
                        type: 'POST',
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response == 0) {
                                location.reload();
                            } else {
                                location.reload();
                            }
                        }
                    });
                } else {}
            }

            console.log(selected_name);
            console.log(selected_user);
            console.log(data);
        });

        function cancel_order(id) {
            $('input[name=order_id]').val(id);
            $('#cancel-order-modal').modal('show');
        }
    </script>
@endsection
