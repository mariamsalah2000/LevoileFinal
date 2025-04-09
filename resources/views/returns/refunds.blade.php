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
                        <li class="breadcrumb-item">Refunds</li>
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
                                <div class="col-sm-3 justify-content-center">
                                    <div class="form-group mb-0">
                                        <label for="date">Search Return</label>
                                        <select class="form-select" name="status" value="{{$status}}">
                                            <option value="">Choose Status</option>
                                            <option value="new">New</option>
                                            <option value="refunded">Refunded</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-1 m-2 justify-content-center">
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                        <div class="card-body">
                            <h5 class="card-title">All Refunds</h5>
                           
                            <!-- Table with stripped rows -->
                            <table class="table datatable" id="example">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">Return No</th>
                                        <th scope="col" class="text-center">Order No</th>
                                        <th scope="col" class="text-center">Status</th>
                                        <th scope="col" class="text-center">Created Date</th>
                                        <th scope="col" class="text-center">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($refunds)
                                        @foreach ($refunds as $key => $refund)
                                            @if ($refund)
                                                <tr>
                                                    <td class="text-center">
                                                        Lvr{{ $refund->return_number }}
                                                    </td>
                                                    <td class="text-center">
                                                        <a class="btn-link"
                                                            href="{{ route('shopify.order.prepare', $refund->order_number) }}">Lvs{{ $refund->order_number }}</a>
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($refund->status == 'new')
                                                            <span
                                                                class="badge badge-inline badge-primary">{{ $refund->status }}</span>
                                                        
                                                        @else
                                                            <span
                                                                class="badge badge-inline badge-success">{{ $refund->status }}</span>
                                                        @endif
                                                    </td>
                                                    
                                                    <td class="text-center">{{ date('Y-m-d h:i:s', strtotime($refund->created_at)) }}</td>
                                                    
                                                    <td class="text-center">
                                                        @if($refund->status == "new")
                                                        <div class="col-2  mr-2 ml-6 text-center">
                                                            <div class="row mb-1">
                                                                <a class="btn btn-success text-white" onclick="openRefundModal(1)" title="Refund">
                                                                    <i class="bi bi-check-lg"></i>
                                                                </a>
                                                                Refund
                                                            </div>
                                                        </div>
                                                            
                                                        @endif
                                                    </td>
                                                </tr>
                                                <div class="modal fade" id="refund-modal-{{$refund->id}}" tabindex="-1">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Confirm Refund</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body fulfillment_form">
                                                                <form action="{{ route('refunds.confirmrefund') }}" class="row g-3" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="refund_id" value="{{$refund->id}}">
                                                                    <div class="col-md-6">
                                                                        <label for="order_id">Transaction Number Number</label>
                                                                        <input type="text" name="transaction_number" class="form-control"
                                                                            placeholder="Enter Transaction Reference" required>
                                                                    </div>
                                                                    <div class="col-4 justify-content-center">
                                                                        <button type="submit" class="btn btn-info">Submit</button>
                                                                    </div>


                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endisset
                                </tbody>
                                

                            </table>

                            <!-- End Table with stripped rows -->

                        </div>
                    <div class="row">
                        <div class="col-12">
                            <nav>
                                <ul class="pagination pagination-sm">
                                    {{ $refunds->links() }}
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
    <script type="text/javascript">
        $("ul#finance").siblings('a').attr('aria-expanded', 'true');
        $("ul#finance").addClass("show");
        $("#refunds").addClass("active");
        $(document).ready(function() {
            $('#daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'MM/DD/YYYY'
                }
            });
        });
        $('#daterange').val('');

        function openRefundModal(id) {
            $('#refund-modal-'+id).modal('show');
        }
    </script>
@endsection
