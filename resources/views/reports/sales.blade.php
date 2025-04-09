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
                <h1>Sales Report</h1>
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

                                <div class="col-sm-3 justify-content-center">

                                    <div class="container">
                                        <label for="date">Filter By Order Daterange</label>
                                        <input type="text" value="{{ $daterange }}" id="daterange" name="daterange"
                                            class="form-control">
                                    </div>

                                </div>
                                <div class="col-sm-3 justify-content-center">

                                    <div class="container">
                                        <label for="date">Filter By Delivered Daterange</label>
                                        <input type="text" value="{{ $delivered_daterange }}" id="delivered_daterange"
                                            name="delivered_daterange" class="form-control">
                                    </div>

                                </div>
                                <div class="col-sm-3 justify-content-center">

                                    <div class="container">
                                        <label for="date">Filter By Shipped Daterange</label>
                                        <input type="text" value="{{ $shipped_daterange }}" id="shipped_daterange"
                                            name="shipped_daterange" class="form-control">
                                    </div>

                                </div>
                                <div class="col-sm-3 justify-content-center">

                                    <div class="container">
                                        <label for="date">Filter By Return Daterange</label>
                                        <input type="text" value="{{ $returned_daterange }}" id="returned_daterange"
                                            name="returned_daterange" class="form-control">
                                    </div>

                                </div>
                                <div class="col-sm-3 justify-content-center">
                                    <div class="">
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

                                <div class="col-sm-3 justify-content-center">
                                    <div class="form-group mb-0">
                                        <label for="date">Search Order</label>
                                        <input type="text" class="form-control" id="search"
                                            name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset
                                            placeholder="{{ 'Type Product Name or SKU & Hit Enter' }}">
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
                                <div class="col-10"></div>
                                <div class="col-auto">
                                    <div class="form-group mb-0">
                                        <button type="button"
                                            onclick="exportTableToExcel('salesTable', new Date().toISOString().slice(0, 10) + '_' + new Date().toTimeString().slice(0, 8).replace(/:/g, '-')+'_sales_data')"
                                            class="btn btn-warning">{{ 'Export Sheet' }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Sales Report</h5>
                            {{--  <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->  --}}

                            <!-- Table with stripped rows -->
                            <table class="table datatable" id="salesTable">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">Created Date</th>
                                        <th scope="col" class="text-center">Order No</th>
                                        <th scope="col" class="text-center">Product</th>
                                        <th scope="col" class="text-center">Product Price</th>
                                        <th scope="col" class="text-center">Product Qty</th>
                                        <th scope="col" class="text-center">Shipping Single Price</th>
                                        <th scope="col" class="text-center">Prepared By</th>
                                        <th scope="col" class="text-center">Delivery Status</th>
                                        <th scope="col" class="text-center">Item Status</th>
                                        <th scope="col" class="text-center">Sync Date</th>
                                        <th scope="col" class="text-center">Shipping Date</th>
                                        <th scope="col" class="text-center">Delivery Date</th>
                                        <th scope="col" class="text-center">Return Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($products)
                                        @foreach ($products as $key => $product)
                                            @if ($product)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ date('d/m/Y', strtotime($product->order->created_at)) }}</td>
                                                    <td class="text-center">
                                                        <a class="btn-link"
                                                            href="{{ route('shopify.order.prepare', $product->order->order_number) }}">Lvs{{ $product->order->order_number }}</a>
                                                    </td>

                                                    <td class="text-center">
                                                        {{ $product->product_name }}<br>{{ $product->product_sku }}</td>
                                                    <td class="text-center">{{ $product->price }}
                                                    </td>
                                                    <td class="text-center">{{ $product->order_qty }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ ceil(collect($product->order->shipping_lines)->sum('price') / $product->order->products->count()) }}
                                                    </td>
                                                    <td class="text-center">{{ $product->prepare->user->name }}</td>
                                                    <td class="text-center">{{ $product->order->fulfillment_status }}</td>

                                                    @if ($product->is_refunded)
                                                        <td class="text-center">{{ 'Removed' }}</td>
                                                    @elseif ($product->return_status)
                                                        <td class="text-center">{{ $product->return_status }}</td>
                                                    @else
                                                        <td class="text-center">{{ $product->product_status }}</td>
                                                    @endif

                                                    <td class="text-center">
                                                        {{ $product->order->created_at_date ?? '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $product->order->histories->where('action', 'Shipped')->first()?->created_at ?? '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $product->order->histories->where('action', 'Delivered')->first()?->created_at ?? '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $product->order->histories->where('action', 'Returned')->first()?->created_at ?? '-' }}
                                                    </td>

                                                </tr>
                                            @endif
                                        @endforeach
                                    @endisset
                                </tbody>

                            </table>

                            <!-- End Table with stripped rows -->

                        </div>

                    </form>
                    <div class="row">
                        <div class="col-12">
                            <nav>
                                <ul class="pagination pagination-sm">
                                    {{ $products->links() }}
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
        $("#sales_report").addClass("active");

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
            filename = filename ? filename + '.xlsx' : 'sales_report.xlsx';

            // Export the file
            XLSX.writeFile(workbook, filename);
        }

        $(document).ready(function() {
            $('#daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'MM/DD/YYYY'
                }
            });

            $('#shipped_daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'MM/DD/YYYY'
                }
            });
            $('#delivered_daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'MM/DD/YYYY'
                }
            });
            $('#returned_daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'MM/DD/YYYY'
                }
            });

            $('#daterange').val('');
            $('#shipped_daterange').val('');
            $('#delivered_daterange').val('');
            $('#returned_daterange').val('');
        });
    </script>
@endsection
