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
                <h1>Shortage Products Orders</h1>
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
                            <div class="row col-12">

                                <div class="col-sm-3">
                                    <div class="form-group mb-0">
                                        <label for="date">Filter By Order Date</label>
                                        <input type="date" class="form-control" value="{{ $date }}"
                                            name="date" placeholder="{{ 'Filter by order date' }}" data-format="DD-MM-Y"
                                            data-separator=" to " data-advanced-range="true" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group mb-0">
                                        <label for="date">Filter By Hold Date</label>
                                        <input type="date" class="form-control" value="{{ $hold_date }}"
                                            name="hold_date" placeholder="{{ 'Filter by hold date' }}" data-format="DD-MM-Y"
                                            data-separator=" to " data-advanced-range="true" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group mb-0">
                                        <label for="search">Search Product</label>
                                        <input type="text" class="form-control" value="{{ $search }}"
                                            name="search" placeholder="{{ 'Search For Product' }}">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group mb-0 mt-4">
                                        <button type="submit" name="button" value="filter"
                                            class="btn btn-primary">{{ 'Filter' }}</button>
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
                                    <h6 class="d-inline-block pt-10px">{{ $orders_count }} Orders</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-header row gutters-5">
                            <div class="row col-12">
                                <div class="col-10"></div>
                                <div class="col-auto">
                                    <div class="form-group mb-0">
                                        <button type="submit" name="button" value="export"
                                            class="btn btn-warning">{{ 'Export Sheet' }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Shortage Products Orders</h5>
                            {{--  <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->  --}}

                            <!-- Table with stripped rows -->
                            <table class="table datatable" id="example">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">Order Date</th>
                                        <th scope="col" class="text-center">Hold Date</th>
                                        <th scope="col" class="text-center">Total Items</th>
                                        <th scope="col" class="text-center">Shortage Items</th>
                                        <th scope="col" class="text-center">Total Price</th>
                                        <th scope="col" class="text-center">Shortage Price</th>
                                        <th scope="col" class="text-center">Assigned To</th>
                                        <th scope="col" class="text-center">Status</th>
                                        <th scope="col" class="text-center">Trials</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($orders)
                                        @foreach ($orders as $key => $order)
                                            @if ($order)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ date('Y-m-d h:i:s', strtotime($order->created_at)) }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ date('Y-m-d h:i:s', strtotime($order->hold_date)) }}</td>
                                                    <td class="text-center">{{ $order->total_items }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $order->shortage_items }}
                                                    </td>
                                                    <td class="text-center">{{ $order->total_price }}</td>
                                                    <td class="text-center">
                                                        {{ $order->shortage_price }}
                                                    </td>
                                                    <td class="text-center">{{ $order->user->name }}</td>
                                                    <td class="text-center">{{ $order->status }}</td>
                                                    <td class="text-center">{{ $order->trial }}</td>


                                                </tr>
                                            @endif
                                        @endforeach
                                    @endisset
                                </tbody>
                                <tfoot>

                                    <tr>
                                        <th class="text-center">{{ $orders_count }}</th>
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
    <script type="text/javascript">
        $("ul#reports").siblings('a').attr('aria-expanded', 'true');
        $("ul#reports").addClass("show");
        $("#shortage_report").addClass("active");
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
    </script>
@endsection
