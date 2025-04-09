@extends('layouts.app')
@section('css')
    <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
    <style>
        .pagination {
            font-size: 14px;
            /* Adjust this size to make the links smaller */
        }

        .page-link {
            padding: 0.25rem 0.75rem;
            /* Control padding for smaller buttons */
            font-size: 0.875rem;
            /* Smaller font size */
        }
    </style>
@endsection
@section('content')

    <div class="pagetitle">
        <div class="row">

            <div class="col-8">
                <h1>Shipped Orders</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">Reports</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <form class="" action="" id="sort_orders" method="GET">

                        <div class="card-header row gutters-5">
                            <div class="row col-12 justify-content-center">

                                <div class="col-sm-2 justify-content-center">
                                    <div class="form-group mb-0">
                                        <label for="date">Filter By Shipping Date</label>
                                        <input type="date" class="form-control" value="{{ $date }}"
                                            name="date" placeholder="{{ 'Filter by Shipping date' }}"
                                            data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-2 justify-content-center">
                                    <div class="form-group mb-0">
                                        <label for="date">Filter By Order Date</label>
                                        <input type="date" class="form-control" value="{{ $order_date }}"
                                            name="order_date" placeholder="{{ 'Filter by Order date' }}"
                                            data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-3 justify-content-center">
                                    <div class="">
                                        <label for="date">Filter By Shipping Stauts</label>
                                        <select class="form-select aiz-selectpicker" name="shipping_status"
                                            id="shipping_status">
                                            <option value="" @if ($shipping_status == '') selected @endif>Select
                                            </option>
                                            <option value="normal" @if ($shipping_status == 'normal') selected @endif>
                                                Normal</option>
                                            <option value="delay" @if ($shipping_status == 'delay') selected @endif>
                                                Delayed</option>
                                            <option value="expected_today"
                                                @if ($shipping_status == 'expected_today') selected @endif>
                                                Expected Today</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-3 justify-content-center">
                                    <div class="form-group mb-0">
                                        <label for="date">Search Order</label>
                                        <input type="text" class="form-control" id="search"
                                            name="search"@isset($search) value="{{ $search }}" @endisset
                                            placeholder="{{ 'Type Order No & Hit Enter' }}">
                                    </div>
                                </div>
                                <div class="col-sm-2 justify-content-center">
                                    <label for="paginate">Show Orders</label>
                                    <select class="form-select aiz-selectpicker" name="paginate" id="paginate">
                                        <option value="0">Choose Number To SHow</option>
                                        <option value="15">15 Order</option>
                                        <option value="50">50 Order</option>
                                        <option value="100">100 Order</option>
                                        <option value="1000">All</option>
                                    </select>
                                </div>
                                <div class="col-sm-1 m-2 justify-content-center">
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-header row gutters-5">
                            <div class="row col-12">
                                <div class="col-sm-3">
                                    <h6 class="d-inline-block pt-10px">Total Orders</h6>
                                </div>
                                <div class="col-sm-4">
                                    <h6 class="d-inline-block pt-10px">{{ $total_orders }} Orders</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-header row gutters-5">
                            <div class="row col-12">
                                <div class="col-10"></div>
                                <div class="col-auto">
                                    <div class="form-group mb-0">
                                        <button type="button"
                                            onclick="exportTableToExcel('shippedOrdersTable', new Date().toISOString().slice(0, 10) + '_' + new Date().toTimeString().slice(0, 8).replace(/:/g, '-')+'_shipped_orders_data')"
                                            class="btn btn-warning">{{ 'Export Sheet' }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Shipped Orders</h5>
                            {{--  <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->  --}}

                            <!-- Table with stripped rows -->
                            <table class="table datatable" id="shippedOrdersTable">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">Order No</th>
                                        <th scope="col" class="text-center">Created Date</th>
                                        <th scope="col" class="text-center">Shipping Date</th>
                                        <th scope="col" class="text-center">City</th>
                                        <th scope="col" class="text-center">Estimate Arrival</th>

                                        <th scope="col" class="text-center">Shipping Duration</th>
                                        <th scope="col" class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($orders)
                                        @foreach ($orders as $key => $order)
                                            @if ($order)
                                                @php

                                                    $fee = $shipping_fees
                                                        ->where('city', $order->shipping_address['province'])
                                                        ->first();

                                                    $shippingDate = Carbon\Carbon::parse(
                                                        $order->histories->where('action', 'Shipped')->first()
                                                            ->created_at,
                                                    );
                                                    $shipping_date = $shippingDate;
                                                    $estimatedArrivalDays = $fee ? $fee->estimate_arrival : 2;
                                                    $estimatedArrivalDate = $shippingDate->addDays(
                                                        $estimatedArrivalDays,
                                                    );

                                                    // Compare with the current date
                                                    $now = Carbon\Carbon::now();

                                                    // Calculate the duration since the shipping date
                                                    $duration = Carbon\Carbon::parse(
                                                        $order->histories->where('action', 'Shipped')->first()
                                                            ->created_at,
                                                    )->diffForHumans($now, [
                                                        'parts' => 2, // Include up to two units, e.g., "4 days 1 hour"
                                                        'syntax' => Carbon\Carbon::DIFF_RELATIVE_TO_NOW,
                                                    ]);
                                                @endphp
                                                <tr>
                                                    <td class="text-center">
                                                        <a class="btn-link"
                                                            href="{{ route('shopify.order.prepare', $order->order_number) }}">Lvs{{ $order->order_number }}</a>
                                                    </td>
                                                    <td class="text-center">
                                                        {{ date('d/m/Y', strtotime($order->created_at)) }}</td>
                                                    <td class="text-center">
                                                        {{ date('d/m/Y', strtotime($order->histories->where('action', 'Shipped')->first()->created_at)) }}
                                                    </td>
                                                    <td class="text-center">{{ $order->shipping_address['province'] }}</td>
                                                    <td class="text-center">{{ $estimatedArrivalDays }} Days
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $duration }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($now->greaterThan($estimatedArrivalDate))
                                                            @php
                                                                $overdueDays = $now->diffInDays($estimatedArrivalDate);
                                                            @endphp
                                                            <span
                                                                class="badge badge-danger">{{ "$overdueDays Days Delay" }}</span>
                                                        @elseif($estimatedArrivalDate->greaterThan($now))
                                                            @php
                                                                $daysLeft = $now->diffInDays($estimatedArrivalDate);
                                                            @endphp
                                                            <span
                                                                class="badge badge-primary">{{ "$daysLeft Days Left." }}</span>
                                                        @else
                                                            <span class="badge badge-success">{{ 'Expected Today' }}</span>
                                                        @endif
                                                    </td>

                                                </tr>
                                            @endif
                                        @endforeach
                                    @endisset
                                </tbody>
                                <tfoot>

                                    <tr>
                                        <th class="text-center">{{ $total_orders }}</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>


                                </tfoot>

                            </table>

                            <!-- End Table with stripped rows -->

                        </div>

                    </form>
                    <div class="row">
                        <div class="col-12">
                            <nav>
                                <ul class="pagination pagination-sm">
                                    {{ $orders->links() }}
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('scripts')
    <script type="text/javascript" src="<?php echo asset('vendor/jquery/jquery.min.js'); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

    <script type="text/javascript">
        $("ul#reports").siblings('a').attr('aria-expanded', 'true');
        $("ul#reports").addClass("show");
        $("#shipped_orders").addClass("active");

        function exportTableToExcel(tableID, filename = '') {
            // Clone the original table
            const originalTable = document.getElementById(tableID);
            const clonedTable = originalTable.cloneNode(true);

            // Locate the index of the "Action" column dynamically
            let actionColumnIndex = -1;
            clonedTable.querySelectorAll('thead tr th').forEach((header, index) => {
                if (header.textContent.trim() === 'Action') {
                    actionColumnIndex = index;
                }
            });

            // Ensure "Action" column was found before attempting to remove it
            if (actionColumnIndex !== -1) {
                // Remove the "Action" header cell
                clonedTable.querySelectorAll('thead tr').forEach(row => {
                    row.deleteCell(actionColumnIndex);
                });

                // Remove the "Action" cell in each body row
                clonedTable.querySelectorAll('tbody tr').forEach(row => {
                    row.deleteCell(actionColumnIndex);
                });
            }

            // Convert the modified cloned table to a worksheet
            const worksheet = XLSX.utils.table_to_sheet(clonedTable);

            // Create a new workbook and append the worksheet
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

            // Use the provided filename or default
            filename = filename ? filename + '.xlsx' : 'shipped_orders.xlsx';

            // Export the file
            XLSX.writeFile(workbook, filename);
        }
    </script>
@endsection
