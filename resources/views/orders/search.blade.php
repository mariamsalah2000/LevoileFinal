@extends('layouts.app')
@section('css')
    <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
    <style>
        .shadow {
            -moz-box-shadow: 3px 3px 5px 6px #ccc;
            -webkit-box-shadow: 3px 3px 5px 6px #ccc;
            box-shadow: 3px 3px 5px 6px #ccc;
            border-radius: 4%;
            /*supported by all latest Browser*/
            -moz-border-radius: 4%;
            /*For older Browser*/
            -webkit-border-radius: 4%;
            /*For older Browser*/

            width: 130px;
            height: 50px;
        }

        .shadow2 {
            border-radius: 4%;

            width: 130px;
            height: 115px;
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
                        <li class="breadcrumb-item">Search Orders</li>
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
                                        <input type="text" class="form-control" id="search"
                                            name="search"@isset($search) value="{{ $search }}" @endisset
                                            placeholder="{{ 'Type Order Number & Hit Enter' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">


                            {{--  <!-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> -->  --}}

                            <!-- Table with stripped rows -->
                            <table class="table datatable" id="example">
                                <thead>
                                    <tr>
                                        <th scope="col">Order No.</th>
                                        <th scope="col">Order Source</th>
                                        <th scope="col">Customer Data</th>
                                        <th scope="col">Channel</th>
                                        <th scope="col" class="text-center">Payment Type</th>
                                        <th scope="col" class="text-center">Subtotal</th>
                                        <th scope="col">Shipping</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Delivery Status</th>
                                        <th scope="col" class="text-center">Assigned To</th>

                                        <th scope="col">Created Date</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($order)
                                        @php
                                            $total_shipping = 0;
                                            foreach ($order['shipping_lines'] as $ship) {
                                                $total_shipping = $ship['price'];
                                            }
                                            $returns = 0;
                                            $return = \App\Models\ReturnedOrder::where(
                                                'order_number',
                                                $order->order_number,
                                            )->first();
                                            if ($return) {
                                                $returns = \App\Models\ReturnDetail::where(
                                                    'return_id',
                                                    $return->id,
                                                )->sum('amount');
                                            }

                                        @endphp
                                        <tr>
                                            <td><a class="btn-link"
                                                    href="{{ route('shopify.order.prepare', $order->order_number) }}">Lvs{{ $order->order_number }}</a>
                                            </td>
                                            @php
                                                $shipping_address = $order['shipping_address'];

                                            @endphp
                                            <td>{{ $order->source_name ?? 'Online' }}</td>
                                            <td>
                                                <p>{{ isset($shipping_address['name']) ? $shipping_address['name'] : '' }}
                                                    / </p>
                                                <p>
                                                    {{ isset($shipping_address['phone']) ? $shipping_address['phone'] : '' }}
                                                </p>
                                            </td>
                                            <td>{{ $order->channel ?? 'Online' }}</td>
                                            <td class="text-center">
                                                {{ $order->financial_status == 'paid' ? 'Paid' : 'Cash' }}
                                            </td>
                                            <td class="text-center">{{ $order->subtotal_price }}</td>
                                            <td class="text-center">{{ $total_shipping }}</td>
                                            <td class="text-center">{{ $order->total_price - $returns }}</td>
                                            
                                            <td>
                                                @if ($order->fulfillment_status == 'processing')
                                                    <span
                                                        class="badge badge-inline badge-danger">{{ $order->fulfillment_status }}</span>
                                                @elseif ($order->fulfillment_status == 'distributed')
                                                    <span
                                                        class="badge badge-inline badge-info">{{ $order->fulfillment_status }}</span>
                                                @elseif ($order->fulfillment_status == 'prepared')
                                                    <span
                                                        class="badge badge-inline badge-success">{{ $order->fulfillment_status }}</span>
                                                @elseif ($order->fulfillment_status == 'shipped')
                                                    <span
                                                        class="badge badge-inline badge-primary">{{ $order->fulfillment_status }}</span>
                                                @elseif ($order->fulfillment_status == 'hold')
                                                    <span
                                                        class="badge badge-inline badge-warning">{{ $order->fulfillment_status }}</span>
                                                @elseif ($order->fulfillment_status == 'reviewed')
                                                    <span
                                                        class="badge badge-inline badge-secondary">{{ $order->fulfillment_status }}</span>
                                                @elseif ($order->fulfillment_status == 'cancelled')
                                                    <span
                                                        class="badge badge-inline badge-danger">{{ $order->fulfillment_status }}</span>
                                                @elseif ($order->fulfillment_status == 'fulfilled')
                                                    <span
                                                        class="badge badge-inline badge-dark">{{ $order->fulfillment_status }}</span>
                                                @elseif ($order->fulfillment_status == 'returned' || $order->fulfillment_status == 'Returned')
                                                    <span
                                                        class="badge badge-inline badge-danger">{{ 'Returned' }}</span>
                                                @elseif ($order->fulfillment_status == 'delivered' || $order->fulfillment_status == 'Delivered')
                                                    <span
                                                        class="badge badge-inline badge-success">{{ $order->fulfillment_status }}</span>
                                                @elseif ($order->fulfillment_status == 'partial_return' || $order->fulfillment_status == 'Partial Return')
                                                    <span
                                                        class="badge badge-inline badge-warning">{{ 'Partial Return' }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $order->prepare ? $order->prepare->user->name : '-' }}</td>
                                            <td>{{ date('Y-m-d h:i:s', strtotime($order->created_at)) }}</td>


                                            <td class="text-right">
                                                <div class="row">
                                                    <div class="col-3 mr-2 ml-2">
                                                        <div class="row  mb-1">
                                                            <a class="btn btn-warning"
                                                                href="{{ route('prepare.order-history', $order->id) }}"
                                                                title="Order History">
                                                                <i class="bi bi-clock-history"></i>
                                                            </a>
                                                            History
                                                        </div>
                                                    </div>

                                                    @if ($order->fulfillment_status != 'shipped')
                                                        <div class ="col-3  mr-2 ml-2">
                                                            <div class="row mb-1">
                                                                <a class="btn btn-danger"
                                                                    onclick="cancel_order({{ $order->id }})"
                                                                    title="Cancel Order">
                                                                    <i class="bi bi-x-square"></i>
                                                                </a>
                                                                Cancel
                                                            </div>
                                                        </div>
                                                        <div class ="col-3  mr-2 ml-2">
                                                            <div class="row mb-1">
                                                                <a class="btn btn-secondary"
                                                                    href="{{ url('/pos/edit/' . $order->order_id) }}"
                                                                    title="Edit Order">
                                                                    <i class="bi bi-pen"></i>
                                                                </a>
                                                                Edit
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if ($order->fulfillment_status == 'prepared' && auth()->user()->role_id != 6)
                                                        <div class="col-3  mr-2 ml-2">
                                                            <div class="row mb-1">
                                                                <a class="btn btn-primary"
                                                                    href="{{ route('prepare.review', $order->id) }}"
                                                                    title="Review Order">
                                                                    <i class="bi bi-check-lg"></i>
                                                                </a>
                                                                Review
                                                            </div>
                                                        </div>
                                                    @elseif ($order->fulfillment_status == 'fulfilled')
                                                        <div class="col-3  mr-2 ml-2">
                                                            <div class="row mb-1">
                                                                <a class="btn btn-dark"
                                                                    href="{{ route('prepare.generate-invoice', $order->id) }}"
                                                                    title="Generate Invoice">
                                                                    <i class="bi bi-printer"></i>
                                                                </a>
                                                                Invoice
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="11">
                                                <div class="text-center fw-bold">
                                                    Order Not Found, Please Re-Sync It
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
                            <input type="hidden" name="id" />
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
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        $("ul#operation").siblings('a').attr('aria-expanded', 'true');
        $("ul#operation").addClass("show");
        $("#prepares").addClass("active");
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
            $('input[name=id]').val(id);
            $('#cancel-order-modal').modal('show');
        }
    </script>
@endsection
