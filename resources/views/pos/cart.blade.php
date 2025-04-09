@if(session()->has('branch'))
@include('stock_requests.cart')
@else
<div class="aiz-pos-cart-list mb-4 mt-3 c-scrollbar-light">
    @php
        $subtotal = 0;
        $tax = 0;
    @endphp
    @if (Session::has('pos.cart'))
        <ul class="list-group list-group-flush">
            @forelse (Session::get('pos.cart') as $key => $cartItem)
                @php
                    $subtotal += $cartItem['price'] * $cartItem['quantity'];
                    $tax += $cartItem['tax'] * $cartItem['quantity'];
                    $stock = \App\Models\ProductVariant::where('sku', $cartItem['stock_id'])->first();
                @endphp
                <li class="list-group-item py-0 pl-2">
                    <div class="row gutters-5 align-items-center">
                        <div class="col-2">
                            <img src="{{ $cartItem['image'] }}" width="80px" height="80px">
                        </div>
                        <div class="col-2">
                            <div class="row no-gutters align-items-center flex-column aiz-plus-minus">
                                <button class="btn col-auto btn-icon btn-sm fs-15 add-pluss" type="button"
                                    data-type="plus" data-stock-id="{{ $cartItem['stock_id'] }}"
                                    data-field="qty-{{ $key }}">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <input type="text" name="qty-{{ $key }}" id="qty-{{ $key }}"
                                    class="col border-0 text-center flex-grow-1 fs-16 input-number" placeholder="1"
                                    value="{{ $cartItem['quantity'] }}" min="{{ 1 }}"
                                    max="{{ $stock->inventory_quantity }}"
                                    onchange="updateQuantity({{ $key }})">
                                <button class="btn col-auto btn-icon btn-sm fs-15 add-minus" type="button"
                                    data-type="minus" data-stock-id="{{ $cartItem['stock_id'] }}"
                                    data-field="qty-{{ $key }}">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="text-truncate-2">{{ $stock->product->title }}</div>
                            <span
                                class="span badge badge-inline fs-12 badge-soft-secondary">{{ $cartItem['variant'] }}</span>
                        </div>
                        <div class="col-2">
                            <div class="fs-12 opacity-60">{{ $cartItem['price'] }} x {{ $cartItem['quantity'] }}
                            </div>
                            <div class="fs-15 fw-600">{{ $cartItem['price'] * $cartItem['quantity'] }} LE</div>
                        </div>
                        <div class="col-1">
                            <button type="button" class="btn btn-circle btn-icon btn-sm btn-soft-danger ml-2 mr-0"
                                onclick="removeFromCart({{ $key }})">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </li>
            @empty
                <li class="list-group-item">
                    <div class="text-center">
                        <i class="las la-frown la-3x opacity-50"></i>
                        <p>{{ trans('No Product Added') }}</p>
                    </div>
                </li>
            @endforelse
        </ul>
    @else
        <div class="text-center">
            <i class="las la-frown la-3x opacity-50"></i>
            <p>{{ trans('No Product Added') }}</p>
        </div>
    @endif
</div>
<div class="row">
    <div class="col-6">
        <textarea name="shipping_note" class="form-control" placeholder="Add Shipping Note">{{ isset($order) ? $order->note : '' }}</textarea>
    </div>
    <div class="col-6">
        <textarea name="prepare_note" class="form-control" placeholder="Add Preparation Note">{{ isset($order) ? $order->prepare_note : '' }}</textarea>
    </div>
</div>
<div class="row m-2 justify-content-center">
    <div class="col-12">
        <input type="checkbox" name="paid" id="mark_as_paid"> Mark as Paid
    </div>
    <div class="col-md-12">
        <div class="form-group" id="payment-section">

        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <input type="text" id="couponCode" name="coupon_code" class="form-control" placeholder="Have a Coupon Code?">
    </div>
</div>
<hr>
<div>
    <div class="d-flex justify-content-between fw-600 mb-2 opacity-70">
        <span>{{ trans('Sub Total') }}</span>
        <span>{{ $subtotal }}</span>
    </div>
    <div class="d-flex justify-content-between fw-600 mb-2 opacity-70">
        <span>{{ trans('Tax') }}</span>
        <span>{{ $tax }}</span>
    </div>
    <div class="d-flex justify-content-between fw-600 mb-2 opacity-70">
        <span>{{ trans('Shipping') }}</span>
        <span>{{ Session::get('pos.shipping', 0) }}</span>
    </div>
    <div class="d-flex justify-content-between fw-600 mb-2 opacity-70">
        <span>{{ trans('Discount') }}</span>
        <span>{{ Session::get('pos.discount', 0) }}</span>
    </div>
    <div class="d-flex justify-content-between fw-600 fs-18 border-top pt-2">
        <span>{{ trans('Total') }}</span>
        <span>{{ $subtotal + $tax + Session::get('pos.shipping', 0) - Session::get('pos.discount', 0) }}</span>
    </div>
</div>
@endif
