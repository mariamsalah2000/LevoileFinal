@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <div class="row">
            <div class="col-12">
                <div class="col-9">
                    <h1>Make a Call for Order {{ $order->name }}</h1>
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
                <form action="{{ route('shortage.call') }}" method="POST" id="make_call_post">
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
                        <div class="row m-2">
                            <div class="col-8">
                                <div class="card shadow-sm p-3">
                                    <div class="card-body">
                                        <h5 class="text-danger mb-3">
                                            <i class="bi bi-exclamation-circle-fill"></i> Trial Number:
                                            {{ $shortage->trial + 1 }}
                                        </h5>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <strong>Status:</strong>
                                                <span>{{ $shortage->status }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <strong>Response:</strong>
                                                <span>{{ $shortage->response }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <strong>Final Response:</strong>
                                                <span>{{ $shortage->final_response ?? '-' }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <strong>Reschedule Date:</strong>
                                                <span>{{ date('Y-m-d', strtotime($shortage->schedule_date)) }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <strong>Reschedule Time:</strong>
                                                <span>{{ $shortage->schedule_time }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <strong>Note:</strong>
                                                <span>{{ $shortage->note }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card shadow-sm p-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Customer Details</h5>
                                        <div class="alert alert-light" role="alert">
                                            <p>
                                                NAME : {{ $order['shipping_address']['name'] ?? '' }} <br>
                                                PHONE : {{ $order['shipping_address']['phone'] ?? '' }} <br>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 class="card-title">Items</h5>
                        <input type="hidden" id="order_id" name="order_id" value="{{ $shortage->id }}">
                        <input type="hidden" id="order_number" name="order_number" value="{{ $order['order_number'] }}">
                        @if ($prepare->products)
                            <div class="row">
                                @foreach ($prepare->products->sortByDesc('product_status') as $item)
                                    <div class="col-md-3 mb-2">
                                        <div>
                                            <strong
                                                style="color:red">{{ $item['product_status'] != 'prepared' && $item['product_status'] != 'unfulfilled' ? '(' . str_replace('_', ' ', ucfirst($item['product_status'])) . ')' : '-' }}</strong>
                                        </div>
                                        <div>
                                            @isset($item)
                                                @isset($item->variant_image)
                                                    <img height="300" width="auto" src="{{ $item->variant_image }}"
                                                        alt="Image here">
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

                                            @if (in_array($item['product_id'], $refunds))
                                                <span class="badge bg-dark">Removed</span>
                                                <input name = "product_status[]" type="hidden" value="prepared">
                                            @elseif (in_array($item['product_id'], $returns))
                                                <span class="badge bg-secondary">Returned</span>
                                                <input name = "product_status[]" type="hidden" value="prepared">
                                            @elseif ($item['product_status'] == 'prepared')
                                                <span class="badge bg-success mb-1">Prepared</span>
                                                <input name = "product_status[]" type="hidden" value="prepared">
                                            @endif

                                            <input type="hidden" name="line_item_id[]" value="{{ $item['product_id'] }}">
                                            <input type="hidden" name="qty[]" value="{{ $item['order_qty'] }}">
                                            <input type="hidden" name="prepare_product_id[]" value="{{ $item['id'] }}">
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                            <div class="row justify-content-end">
                                <div class="col-md-12">
                                    <div class="form-group mt-4">
                                        <label for="call_response">Customer Response:</label>
                                        <select class="form-select" id="call_response" name="response" onchange="respond()"
                                            required>
                                            <option value="">Choose Response</option>
                                            <option value="answered">Answered</option>
                                            <option value="no_answer">No Answer</option>
                                            <option value="phone_off">Phone Off</option>
                                            <option value="wrong_number">Wrong Number</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-end" id="reschedule_call" style="display: none">
                                <div class="col-md-6 justify-content-center">
                                    <div class="form-group mt-4">
                                        <label for="note">Reschedule Date</label>
                                        <input type="date" class="form-control" name="reschedule_date">
                                    </div>
                                    <div class="form-group mt-4">
                                        <label for="note">Reschedule Time</label>
                                        <input type="time" class="form-control" name="reschedule_time">
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-end" id="answered" style="display: none">
                                <div class="col-md-6 justify-content-center">


                                    <div class="form-group mt-4">
                                        <label for="note">Customer Answer</label>
                                        <select class="form-select" name="final_response">
                                            <option value="Send as it is">Send as it is - إرسال الاوردر كما هو</option>
                                            <option value="Send and Replace">Send and Replace - استبدال المنتج وإرسال
                                                الاوردر</option>
                                            <option value="Send and Add">Send and Add - إضافه منتج وإرسال الاوردر</option>
                                            <option value="Customer will Reply Later">Customer will Reply Later - سوف يرد
                                                بعد قليل</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-end" id="submit_box" style="display: none">
                                <div class="col-md-6 justify-content-center">

                                    <div class="form-group mt-4">
                                        <label for="note">Note</label>
                                        <textarea name="note" class="form-control" placeholder="Add Note" required></textarea>
                                    </div>
                                    <div class="form-group mt-4">
                                        <input type="submit" id="post_call_button" value="Submit"
                                            class="btn btn-danger">
                                    </div>
                                </div>
                            </div>
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



        $("#post_call_button").click(function() {
            document.getElementById('post_call_button').disabled = true;
            $("#make_call_post").submit();
        });
    </script>
@endsection
