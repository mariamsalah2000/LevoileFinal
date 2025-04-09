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
                        <li class="breadcrumb-item">Review Pickup</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <form class="" action="{{ route('bulk-order-shipped') }}" id="sort_orders" method="post">
                        @csrf
                        
                        <div class="card-body">
                            <h4 class="card-title text-center">Orders Summary</h4>
                            <h5 style="color: red">Failed Orders</h5>
                            <div class="container mt-5">
                                <div class="mt-4">
                                    <table class="table table-bordered" id="orders_table">
                                        <thead style="background-color: rgb(210, 207, 207)">
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
                                                <th scope="col" class="text-center">Subtotal</th>
                                                <th scope="col" class="text-center">Shipping</th>
                                                <th scope="col" class="text-center">Total</th>
                                                <th scope="col">Customer Phone</th>
                                                <th scope="col" class="text-center">Delivery Status</th>

                                                <th scope="col">Created Date</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody id="orders_tbody">
                                            @foreach ($same_client as $client)
                                            @php
                                                $shipping_cost = isset($sale_data['shipping_lines'][0])?$sale_data['shipping_lines'][0]['price'] : 0;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="aiz-checkbox-inline">
                                                            <label class="aiz-checkbox">
                                                                <input type="checkbox" class="check-one" name="id[]" value="{{ $client->id }}">
                                                                <span class="aiz-square-check"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    Lvs{{ $client->order_number }}
                                                </td>
                                                <td>{{ $client['shipping_address']['name'] ?? '-' }}</td>
                                                <td>{{ $client->subtotal_price }}</td>
                                                <td>{{ $shipping_cost }}</td>
                                                <td>{{ $client->total_price }}</td>
                                                <td>{{ $client['shipping_address']['phone'] ?? '-' }}</td>
                                                <td>{{ $client->fulfillment_status }}</td>
                                                <td>{{ $client->created_at_date }}</td>
                                                
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 style="color:green">Success Orders</h5>
                            <div class="container mt-5">
                                <div class="mt-4">
                                    <table class="table table-bordered" id="orders_table">
                                        <thead style="background-color: rgb(210, 207, 207)">
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
                                                <th scope="col" class="text-center">Subtotal</th>
                                                <th scope="col" class="text-center">Shipping</th>
                                                <th scope="col" class="text-center">Total</th>
                                                <th scope="col">Customer Phone</th>
                                                <th scope="col" class="text-center">Delivery Status</th>

                                                <th scope="col">Created Date</th>
                                            </tr>
                                        </thead>
                                        <tbody id="orders_tbody">
                                            @foreach ($success_orders as $client)
                                            @php
                                                $shipping_cost = isset($sale_data['shipping_lines'][0])?$sale_data['shipping_lines'][0]['price'] : 0;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="aiz-checkbox-inline">
                                                            <label class="aiz-checkbox">
                                                                <input type="checkbox" class="check-one" name="id[]" value="{{ $client->id }}">
                                                                <span class="aiz-square-check"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    Lvs{{ $client->order_number }}
                                                </td>
                                                <td>{{ $client['shipping_address']['name'] ?? '' }}</td>
                                                <td>{{ $client->subtotal_price }}</td>
                                                <td>{{ $shipping_cost }}</td>
                                                <td>{{ $client->total_price }}</td>
                                                <td>{{ $client['shipping_address']['phone'] ?? '' }}</td>
                                                <td>{{ $client->fulfillment_status }}</td>
                                                <td>{{ $client->created_at_date }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between items-center p-4 m-2">
                            <button class="btn btn-primary" href="{{ redirect()->back() }}">Back</button>
                            <button class="btn btn-danger" type="submit">Submit</button>
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

    </script>
@endsection
