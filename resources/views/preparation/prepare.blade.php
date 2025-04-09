

@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <div class="row">
            <div class="col-12">
                <div class="col-9">
                    <h1>Order {{ $order->name }}</h1>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('shopify.orders') }}">Orders</a></li>
                            <li class="breadcrumb-item">Order {{ $order->name ?? '' }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="card">
                <form action="{{ route('shopify.order.fulfillitems') }}" method="POST" id="prepare_orders_store">
                    @csrf
                    <div class="card-body text-center">
                        @if ($prepare->order->prepare_note)
                            <br>
                            <hr>
                            <h6 class="text-center" style="color:red">Order Prepare Note :
                                {{ $prepare->order->prepare_note }}</h4>
                                <br>
                                <hr>
                        @endif
                        <h5 class="card-title">Items</h5>
                        <input type="hidden" id="order_id" name="order_id" value="{{ $order['table_id'] }}">
                        <input type="hidden" id="order_number" name="order_number" value="{{ $order['order_number'] }}">
                        @if ($prepare->products)
                            <div class="row">
                                @foreach ($prepare->products as $item)
                                    @if (in_array($item['product_id'], $refunds->pluck('line_item_id')->toArray()))
                                        @php
                                        $refund = $refunds->where('line_item_id',$item['product_id'])->first();
                                        @endphp
                                        @if($refund->quantity == $item->order_qty)
                                        <div class="col-md-3 mb-2">
                                            <div>
                                                <strong
                                                    style="color:red">{{ $item['product_status'] != 'prepared' && $item['product_status'] != 'unfulfilled' ? '(' . str_replace('_', ' ', ucfirst($item['product_status'])) . ')' : '-' }}</strong>
                                            </div>
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
                                                    <span class="badge bg-dark">Removed</span>
                                                    <input name = "product_status[]" type="hidden" value="prepared">
                                                

                                                <input type="hidden" name="line_item_id[]" value="{{ $item['product_id'] }}">
                                                <input type="hidden" name="qty[]" value="{{ $item['order_qty'] }}">
                                                <input type="hidden" name="prepare_product_id[]" value="{{ $item['id'] }}">
                                            </div>
                                            
                                        </div>
                                        @else
                                        <div class="col-md-3 mb-2">
                                            <div>
                                                <strong
                                                    style="color:red">{{ $item['product_status'] != 'prepared' && $item['product_status'] != 'unfulfilled' ? '(' . str_replace('_', ' ', ucfirst($item['product_status'])) . ')' : '-' }}</strong>
                                            </div>
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
                                                    <small>Qty : {{ $refund->quantity}}</small>
                                                    <br>
                                                    <small>Variation : {{ $item['variation_id'] }}</small>
                                                    <br>
                                                    <small>Price : {{ $item['price'] ?? '' }}</small><br>
                                                    <small>Sku : {{ $item['product_sku'] ?? '' }}</small><br>
                                                </strong>
                                            </div>

                                            <div>
                                                    <span class="badge bg-dark">Removed</span>
                                                    <input name = "product_status[]" type="hidden" value="prepared">
                                                

                                                <input type="hidden" name="line_item_id[]" value="{{ $item['product_id'] }}">
                                                <input type="hidden" name="qty[]" value="{{ $refund->quantity}}">
                                                <input type="hidden" name="prepare_product_id[]" value="{{ $item['id'] }}">
                                            </div>
                                            
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <div>
                                                <strong
                                                    style="color:red">{{ $item['product_status'] != 'prepared' && $item['product_status'] != 'unfulfilled' ? '(' . str_replace('_', ' ', ucfirst($item['product_status'])) . ')' : '-' }}</strong>
                                            </div>
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
                                                    <small>Qty : {{ $item['order_qty'] - $refund->quantity }}</small>
                                                    <br>
                                                    <small>Variation : {{ $item['variation_id'] }}</small>
                                                    <br>
                                                    <small>Price : {{ $item['price'] ?? '' }}</small><br>
                                                    <small>Sku : {{ $item['product_sku'] ?? '' }}</small><br>
                                                </strong>
                                            </div>

                                            <div>

                                                @php
                                                    $return = $returns->where('line_item_id', $item['product_id'])->first();
                                                @endphp
                                                @if ($return)
                                                    @if ($return && $return['status'] == 'Returned')
                                                        <span class="badge bg-secondary mb-1">Returned</span>
                                                    @else
                                                        <span class="badge bg-warning mb-1">Return In Progress</span>
                                                    @endif
                                                    <input name = "product_status[]" type="hidden" value="prepared">
                                                @elseif ($item['product_status'] == 'prepared')
                                                    <span class="badge bg-success mb-1">Prepared</span>
                                                    <input name = "product_status[]" type="hidden" value="prepared">
                                                    @if ($order->fulfillment_status == 'shipped')
                                                        <br>
                                                        <select name="returns[]" id="return_order{{ $item['product_id'] }}"
                                                            class="form-select" onchange="returnn({{ $item['product_id'] }})">
                                                            <option value="">Options ...</option>
                                                            <option value="{{ $item['product_id'] }}">Return</option>
                                                        </select>
                                                    @endif
                                                @else
                                                    <select name="product_status[]" class="form-select"
                                                        aria-label="Default select example" required>
                                                        <option value=" ">Choose Status ...
                                                        </option>
                                                        <option value="prepared"
                                                            @if ($item['product_status'] == 'prepared') selected @endif>
                                                            Prepared</option>
                                                        <option value="hold"
                                                            @if ($item['product_status'] != 'prepared' && $item['product_status'] != 'unfulfilled') selected @endif>
                                                            Hold</option>
                                                    </select>
                                                    @error('product_status.*')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                @endif

                                                <input type="hidden" name="line_item_id[]" value="{{ $item['product_id'] }}">
                                                <input type="hidden" name="qty[]" value="{{ $item['order_qty'] - $refund->quantity }}">
                                                <input type="hidden" name="prepare_product_id[]" value="{{ $item['id'] }}">
                                            </div>
                                            @if($order->fulfillment_status == 'shipped')
                                            <div id="{{ $item['product_id'] }}" style="display:none">
                                                <label for="return_qty[]">Return Qty</label>
                                                <input type="number" class="form-control" name="return_qty[]"
                                                    placeholder="Enter Qty" max="{{ $item['order_qty'] - $refund->quantity }}" min="1"
                                                    oninput="validity.valid || (value = {{ $item['order_qty'] - $refund->quantity }});"
                                                    value="{{ $item['order_qty'] - $refund->quantity  }}" disabled>
                                                @error('qty.*')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <input type="hidden" name="items[]" id="items{{ $item['product_id'] }}">
                                                <label for="reason[]">Return Reason</label>
                                                <select class="form-select aiz-selectpicker" name="reason[]"
                                                    data-minimum-results-for-search="Infinity">
                                                    <option value="">Select</option>
                                                    <option value="UNKNOWN">Unknown</option>
                                                    <option value="SIZE_TOO_SMALL">Size was too small</option>
                                                    <option value="SIZE_TOO_LARGE">Size was too large</option>
                                                    <option value="UNWANTED" selected>Customer changed their mind</option>
                                                    <option value="NOT_AS_DESCRIBED">Item not as described</option>
                                                    <option value="WRONG_ITEM">Received the wrong item</option>
                                                    <option value="DEFECTIVE">Damaged or defective</option>
                                                    <option value="Color">Color</option>
                                                    <option value="OTHER">Other</option>

                                                </select>
                                                @error('reason.*')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                <label for="amount[]">Amount</label>
                                                <input type="number" name="amount[]" class="form-control"
                                                    max="{{ $item['price'] }}" min="0"
                                                    oninput="validity.valid || (value = {{ $item['price'] }});"
                                                    value="{{ $item['price'] }}" >
                                                @error('amount.*')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror

                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                    @else
                                    <div class="col-md-3 mb-2">
                                        <div>
                                            <strong
                                                style="color:red">{{ $item['product_status'] != 'prepared' && $item['product_status'] != 'unfulfilled' ? '(' . str_replace('_', ' ', ucfirst($item['product_status'])) . ')' : '-' }}</strong>
                                        </div>
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

                                            @php
                                                $return = $returns->where('line_item_id', $item['product_id'])->first();
                                            @endphp
                                            @if ($return)
                                                @if ($return && $return['status'] == 'Returned')
                                                    <span class="badge bg-secondary mb-1">Returned</span>
                                                @else
                                                    <span class="badge bg-warning mb-1">Return In Progress</span>
                                                @endif
                                                <input name = "product_status[]" type="hidden" value="prepared">
                                            @elseif ($item['product_status'] == 'prepared')
                                                <span class="badge bg-success mb-1">Prepared</span>
                                                <input name = "product_status[]" type="hidden" value="prepared">
                                                @if ($order->fulfillment_status == 'shipped')
                                                    <br>
                                                    <select name="returns[]" id="return_order{{ $item['product_id'] }}"
                                                        class="form-select" onchange="returnn({{ $item['product_id'] }})">
                                                        <option value="">Options ...</option>
                                                        <option value="{{ $item['product_id'] }}">Return</option>
                                                    </select>
                                                @endif
                                            @else
                                                <select name="product_status[]" class="form-select"
                                                    aria-label="Default select example" required>
                                                    <option value=" ">Choose Status ...
                                                    </option>
                                                    <option value="prepared"
                                                        @if ($item['product_status'] == 'prepared') selected @endif>
                                                        Prepared</option>
                                                    <option value="hold"
                                                        @if ($item['product_status'] != 'prepared' && $item['product_status'] != 'unfulfilled') selected @endif>
                                                        Hold</option>
                                                </select>
                                                @error('product_status.*')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            @endif

                                            <input type="hidden" name="line_item_id[]" value="{{ $item['product_id'] }}">
                                            <input type="hidden" name="qty[]" value="{{ $item['order_qty'] }}">
                                            <input type="hidden" name="prepare_product_id[]" value="{{ $item['id'] }}">
                                        </div>
                                        @if($order->fulfillment_status == 'shipped')
                                        <div id="{{ $item['product_id'] }}" style="display:none">
                                            <label for="return_qty[]">Return Qty</label>
                                            <input type="number" class="form-control" name="return_qty[]"
                                                placeholder="Enter Qty" max="{{ $item['order_qty'] }}" min="1"
                                                oninput="validity.valid || (value = {{ $item['order_qty'] }});"
                                                value="{{ $item['order_qty'] }}">
                                            @error('qty.*')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <input type="hidden" name="items[]" id="items{{ $item['product_id'] }}">
                                            <label for="reason[]">Return Reason</label>
                                            <select class="form-select aiz-selectpicker" name="reason[]"
                                                data-minimum-results-for-search="Infinity">
                                                <option value="">Select</option>
                                                <option value="UNKNOWN">Unknown</option>
                                                <option value="SIZE_TOO_SMALL">Size was too small</option>
                                                <option value="SIZE_TOO_LARGE">Size was too large</option>
                                                <option value="UNWANTED" selected>Customer changed their mind</option>
                                                <option value="NOT_AS_DESCRIBED">Item not as described</option>
                                                <option value="WRONG_ITEM">Received the wrong item</option>
                                                <option value="DEFECTIVE">Damaged or defective</option>
                                                <option value="Color">Color</option>
                                                <option value="OTHER">Other</option>

                                            </select>
                                            @error('reason.*')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <label for="amount[]">Amount</label>
                                            <input type="number" name="amount[]" class="form-control"
                                                max="{{ $item['price'] }}" min="0"
                                                oninput="validity.valid || (value = {{ $item['price'] }});"
                                                value="{{ $item['price'] }}" >
                                            @error('amount.*')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror

                                        </div>
                                        @endif
                                    </div>
                                       
                                    @endif
                                @endforeach

                            </div>
                            <hr>
                            @if ($prepare->delivery_status == 'distributed' || $prepare->delivery_status == 'hold')
                                <div class="row justify-content-end">
                                    <div class="col-md-12">
                                        <div class="form-group mt-4">
                                            <input type="submit" value="Save Order" class="btn btn-primary">
                                        </div>
                                    </div>
                                </div>
                            @elseif($order->fulfillment_status == 'shipped')
                                <div class="row justify-content-center m-2" id="return_order_submit"
                                    style="display: none">
                                    <div class="col-md-6 justify-content-center">
                                        <div class="form-group mt-4">
                                            <label for="shipping_on">Shipping On</label>
                                            <select class="form-select aiz-selectpicker" name="shipping_on"
                                                data-minimum-results-for-search="Infinity" required>
                                                <option value="client"selected>Client</option>
                                                <option value="levoile">Levoile</option>

                                            </select>
                                        </div>
                                        <div class="form-group mt-4">
                                            <label for="note">Note</label>
                                            <textarea name="note" class="form-control" placeholder="Add Return Note"></textarea>
                                        </div>
                                        <div class="form-group mt-4">
                                            <label for="myCheckbox">
                                                <input name="all" type="checkbox" class="form-check-input"
                                                    value="all" id="return_all">
                                                Return All
                                            </label>
                                        </div>
                                        <div class="form-group mt-4">
                                            <input type="button" id="return_button" value="Confirm Return"
                                                class="btn btn-danger">
                                        </div>
                                    </div>
                                </div>
                                @if (count($prepare->products) > count($returns))
                                    <div class="row justify-content-center m-2" id="return_all_submit">
                                        <div class="col-3">
                                            <button onclick="return_order(1)" type="button"
                                                class="btn btn-danger">Return
                                                All</button>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endif
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



        $("#return_button").click(function() {
            return_order();
        });

        function return_order(all = 0) {
            console.log("hi");
            $("return_button").prop('disabled',true);
            $("return_all_submit").prop('disabled',true);
            document.getElementById('return_button').innerHTML = "Processing";
            document.getElementById('return_all_submit').innerHTML = "Processing";
            if (all == 1) {
                document.getElementById("return_all").checked = true;
            }
            var data = new FormData($('#prepare_orders_store')[0]);
            var selected_name = $(this).find("option:selected").text();
            var selected_user = this.value;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('orders.return') }}",
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


            console.log(selected_name);
            console.log(selected_user);
            console.log(data);
        }
    </script>
@endsection

