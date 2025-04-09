@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <div class="row">
            <div class="col-12">
                <div class="col-9">
                    <h1>Confirm Partial Return on #{{ $order->order_number }}</h1>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('shopify.orders') }}">Orders</a></li>
                            <li class="breadcrumb-item">Return #{{ $order->order_number }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="card">
                <form action="{{ route('partial_return.confirm') }}" method="POST" id="prepare_orders_store">
                    @csrf
                    <div class="card-body text-center">
                        <h5 class="card-title">Items</h5>
                        <input type="hidden" id="order_number" name="order_number"
                            value="{{ $order->order_number }}">
                        @if ($prepare_products)
                            <div class="row">
                                @foreach ($prepare_products as $item)
                                    <div class="col-md-3 mb-2">
                                        @isset($item)
                                            <div>
                                                @isset($item)
                                                    @isset($item->variant_image)
                                                        <a href="#" data-bs-toggle="modal"
                                                            data-bs-target="#imagesmodal-{{ $item['product_id'] }}">
                                                            <img height="300" width="auto" src="{{ $item->variant_image }}"
                                                                alt="Image here">
                                                        </a>
                                                    @endisset
                                                @endisset
                                                <br>
                                            </div>
                                            <div>
                                                <strong>
                                                    <br>
                                                    {{ $item['product_name'] }}<br>
                                                    <small>Qty : {{ $item['order_qty'] }}</small>
                                                    <br>
                                                    <small>Variation : {{ $item['variation_id'] }}</small>
                                                    <br>
                                                    <small>Price : {{ $item['price'] ?? '' }}</small><br>
                                                    <small>Sku : {{ $item['product_sku'] ?? '' }}</small><br>
                                                </strong>
                                            </div>
                                            <div>
                                                
                                                @if(in_array($item['product_id'], $refunds))
                                                    <span class="badge bg-dark">Removed</span>
                                                @elseif ($return)
                                                    @if ($return && $return['status'] == 'Returned')
                                                        <span class="badge bg-secondary mb-1">Returned</span>
                                                    @else
                                                        <span class="badge bg-warning mb-1">Return In Progress</span>
                                                    @endif
                                                @else
                                                    <input type="hidden" name="prepare_product_id[]" value="{{ $item['id'] }}">
                                                    <input type="hidden" name="amount[]" value="{{ $item['price'] }}">

                                                    <input type="number" class="form-control" value="{{ $item['order_qty'] }}" name="qty[]" min="1" step="1" max="{{ $item['order_qty'] }}" placeholder="Enter Qty">
                                                    <br>
                                                    <select name="detail_id[]" class="form-select"
                                                        aria-label="Default select example">
                                                        <option value=" ">Choose Return Status ...
                                                        </option>
                                                        <option value="{{ $item['product_id'] }}">
                                                            Received</option>
                                                    </select>
                                                    @error('status.*')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                @endif
                                            </div>
                                        @endisset
                                    </div>
                                @endforeach

                            </div>
                        @endif
                        
                        <div class="form-group mt-4">
                            <input type="submit" value="Confirm Return" class="btn btn-primary">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $('form#prepare_orders_store').submit(function() {
                $(this).find(':input[type=submit]').prop('disabled', true);
            });
        });
    </script>
@endsection
