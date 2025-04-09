@extends('layouts.app')
@section('css')
    <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
    <style>
        .shadow2 {
            -moz-box-shadow: 3px 3px 5px 6px #ccc;
            -webkit-box-shadow: 3px 3px 5px 6px #ccc;
            box-shadow: 3px 3px 5px 6px #ccc;
            border-radius: 4%;
            /*supported by all latest Browser*/
            -moz-border-radius: 4%;
            /*For older Browser*/
            -webkit-border-radius: 4%;
            /*For older Browser*/

            width: 180px;
            height: 75px;
        }

        .custom-dropdown {
            width: 200px;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f8f9fa;
            color: #333;
            cursor: pointer;
        }

        .custom-dropdown:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .custom-dropdown:hover {
            border-color: #0056b3;
        }

        .custom-dropdown:active {
            border-color: #004085;
            background-color: #e2e6ea;
        }
    </style>
@endsection
@section('content')

    <div class="pagetitle">
        <div class="row">
            <div class="col-8">
                <h1>Hold Products</h1>
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
                                <div class="col-10 justify-content-center">

                                    <div class="shadow2 justify-content-center text-center"
                                        style="background-color: rgb(166, 166, 164); color:white">
                                        <strong>Hold Orders</strong>
                                        <br>
                                        {{ $orders }}
                                    </div>

                                </div>
                                <div class="col-2 justify-content-center">

                                    <div class="shadow2 justify-content-center text-center"
                                        style="background-color: rgb(166, 166, 164); color:white">
                                        <strong>Hold Products</strong>
                                        <br>
                                        {{ $products_all->count() }}
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card-header row gutters-5">
                            <div class="row col-12">

                                <div class="col-2"></div>
                                @php
                                    $status = ['NA', 'shortage', 'hold'];
                                @endphp
                                @foreach ($status as $key => $stat)
                                    <div class="col-2 justify-content-center">

                                        <div class="shadow2 justify-content-center text-center"
                                            style="background-color: rgb(166, 166, 164); color:white">
                                            <strong>{{ ucfirst($stat) }} Products</strong>
                                            <br>
                                            @if ($stat == 'hold')
                                                {{ $products_all->whereNotIn('product_status', ['NA', 'shortage', 'prepared'])->count() }}
                                            @else
                                                {{ $products_all->where('product_status', $stat)->count() }}
                                            @endif
                                        </div>

                                    </div>
                                @endforeach
                                <div class="col-2"></div>

                            </div>
                        </div>
                        <div class="card-header row gutters-5">
                            <div class="row col-12">

                                <div class="col-sm-3">
                                    <div class="form-group mb-0">
                                        <label for="date">Filter By Order Date</label>
                                        <input type="date" class="custom-dropdown" value="{{ $date }}"
                                            name="date" placeholder="{{ 'Filter by order date' }}" data-format="DD-MM-Y"
                                            data-separator=" to " data-advanced-range="true" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group mb-0">
                                        <label for="date">Filter By Hold Date</label>
                                        <input type="date" class="custom-dropdown" value="{{ $hold_date }}"
                                            name="hold_date" placeholder="{{ 'Filter by hold date' }}"
                                            data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="">
                                        <label for="date">Filter By Product Status</label>
                                        <select class="custom-dropdown aiz-selectpicker" name="delivery_status"
                                            id="delivery_status">
                                            <option value="">Select</option>
                                            <option value="hold" @if ($delivery_status == 'hold') selected @endif>Hold
                                            </option>
                                            <option value="NA" @if ($delivery_status == 'NA') selected @endif>N/A
                                            </option>

                                            <option value="shortage" @if ($delivery_status == 'shortage') selected @endif>
                                                Shortage</option>
                                            @foreach ($statuses as $key => $status)
                                                <option value="{{ $status }}"
                                                    @if ($delivery_status == $status) selected @endif>
                                                    {{ str_replace('_', ' ', ucfirst($status)) }} </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group mb-0">
                                        <label for="search">Search Product</label>
                                        <input type="text" class="custom-dropdown" value="{{ $search }}"
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
                            <h5 class="card-title">Hold Products</h5>

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
                                        <th scope="col" class="text-center">Product Image</th>
                                        <th scope="col" class="text-center">Product Name</th>
                                        <th scope="col" class="text-center">Product Status</th>
                                        <th scope="col" class="text-center">Assigned To</th>
                                        <th scope="col">Created Date</th>
                                        <th scope="col">Duration</th>
                                        <th scope="col">Change Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($products)
                                        @foreach ($products as $key => $product)
                                            @php

                                                //$end = Carbon\Carbon::createFromFormat('Y-m-d', $product->updated_at);

                                                $start = Carbon\Carbon::parse($product->created_at);
                                                $end = now();
                                                $options = [
                                                    'join' => ', ',
                                                    'parts' => 2,
                                                    'syntax' => Carbon\CarbonInterface::DIFF_ABSOLUTE,
                                                ];

                                                $duration = $end->diffForHumans($start, $options);
                                                //
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="aiz-checkbox-inline">
                                                            <label class="aiz-checkbox">
                                                                <input type="checkbox" class="check-one" name="id[]"
                                                                    value="{{ $product->id }}">
                                                                <span class="aiz-square-check"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><a class="btn-link"
                                                        href="{{ route('shopify.order.prepare', str_replace('#', '', $product->order->name)) }}">{{ $product->order->name }}</a>
                                                </td>
                                                <td class="text-center">
                                                    <img height="200" width="180"
                                                        src="{{ $product->variant_image ?? null }}" alt="Image here">
                                                </td>
                                                <td>
                                                    Name : {{ $product->product_name }}
                                                    <br>
                                                    Price : {{ $product->price }}
                                                    <br>
                                                    Qty : {{ $product->order_qty }}
                                                    <br>
                                                    Variant : {{ $product->variation_id }}
                                                    <br>
                                                    SKU : {{ $product->product_sku }}
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge badge-inline badge-danger">{{ str_replace('_', ' ', ucfirst($product->product_status)) }}</span>
                                                </td>
                                                <td class="text-center">{{ $product->prepare->user->name }}</td>
                                                <td>{{ Carbon\Carbon::parse($product->created_at) }}</td>
                                                </td>

                                                <td>{{ $duration }}
                                                </td>
                                                @php
                                                    $variant = \App\Models\ProductVariant::where(
                                                        'sku',
                                                        $product->product_sku,
                                                    )->first();
                                                    $variant_branches = [];
                                                    if ($variant) {
                                                        $variant_branches = $branches->where(
                                                            'variant_id',
                                                            $variant->id,
                                                        );
                                                    }

                                                @endphp
                                                <td>

                                                    @if (count($variant_branches) == 0)
                                                        <select name="product_status[{{ $product->id }}]"
                                                            class="custom-dropdown" aria-label="Default select example">

                                                            <option value="NA">N/A</option>
                                                            <option value="shortage">Shortage</option>

                                                        </select>
                                                    @else
                                                        @php
                                                            $has_qty = $variant_branches->where('qty', '>', 0);
                                                        @endphp
                                                        @if (count($has_qty) == 0)
                                                            <select name="product_status[{{ $product->id }}]" disabled
                                                                class="custom-dropdown" aria-label="Default select example">

                                                                <option value="shortage">Shortage</option>

                                                            </select>
                                                        @else
                                                            <select name="product_status[{{ $product->id }}]"
                                                                class="custom-dropdown" aria-label="Default select example">
                                                                <option value="">Choose Status ... </option>
                                                                <option value="shortage">Shortage</option>
                                                                @foreach ($has_qty as $key => $branch)
                                                                    <option value="{{ $branch->name }}">
                                                                        {{ str_replace('_', ' ', ucfirst($branch->name)) . ': ' . $branch->qty . ' Pcs' }}
                                                                    </option>
                                                                @endforeach

                                                            </select>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (count($variant_branches) == 0)
                                                        <a class="btn btn-success"
                                                            href="{{ route('na.review', $product->id) }}" title="Reviewed">
                                                            <i class="bi bi-check-lg"></i>
                                                        </a>
                                                        Review
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                                <tfoot>

                                    <tr>
                                        <th class="text-center">{{ $products_count }}</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>
                                            <button type="submit" name="button" value="update"
                                                class="btn btn-danger">{{ 'Submit' }}</butt>
                                        </th>
                                    </tr>


                                </tfoot>
                            </table>
                            <div class="text-center pb-2">
                                {{ $products->links() }}
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
    <script type="text/javascript">
        $("ul#reports").siblings('a').attr('aria-expanded', 'true');
        $("ul#reports").addClass("show");
        $("#hold_products").addClass("active");
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
