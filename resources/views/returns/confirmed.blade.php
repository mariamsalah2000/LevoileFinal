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
                <h1>Confirmed Returns</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">Edited Orders</li>
                    </ol>
                </nav>
            </div>
            <div class="col-4">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td>
                                <a href="#" onclick="addReturn()" style="float:right" class="btn btn-success">Add
                                    New</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
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

                                <div class="col-4 justify-content-center">

                                    <div class="container">
                                        <label for="date">Filter By Daterange</label>
                                        <input type="text" value="{{ $daterange }}" id="daterange" name="daterange"
                                            class="form-control">
                                    </div>

                                </div>
                                <div class="col-sm-3 justify-content-center">
                                    <div class="form-group mb-0">
                                        <label for="date">Search Return</label>
                                        <input type="text" class="form-control" id="search"
                                            name="search"@isset($search) value="{{ $search }}" @endisset
                                            placeholder="{{ 'Type Order or Return No & Hit Enter' }}">
                                    </div>
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
                                    <h6 class="d-inline-block pt-10px">Total Returns</h6>
                                </div>
                                <div class="col-sm-4">
                                    <h6 class="d-inline-block pt-10px">{{ $returns_count }} Orders</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Confirmed Return</h5>
                            {{--  <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->  --}}

                            <!-- Table with stripped rows -->
                            <table class="table datatable" id="example">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">Return No</th>
                                        <th scope="col" class="text-center">Order No</th>
                                        <th scope="col" class="text-center">Status</th>
                                        <th scope="col" class="text-center">Note</th>
                                        <th scope="col" class="text-center">Old Order Amount</th>
                                        <th scope="col" class="text-center">Return Amount</th>
                                        <th scope="col" class="text-center">New Order Amount</th>
                                        <th scope="col" class="text-center">Quantity</th>
                                        <th scope="col" class="text-center">Returned By</th>
                                        <th scope="col" class="text-center">Shipping On</th>
                                        <th scope="col" class="text-center">Created Date</th>
                                        <th scope="col" class="text-center">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($returns)
                                        @foreach ($returns as $key => $return)
                                            @if ($return)
                                                <tr>
                                                    <td class="text-center">
                                                        Lvr{{ $return->return_number }}
                                                    </td>
                                                    <td class="text-center">
                                                        <a class="btn-link"
                                                            href="{{ route('shopify.order.prepare', $return->order_number) }}">Lvs{{ $return->order_number }}</a>
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($return->status == 'In Progress' && $return->shipping_status == null)
                                                            <span
                                                                class="badge badge-inline badge-danger">{{ $return->status }}</span>
                                                        @elseif ($return->status == 'Returned')
                                                            <span
                                                                class="badge badge-inline badge-info">{{ $return->status }}</span>
                                                        @elseif ($return->status == 'Shipped' || $return->shipping_status == 'Shipped' || $return->shipping_status == 'shipped')
                                                            <span
                                                                class="badge badge-inline badge-success">{{ 'Shipped' }}</span>
                                                        @else
                                                            <span
                                                                class="badge badge-inline badge-primary">{{ $return->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">{{ $return->note ?? '-' }}</td>
                                                    <td class="text-center">
                                                        {{ $return->order->total_price }}</td>
                                                    <td class="text-center">{{ $return->amount }}</td>
                                                    <td class="text-center">{{ $return->order->total_price - $return->amount }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $return->qty }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $return->user ? $return->user->name : '-' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ ucfirst($return->shipping_on) }}
                                                    </td>
                                                    <td>{{ date('Y-m-d h:i:s', strtotime($return->created_at)) }}</td>
                                                    <td class="text-center">
                                                        <div class="col-5  mr-2 ml-2">
                                                            <div class="row mb-1">
                                                                <a class="btn btn-dark"
                                                                    href="{{ route('prepare.generate-return-invoice', $return->id) }}"
                                                                    title="Generate Invoice">
                                                                    <i class="bi bi-printer"></i>
                                                                </a>
                                                                Invoice
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endisset
                                </tbody>
                                <tfoot>

                                    <tr>
                                        <th class="text-center">{{ $returns_count }}</th>
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
                                    {{ $returns->links() }}
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <div class="modal fade" id="confirm-modal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Return</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body fulfillment_form">
                    <form action="{{ route('returns.confirm') }}" class="row g-3" method="get">

                        <div class="col-md-6">
                            <label for="order_id">Order Number</label>
                            <input type="text" name="order_number" class="form-control"
                                placeholder="Enter Order Number and Hit Enter" required>
                        </div>
                        <div class="col-4 justify-content-center">
                            <button type="submit" class="btn btn-info">Submit</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $("ul#returned_orders").siblings('a').attr('aria-expanded', 'true');
        $("ul#returned_orders").addClass("show");
        $("#confirmed_returns").addClass("active");
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
        $(document).ready(function() {
            $('#daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'MM/DD/YYYY'
                }
            });
        });
        $('#daterange').val('');

        function addReturn() {
            $('#confirm-modal').modal('show');
        }
    </script>
@endsection
