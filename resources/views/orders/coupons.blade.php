@extends('layouts.app')
@section('content')
    <div class="pagetitle">
        <div class="row">
            <div class="col-8">
                <h1>Coupon Codes</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">Coupons</li>
                    </ol>
                </nav>

            </div>
            <div class="col-4">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td><a onclick="create()" style="float:right" class="btn btn-success">Create Coupon</a></td>
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


                    <form method="get" action="#">
                        <div class="card-header row gutters-5">
                            <div class="row col-12">

                                <div class="col-sm-3">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control" id="search"
                                            name="search"@isset($search) value="{{ $search }}" @endisset
                                            placeholder="{{ 'Type Coupon Name & hit Enter' }}">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-primary">{{ 'Filter' }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="card-body">
                        <h5 class="card-title">Your Coupon Codes</h5>
                        {{-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> --}}
                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Coupon Name</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($coupons)
                                    @if ($coupons !== null)
                                        @foreach ($coupons as $key => $coupon)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $coupon->name }}</td>
                                                <td>{{ $coupon->amount }}</td>
                                                <td>{{ date('Y-m-d', strtotime($coupon->start_date)) }}</td>
                                                <td>{{ date('Y-m-d', strtotime($coupon->end_date)) }}</td>
                                                <td><a id="edit"
                                                        onclick='edit("{{ $coupon->id }}","{{ $coupon->name }}","{{ $coupon->start_date }}","{{ $coupon->end_date }}")'
                                                        class="btn btn-primary">Edit</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endisset
                            </tbody>
                        </table>
                        <div class="text-center pb-2">
                            {{ $coupons->links() }}
                        </div>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>
        <div id="edit-coupon" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
            class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal_header" class="modal-title">Edit Coupon</h5>&nbsp;&nbsp;

                        <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div id="label-content">
                            <form action="{{ route('update-coupon') }}" method="POST">
                                @csrf
                                <input type="hidden" name="coupon_id">
                                <label>Coupon Name*</label>
                                <input name="name" type="text" required id="name" value=""
                                    class="form-control" placeholder="Coupon Name" required>
                                <label>Coupon Type*</label>
                                <select class="form-select" name="type">
                                    <option value="value" selected>Value</option>
                                    <option value="percentage">Percentage</option>
                                </select>
                                <label>Coupon Amount*</label>
                                <input name="amount" type="text" required id="amount" value=""
                                    class="form-control" placeholder="Coupon Amount" required>
                                <label>Start Date*</label>
                                <input name="start_date" type="date" required id="start_date" value=""
                                    class="form-control" required>
                                <label>End Date*</label>
                                <input name="end_date" type="date" required id="end_date" value=""
                                    class="form-control" required>
                        </div>
                        <button type="submit" id="submit-btn" type="button" class="btn btn-default btn-sm"><i
                                class="dripicons-print"></i>
                            {{ trans('Save') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="create-coupon" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
            class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal_header" class="modal-title">Create Coupon</h5>&nbsp;&nbsp;

                        <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close"
                            class="close"><span aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div id="label-content">
                            <form action="{{ route('create-coupon') }}" method="POST">
                                @csrf
                                <label>Coupon Name*</label>
                                <input name="name" type="text" required id="name" value=""
                                    class="form-control" placeholder="Coupon Name" required>
                                <label>Coupon Type*</label>
                                <select class="form-select" name="type">
                                    <option value="value" selected>Value</option>
                                    <option value="percentage">Percentage</option>
                                </select>
                                <label>Coupon Amount*</label>
                                <input name="amount" type="text" required id="amount" value=""
                                    class="form-control" placeholder="Coupon Amount" required>
                                <label>Start Date*</label>
                                <input name="start_date" type="date" required id="start_date" value=""
                                    class="form-control" required>
                                <label>End Date*</label>
                                <input name="end_date" type="date" required id="end_date" value=""
                                    class="form-control" required>
                                </tr>
                                </table>
                        </div>
                        <button type="submit" id="submit-btn" type="button" class="btn btn-primary btn-sm"><i
                                class="dripicons-print"></i>
                            {{ trans('Save') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script type="text/javascript">
        $("ul#operation").siblings('a').attr('aria-expanded', 'true');
        $("ul#operation").addClass("show");
        $("#coupons").addClass("active");

        function edit(id = null, name = null, start_date = null, end_date = null, amount = null) {
            $('input[name="coupon_id"]').val(id);
            $('input[name="name"]').val(name);
            $('input[name="start_date"]').val(start_date);
            $('input[name="end_date"]').val(end_date);
            $('input[name="amount"]').val(amount);
            $('#edit-coupon').modal('show');

        }

        function create(id = null, name = null, start_date = null, end_date = null) {

            $('#create-coupon').modal('show');

        }

        $("#print-btn").on("click", function() {
            var divToPrint = document.getElementById('print-barcode');
            var newWin = window.open('', 'Print-Window');
            newWin.document.open();
            newWin.document.write(
                '<style type="text/css">@media print { #modal_header { display: none } #print-btn { display: none } #close-btn { display: none } } table.barcodelist { page-break-inside:auto } table.barcodelist tr { page-break-inside:avoid; page-break-after:auto }</style><body onload="window.print()">' +
                divToPrint.innerHTML + '</body>');
            newWin.document.close();
            setTimeout(function() {
                newWin.close();
            }, 10);
        });
    </script>
@endsection
