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
                        <li class="breadcrumb-item">New Assigned Orders</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Orders</h5>

                        <!-- Table with stripped rows -->
                        <table class="table datatable" id="example">
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
                                @isset($orders)
                                    @foreach ($orders as $key => $order)
                                        @if ($order)
                                            @php
                                                $total_shipping = 0;
                                                foreach ($order->order['shipping_lines'] as $ship) {
                                                    $total_shipping = $ship['price'];
                                                }
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
                                                <td><a class="btn-link"
                                                        href="{{ route('shopify.order.prepare', $order->order->order_number) }}">Lvs{{ $order->order->order_number }}</a>
                                                </td>
                                                @php
                                                    $shipping_address = $order->order['shipping_address'];

                                                @endphp
                                                <td>{{ $order->source_name ?? 'Online' }}
                                                </td>
                                                <td>
                                                    <p>{{ isset($shipping_address['name']) ? $shipping_address['name'] : '' }}
                                                        / </p>
                                                    <p>
                                                        {{ isset($shipping_address['phone']) ? $shipping_address['phone'] : '' }}
                                                    </p>
                                                </td>
                                                <td>{{ $order->channel ?? 'Online' }}</td>
                                                <td class="text-center">
                                                    {{ $order->order->financial_status == 'paid' ? 'Paid' : 'Cash' }}
                                                </td>
                                                <td class="text-center">{{ $order->order->subtotal_price }}</td>
                                                <td class="text-center">{{ $total_shipping }}</td>
                                                <td class="text-center">{{ $order->order->total_price - $returns }}</td>

                                                <td>{!! getOrderStatusBadge($order->delivery_status) !!}</td>
                                                <td>{{ $order->user->name }}</td>
                                                <td>{{ date('Y-m-d h:i:s', strtotime($order->order->created_at)) }}</td>
                                                <td class="text-right">
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endisset
                            </tbody>
                        </table>
                        <div class="text-center pb-2">
                            {{ $orders->links() }}
                        </div>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        $("ul#preparation").siblings('a').attr('aria-expanded', 'true');
        $("ul#preparation").addClass("show");
        $("#new").addClass("active");
    </script>
@endsection
