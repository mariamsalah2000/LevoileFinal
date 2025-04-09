@extends('layouts.app')
@section('css')
    <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
@endsection
@section('content')

    <div class="pagetitle">
        <div class="row">
            <div class="col-8">
                <h1>Staff Products Report</h1>
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
                                <div class="col-4 justify-content-center">

                                    <div class="container">
                                        <label for="daterange">Choose Date Range</label>
                                        <input type="text" value="{{ $daterange }}" id="daterange" name="daterange"
                                            class="form-control">
                                    </div>

                                </div>
                                @php
                                    $actions = ['NA', 'shortage', 'hold', 'prepared', 'reviewed'];
                                @endphp
                                <div class="col-sm-3 justify-content-center">
                                    <div class="">
                                        <label for="date">Filter By Action</label>
                                        <select class="form-select aiz-selectpicker" name="action" id="action">
                                            <option value="" @if ($action == '') selected @endif>Select
                                            </option>
                                            @foreach ($actions as $key => $value)
                                                <option value="{{ $value }}"
                                                    @if ($action == $value) selected @endif>
                                                    {{ ucfirst(str_replace('_', ' ', $value)) }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 justify-content-center">
                                    <div class="">
                                        <label for="date">Filter By Staff</label>
                                        <select class="form-select aiz-selectpicker" name="user_id" id="user_id">
                                            <option value="" @if ($user_id == '') selected @endif>Select
                                            </option>
                                            @foreach ($users as $key => $user)
                                                <option value="{{ $user->id }}"
                                                    @if ($user_id == $user->id) selected @endif>
                                                    {{ ucfirst($user->name) }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-auto justify-content-center">
                                    <div class="form-group mb-0 mt-4">
                                        <button type="submit" class="btn btn-primary">{{ 'Filter' }}</button>
                                    </div>
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
                            <h5 class="card-title">Staff Products Report</h5>

                            <!-- Table with stripped rows -->
                            <table class="table datatable" id="example">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">Product Name</th>
                                        <th scope="col" class="text-center">Order No</th>
                                        <th scope="col" class="text-center">Action</th>
                                        <th scope="col" class="text-center">Staff</th>
                                        <th scope="col" class="text-center">Note</th>
                                        <th scope="col" class="text-center">Price</th>
                                        <th scope="col" class="text-center">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($histories)
                                        @foreach ($histories as $key => $history)
                                            @if (!$history->product)
                                                @continue
                                            @endif
                                            @php
                                                $history_sum += $history->product->price;
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $history->product->product_name }}</td>
                                                <td class="text-center"><a class="btn-link"
                                                        href="{{ route('shopify.order.show', $history->order->table_id) }}">Lvs{{ $history->order->order_number }}</a>
                                                </td>
                                                <td class="text-center">{{ $history->action }}</td>
                                                <td class="text-center">{{ $history->user->name }}</td>
                                                <td class="text-center">{{ $history->note }}</td>
                                                <td class="text-center">{{ $history->product->price }}</td>
                                                <td class="text-center">
                                                    {{ date('Y-m-d h:i:s', strtotime($history->created_at)) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                                <tfoot>

                                    <tr>
                                        <th>Total</th>
                                        <th class="text-center">{{ $history_count }}</th>

                                        <th></th>
                                        <th>Price</th>
                                        <th>{{ $history_sum }} LE</th>
                                    </tr>


                                </tfoot>
                            </table>
                            <div class="text-center pb-2">
                                {{ $histories->links() }}
                            </div>
                            <!-- End Table with stripped rows -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script type='text/javascript'>
        $("ul#reports").siblings('a').attr('aria-expanded', 'true');
        $("ul#reports").addClass("show");
        $("#staff_products").addClass("active");
        $(document).ready(function() {
            $('#daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'MM/DD/YYYY'
                }
            });
        });
        $('#daterange').val('');
    </script>
@endsection
