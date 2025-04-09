@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <div class="row">
            <div class="col-12">
                <div class="col-9">
                    <h1>Confirm Return {{ $return->first()->return_number }}</h1>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('shopify.orders') }}">Orders</a></li>
                            <li class="breadcrumb-item">Return {{ $return->first()->return_number ?? '' }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="card">
                <form action="{{ route('returns.confirm.post') }}" method="POST" id="prepare_orders_store">
                    @csrf
                    <div class="card-body text-center">
                        <h5 class="card-title">Items</h5>
                        <input type="hidden" id="return_id" name="return_id" value="{{ $return[0]['id'] }}">
                        <input type="hidden" id="order_number" name="order_number"
                            value="{{ $return[0]['order_number'] }}">
                        @if ($details)
                            <div class="row">
                                @foreach ($details as $detail)
                                    <div class="col-md-3 mb-2">
                                        @php
                                            $item = $detail->product;
                                        @endphp
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
                                                <br>
                                                <strong>{{ $item['product_name'] }}</strong><br>
                                                <small><strong>Return Qty :</strong> {{ $detail['qty'] }}</small>
                                                <br>
                                                <small><strong>Variation : </strong>{{ $item['variation_id'] }}</small>
                                                <br>
                                                <small><strong>Return Amount :
                                                    </strong>{{ $detail['amount'] ?? '' }}</small><br>
                                                <small><strong>Sku : </strong>{{ $item['product_sku'] ?? '' }}</small><br>
                                                <small><strong>Return Reason :
                                                    </strong>{{ $detail['reason'] ?? '' }}</small><br>

                                            </div>

                                            <div>
                                                <input type="hidden" name="detail_id[]" value="{{ $detail->id }}">
                                                @if ($detail['status'] == 'received')
                                                    <span class="badge bg-success mb-1">Received</span>
                                                    <input name = "status[]" type="hidden" value="received">
                                                @elseif (in_array($detail['line_item_id'], $refunds))
                                                <span class="badge bg-dark">Removed</span>
                                                @else
                                                    <select name="status[]" class="form-select"
                                                        aria-label="Default select example" required>
                                                        <option value=" ">Choose Return Status ...
                                                        </option>
                                                        <option value="received">
                                                            Received</option>
                                                        <option value="not_received" @if ($item['status'] == 'not_received')  @endif>
                                                            Not Received</option>
                                                        <option value="wrong_item" @if ($item['status'] == 'wront_item')  @endif>
                                                            Wrong Item</option>
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
                            <label for="myCheckbox">
                                <input name="all" type="checkbox" class="form-check-input" value="all"
                                    id="return_all">
                                Receive All
                            </label>
                        </div>
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
