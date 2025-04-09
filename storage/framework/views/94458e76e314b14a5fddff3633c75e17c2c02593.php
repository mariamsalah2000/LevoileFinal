
<?php $__env->startSection('title'); ?>
    <title><?php echo e('Branch Stock Request'); ?></title>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php if(session()->has('phone_number')): ?>
        <div class="alert alert-danger alert-dismissible text-center">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button><?php echo e(session()->first('phone_number')); ?>

        </div>
    <?php endif; ?>
    <?php if(session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button><?php echo session()->get('success'); ?></div>
    <?php endif; ?>
    <?php if(session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close"
                data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button><?php echo e(session()->get('error')); ?></div>
    <?php endif; ?>

    <style>
        .aiz-pos-product-list {
            overflow-y: auto;
            max-height: calc(100vh - 220px);
            height: calc(100vh - 220px);
            overflow-x: hidden;
        }

        .c-scrollbar-light,
        .uppy-Dashboard-files,
        .bootstrap-select .dropdown-menu .inner {
            scrollbar-color: rgba(24, 28, 41, 0.08);
            scrollbar-width: thin;
        }

        .aiz-pos-cart-list {
            overflow-y: auto;
            max-height: calc(100vh - 490px);
            height: calc(100vh - 490px);
            overflow-x: hidden;
        }

        .absolute-full {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
        }

        .hov-overlay .overlay,
        .hov-container .hov-box {
            visibility: hidden;
            opacity: 0;
            -webkit-transition: visibility 0.3s ease, opacity 0.3s ease;
            transition: visibility 0.3s ease, opacity 0.3s ease;
        }
    </style>
    <section class="forms pos-section">
        <div class="container-fluid">
            <div class="row gutters-5">

                <!-- product list -->
                <div class="col-md-8">
                    <!-- navbar-->
                    <header class="header">
                        <nav class="navbar">
                            <div class="container-fluid">
                                <div class="navbar-holder d-flex align-items-center justify-content-between">

                                    <div class="navbar-header">

                                        <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                                            <li class="nav-item"><a id="btnFullscreen" title="Full Screen"><i
                                                        class="fa fa-expand"></i></a></li>
                                            <li class="nav-item">
                                                <a href="<?php echo e(route('stock_requests.index')); ?>" id="today-sale-btn"
                                                    title="Sales"><i class="fa fa-shop"></i></a>
                                            </li>
                                            <li class="nav-item">
                                                <a rel="nofollow" data-target="#" href="#" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false"
                                                    class="nav-link dropdown-item"><i class="fa fa-user"></i>
                                                    <span><?php echo e(ucfirst(Auth::user()->name)); ?></span> <i
                                                        class="fa fa-angle-down"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                        </nav>
                    </header>

                    <div class="row" style="height: 100px; display:flex">
                        <div class="col-lg-12">
                            <input class="form-control form-control-lg" type="text" name="keyword"
                                placeholder="<?php echo e(trans('Search by Product Name/Barcode')); ?>" onkeyup="filterProducts()">

                        </div>
                    </div>

                    <div class="aiz-pos-product-list c-scrollbar-light">
                        <div class="d-flex flex-wrap justify-content-center">
                            <div class="row"id="product-list">

                            </div>

                        </div>
                        <div id="load-more" class="text-center">
                            <div class="fs-14 d-inline-block fw-600 btn btn-soft-primary c-pointer"
                                onclick="loadMoreProduct()"><?php echo e(trans('Loading..')); ?></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 w-md-350px w-lg-400px w-xl-500px">
                    <div class="card mb-3">
                        <div class="card-body">
                            <?php echo Form::open([
                                'route' => 'stock_requests.store',
                                'method' => 'post',
                                'files' => true,
                                'class' => 'payment-form',
                            ]); ?>

                            <?php echo csrf_field(); ?>

                            <input type="hidden" name="branch_id" value="<?php echo e($branch->id); ?>">
                            <div class="" id="cart-details">
                                <div class="aiz-pos-cart-list mb-4 mt-3 c-scrollbar-light">
                                    <?php
                                        $subtotal = 0;
                                        $tax = 0;

                                        Session::forget('pos.cart');

                                    ?>
                                    <?php if(Session::has('pos.cart')): ?>
                                        <ul class="list-group list-group-flush">
                                            <?php $__empty_1 = true; $__currentLoopData = Session::get('pos.cart'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cartItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <?php
                                                    $subtotal += $cartItem['price'] * $cartItem['quantity'];
                                                    $tax += $cartItem['tax'] * $cartItem['quantity'];
                                                    $stock = \App\Models\ProductStock::find($cartItem['stock_id']);

                                                ?>
                                                <li class="list-group-item py-0 pl-2">
                                                    <div class="row gutters-5 align-items-center">
                                                        <div class="col-auto w-60px">
                                                            <div
                                                                class="row no-gutters align-items-center flex-column aiz-plus-minus">
                                                                <button class="btn col-auto btn-icon btn-sm fs-15"
                                                                    type="button" data-type="plus"
                                                                    data-field="qty-<?php echo e($key); ?>">
                                                                    <i class="las la-plus"></i>
                                                                </button>
                                                                <input type="text" name="qty-<?php echo e($key); ?>"
                                                                    id="qty-<?php echo e($key); ?>"
                                                                    class="col border-0 text-center flex-grow-1 fs-16 input-number"
                                                                    placeholder="1" value="<?php echo e($cartItem['quantity']); ?>"
                                                                    min="<?php echo e($stock->product->min_qty); ?>"
                                                                    max="<?php echo e($stock->qty); ?>"
                                                                    onchange="updateQuantity(<?php echo e($key); ?>)">
                                                                <button class="btn col-auto btn-icon btn-sm fs-15"
                                                                    type="button" data-type="minus"
                                                                    data-field="qty-<?php echo e($key); ?>">
                                                                    <i class="las la-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="text-truncate-2">
                                                                <?php echo e($stock->product->name); ?></div>
                                                            <span
                                                                class="span badge badge-inline fs-12 badge-soft-secondary"><?php echo e($cartItem['variant']); ?></span>
                                                        </div>
                                                        <div class="col-auto">
                                                            <div class="fs-12 opacity-60">
                                                                <?php echo e($cartItem['price']); ?> x
                                                                <?php echo e($cartItem['quantity']); ?></div>
                                                            <div class="fs-15 fw-600">
                                                                <?php echo e($cartItem['price'] * $cartItem['quantity']); ?>

                                                            </div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <button type="button"
                                                                class="btn btn-circle btn-icon btn-sm btn-soft-danger ml-2 mr-0"
                                                                onclick="removeFromCart(<?php echo e($key); ?>)">
                                                                <i class="las la-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <li class="list-group-item">
                                                    <div class="text-center">
                                                        <i class="las la-frown la-3x opacity-50"></i>
                                                        <p><?php echo e(trans('No Product Added')); ?></p>
                                                    </div>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    <?php else: ?>
                                        <div class="text-center">
                                            <i class="las la-frown la-3x opacity-50"></i>
                                            <p><?php echo e(trans('No Product Added')); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <textarea name="note" class="form-control" placeholder="Add Note"></textarea>
                                    </div>
                                </div>


                                <hr>
                                
                            </div>
                        </div>
                        <div class="my-2 my-md-0">
                            <button type="button" type="button" class="btn btn-primary btn-block payment-btn"
                                id="cash-btn"><?php echo e(trans('Confirm Request')); ?>

                            </button>
                        </div>
                    </div>
                </div>
                

                <!-- shipping_cost modal -->
                <div id="shipping-cost-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" class="modal fade text-left">
                    <div role="document" class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><?php echo e(trans('Shipping Cost')); ?></h5>
                                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                        aria-hidden="true"><i class="fa fa-cross"></i></span></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="text" name="shipping_cost" class="form-control numkey"
                                        step="any">
                                </div>
                                <button type="button" name="shipping_cost_btn" class="btn btn-primary"
                                    data-dismiss="modal"><?php echo e(trans('submit')); ?></button>
                            </div>
                        </div>
                    </div>
                </div>

                <?php echo Form::close(); ?>

                <!-- product edit modal -->
                <div id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" class="modal fade text-left">
                    <div role="document" class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 id="modal_header" class="modal-title"></h5>
                                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                        aria-hidden="true"><i class="fa fa-cross"></i></span></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <label><?php echo e(trans('Quantity')); ?></label>
                                        <input type="text" name="edit_qty" class="form-control numkey">
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo e(trans('Unit Discount')); ?></label>
                                        <input type="text" name="edit_discount" class="form-control numkey">
                                    </div>
                                    <div class="form-group" style="display: none!important;">
                                        <label><?php echo e(trans('Unit Price')); ?></label>
                                        <input type="text" name="edit_unit_price" class="form-control numkey"
                                            step="any">
                                    </div>
                                    <div id="edit_unit" class="form-group" style="display: none!important;">
                                        <label><?php echo e(trans('Product Unit')); ?></label>
                                        <select name="edit_unit" class="form-select selectpicker">
                                        </select>
                                    </div>
                                    <button type="button" name="update_btn"
                                        class="btn btn-primary"><?php echo e(trans('update')); ?></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- add customer modal -->
                <div id="addCustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" class="modal fade text-left">
                    <div role="document" class="modal-dialog">
                        <div class="modal-content">
                            <?php echo Form::open(['method' => 'post', 'route' => 'customer.store', 'files' => true, 'class' => 'customer-form']); ?>

                            <?php echo csrf_field(); ?>
                            <div class="modal-header">
                                <h5 id="exampleModalLabel" class="modal-title"><?php echo e(trans('Add Customer')); ?></h5>
                                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                        aria-hidden="true"><i class="fa fa-cross"></i></span></button>
                            </div>
                            <div class="modal-body">
                                <p class="italic">
                                    <small><?php echo e(trans('The field labels marked with * are required input fields')); ?>.</small>
                                </p>
                                <div class="form-group">
                                    <label><?php echo e(trans('Name')); ?> *</strong> </label>
                                    <input type="text" name="name" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label><?php echo e(trans('Email')); ?> *</strong> </label>
                                    <input type="text" name="email" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label><?php echo e(trans('Address')); ?> *</strong> </label>
                                    <input type="text" name="address" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label><?php echo e(trans('City')); ?> *</strong> </label>
                                    <input type="text" name="city" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label><?php echo e(trans('Governorate')); ?> *</strong> </label>
                                    <select name="province" id="province" required class="form-control">
                                        <option value="">Choose Governorate</option>
                                        <?php $__currentLoopData = \App\Models\ShippingFee::get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $shipping): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($shipping->city); ?>"><?php echo e($shipping->city); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label style=""><?php echo e(trans('Phone Number')); ?> *</label>
                                    <input type="number" name="phone_number" required class="form-control"
                                        id="phone_number_after" minlength="11" maxlength="15" readonly>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="pos" value="1">
                                    <input type="submit" value="<?php echo e(trans('submit')); ?>" class="btn btn-primary">
                                </div>

                            </div>
                            <?php echo e(Form::close()); ?>

                        </div>
                    </div>
                </div>
                <!-- edit customer modal -->
                <div id="editCustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true" class="modal fade text-left">
                    <div role="document" class="modal-dialog">
                        <div class="modal-content">
                            <?php echo Form::open([
                                'method' => 'post',
                                'files' => true,
                                'class' => 'customer-update-form',
                            ]); ?>

                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="customer_id_edit">
                            <div class="modal-header">
                                <h5 id="exampleModalLabel" class="modal-title"><?php echo e(trans('Edit Customer')); ?></h5>
                                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span
                                        aria-hidden="true"><i class="fa fa-cross"></i></span></button>
                            </div>
                            <div class="modal-body">
                                <p class="italic">
                                    <small><?php echo e(trans('The field labels marked with * are required input fields')); ?>.</small>
                                </p>
                                <div class="form-group">
                                    <label><?php echo e(trans('Name')); ?> *</strong> </label>
                                    <input type="text" name="name2" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label><?php echo e(trans('Email')); ?> *</strong> </label>
                                    <input type="text" name="email2" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label><?php echo e(trans('Address')); ?> *</strong> </label>
                                    <input type="text" name="address2" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label><?php echo e(trans('City')); ?> *</strong> </label>
                                    <input type="text" name="city2" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label><?php echo e(trans('Governorate')); ?> *</strong> </label>
                                    <select name="province" id="province2" required class="form-control">
                                        <option value="">Choose Governorate</option>
                                        <?php $__currentLoopData = \App\Models\ShippingFee::get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $shipping): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($shipping->city); ?>"><?php echo e($shipping->city); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label style=""><?php echo e(trans('Phone Number')); ?> *</label>
                                    <input type="number" name="phone_number2" required class="form-control"
                                        id="phone_number_after2" minlength="11" maxlength="15" readonly>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="pos" value="1">
                                    <input type="submit" value="<?php echo e(trans('submit')); ?>" class="btn btn-primary">
                                </div>

                            </div>
                            <?php echo e(Form::close()); ?>

                        </div>
                    </div>
                </div>

                <div id="order-confirm" class="modal fade">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-zoom modal-xl">
                        <div class="modal-content" id="variants">
                            <div class="modal-header bord-btm">
                                <h4 class="modal-title h6"><?php echo e(trans('Order Summary')); ?></h4>
                                <button type="button" class="close" data-dismiss="modal"><span
                                        aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body" id="order-confirmation">
                                <div class="p-4 text-center">
                                    <i class="las la-spinner la-spin la-3x"></i>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-base-3"
                                    data-dismiss="modal"><?php echo e(trans('Close')); ?></button>
                                <button type="button" onclick="submitOrder('cash_on_delivery')"
                                    class="btn btn-base-1 btn-info"><?php echo e(trans('Confirm with COD')); ?></button>
                            </div>
                        </div>
                    </div>
                </div>

                

            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("ul#branches").siblings('a').attr('aria-expanded', 'true');
        $("ul#branches").addClass("show");
        $("#add").addClass("active");

        var valid;

        // array data depend on warehouse
        var lims_product_array = [];
        var products_available = [];
        var product_code = [];
        var product_name = [];
        var product_qty = [];
        var product_type = [];
        var product_id = [];
        var product_list = [];
        var qty_list = [];

        // array data with selection
        var product_price = [];
        var product_discount = [];
        var tax_rate = [];
        var tax_name = [];
        var tax_method = [];
        var unit_name = [];
        var unit_operator = [];
        var unit_operation_value = [];
        var gift_card_amount = [];
        var gift_card_expense = [];

        // temporary array
        var temp_unit_name = [];
        var temp_unit_operator = [];
        var temp_unit_operation_value = [];


        var deposit = [];
        var product_row_number = 1;
        var rowindex;
        var customer_group_rate;
        var row_product_price;
        var pos;
        var role_id = <?php echo json_encode(\Auth::user()->role_id); ?>;
        var warehouse_id = 1;
        var biller_id = <?php echo json_encode(\Auth::user()->biller_id); ?>;
        var coupon_list = [];
        var currency = "EGP";
        $("#warehouse_id").val(warehouse_id);
        var id = warehouse_id;

        var products = null;

        $(document).ready(function() {
            $('body').addClass('side-menu-closed');
            $('#product-list').on('click', '.add-plus:not(.c-not-allowed)', function() {
                var customer_id = $(' #customer_id_ajax ').val();
                var stock_id = $(this).data('stock-id');
                $.post('<?php echo e(route('pos.addToCart')); ?>', {
                    stock_id: stock_id
                }, function(data) {
                    if (data.success == 1) {
                        updateCart(data.view);
                    } else {
                        alert(data.message);
                    }

                });

            });
            filterProducts();
            // getShippingAddress();
        });

        $(document).on('click', '.add-minus', function() {
            var stock_id = $(this).data('stock-id');
            $.post('<?php echo e(route('pos.decreaseCart')); ?>', {
                stock_id: stock_id
            }, function(data) {
                if (data.success == 1) {
                    updateCart(data.view);
                } else {
                    alert(data.message);
                }

            });

        });
        $(document).on('click', '.add-pluss', function() {
            var stock_id = $(this).data('stock-id');
            $.post('<?php echo e(route('pos.addToCart')); ?>', {
                stock_id: stock_id
            }, function(data) {
                if (data.success == 1) {
                    updateCart(data.view);
                } else {
                    alert(data.message);
                }

            });

        });

        $(document).on('change', '#couponCode', function() {
            console.log("h");
            var code = $(this).val();

            $.ajax({
                type: 'GET',
                url: 'pos/check-coupon-code',
                data: {
                    code: code
                },
                success: function(data) {
                    if (data.success == 1)
                        updateCart(data.view);
                    else {
                        alert("Invalid or Expired Coupon");
                    }
                }
            });
        });


        function updateCart(data) {
            $('#cart-details').html(data);
        }

        function removeFromCart(key) {
            $.post('<?php echo e(route('pos.removeFromCart')); ?>', {
                key: key
            }, function(data) {
                updateCart(data);
            });
        }

        function addToCart(product_id, variant, quantity) {
            $.post('<?php echo e(route('pos.addToCart')); ?>', {
                product_id: product_id,
                variant: variant,
                quantity,
                quantity
            }, function(data) {
                $('#cart-details').html(data);
                $('#product-variation').modal('hide');
            });
        }

        function updateQuantity(key) {
            $.post('<?php echo e(route('pos.updateQuantity')); ?>', {
                key: key,
                quantity: $('#qty-' + key).val()
            }, function(data) {
                if (data.success == 1) {
                    updateCart(data.view);
                } else {
                    alert(data.message);
                }
            });
        }

        function filterProducts() {
            var keyword = $('input[name=keyword]').val();
            $.get('<?php echo e(route('product_sale.search')); ?>', {
                keyword: keyword,
            }, function(data) {
                products = data;
                $('#product-list').html(null);
                setProductList(data);
            });
        }

        function loadMoreProduct() {
            if (products != null && products.links.next != null) {
                $('#load-more').find('.btn').html('<?php echo e(trans('Loading..')); ?>');
                $.get(products.links.next, {}, function(data) {
                    products = data;
                    setProductList(data);
                });
            }
        }

        function setProductList(data) {
            for (var i = 0; i < data.data.length; i++) {
                $('#product-list').append(
                    `<div class="col-3">
                        <div class="card bg-white c-pointer product-card hov-container col-12">
                            <div class="position-relative">
                                <span class="absolute-top-left mt-1 ml-1 mr-0">
                                    ${data.data[i].qty > 0
                                        ? `<span class="badge badge-inline badge-success fs-13"><?php echo e(trans('In stock')); ?>`
                                        : `<span class="badge badge-inline badge-danger fs-13"><?php echo e(trans('Out of stock')); ?>` }
                                    : ${data.data[i].qty}</span>
                                </span>
                                ${data.data[i].variant != null
                                    ? `<span class="badge badge-inline badge-warning absolute-bottom-left mb-1 ml-1 mr-0 fs-13 text-truncate">${data.data[i].variant}</span>`
                                    : '' }
                                    <button style="border:none" class="add-plus  ${data.data[i].qty <= 0 ? 'c-not-allowed' : '' }"  data-stock-id="${data.data[i].stock_id}">
                                <img src="${data.data[i].thumbnail_image }" class="card-img-top img-fit h-120px h-xl-180px h-xxl-210px mw-100 mx-auto" ></button>
                            </div>
                            <div class="card-body p-2 p-xl-3">
                                <div class="text-truncate fw-600 fs-14 mb-2">${data.data[i].name}</div>
                                <div class="">
                                    ${data.data[i].price != data.data[i].base_price
                                        ? `<del class="mr-2 ml-0">${data.data[i].base_price} LE</del><span>${data.data[i].price} LE</span>`
                                        : `<span>${data.data[i].base_price} LE</span>`
                                    }
                                                                            <i class='bx bx-plus absolute-center bx-md text-white'></i>

                                </div>
                            </div>
                            <div class="add-plus absolute-full rounded overflow-hidden hov-box ${data.data[i].qty <= 0 ? 'c-not-allowed' : '' }" data-stock-id="${data.data[i].stock_id}">
                                <div class="absolute-full bg-dark opacity-50">
                                </div>
                                    <i class='bx bx-plus absolute-center bx-md text-white'></i>
                            </div>
                        </div>
                    </div>`
                );
            }
            if (data.links.next != null) {
                $('#load-more').find('.btn').html('<?php echo e(trans('Load More.')); ?>');
            } else {
                $('#load-more').find('.btn').html('<?php echo e(trans('Nothing more found.')); ?>');
            }
        }



        $(window).keydown(function(event) {

            if (event.keyCode == 112) {
                var rownumber = $('table.order-list tbody tr:last').index();
                var customer_id = $('#customer_id_ajax').val();
                var source = $('#source').val();
                temp_data = $('#lims_productcodeSearch').val();

                if (rownumber < 0) {
                    alert("Please insert product to order table!")
                    e.preventDefault();
                }
                $("#cash-btn").click();
                event.preventDefault();
            }
            if (event.keyCode == 13) {
                event.preventDefault();
            }
            if (event.keyCode == 113) {

                var rownumber = $('table.order-list tbody tr: last ').index();
                var customer_id = $(' #customer_id_ajax ').val();
                var warehouse_id = $('#warehouse_id').val();
                var source = $('#source').val();
                temp_data = $('#lims_productcodeSearch').val();

                if (rownumber < 0) {
                    alert("Please insert product to order table!");
                    e.preventDefault();
                }
                $("#credit-card-btn").click();
                event.preventDefault();
            }
            if (event.ctrlKey && event.keyCode == 83) {
                var rownumber = $('table.order-list tbody tr:last').index();
                if (rownumber < 0) {
                    alert("Please insert product to order table!");
                    e.preventDefault();
                } else {
                    $("#submit-btn").click();
                    event.preventDefault();
                }
            }
        });

        $('select[name=customer_id]').val($("input[name='customer_id_hidden']").val());

        var id = $("#warehouse_id").val();

        $.get('sales/getproduct/' + id, function(data) {
            lims_product_array = [];
            product_code = data[0];
            product_name = data[1];
            product_qty = data[2];
            product_type = data[3];
            product_id = data[4];
            product_list = data[5];
            qty_list = data[6];
            $.each(product_code, function(index) {
                lims_product_array.push(product_code[index] + ' (' + product_name[index] + ')');

                products_available[product_code[index]] = product_qty[index];
            });
        });

        $('#lims_productcodeSearch').on('input', function() {
            var data = $('#lims_productcodeSearch').val() + " ";
            var customer_id = $('#customer_id_ajax').val();
            var warehouse_id = $('#warehouse_id').val();
            temp_data = $('#lims_productcodeSearch').val();
            

        });

        $('#get_customer_sale').on('focusout', function() {
            var id = $(this).val();
            var section = $('#customer-section');
            var minLength = 11;
            var maxLength = 11;
            var options = '';
            section.html('');

            if (id.length < minLength) {
                options =
                    '<i class="fa fa-close" style="font-size: 23px; color: #fff;padding: 5px;background-color: red;margin: 3px 15px;"></i>' +
                    '<span > Phone Number Must Be 11 Digit </span> ';
                section.append(options);
            } else if (id.length > maxLength) {

                options =
                    '<i class="fa fa-close" style="font-size: 23px; color: #fff;padding: 5px;background-color: red;margin: 3px 15px;"></i>' +
                    '<span> Phone Number Must Be 11 Digit </span> ';
                section.append(options);
            } else {

                var url = 'get_customer_sale/' + id;
                $.ajax({
                    type: "GET",
                    url: url,
                    data: id,
                    cache: false,
                    success: function(data) {

                        if (data.length > 0) {
                            for (var i = 0; i < data
                                .length; i++) { // Loop through the data &construct the options 
                                options += '<span>Customer Name is : ' + data[i].name + '</span>';
                                options += '<br><span>Customer Address is : ' + data[i].address +
                                    '</span>';
                                options +=
                                    ' <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editCustomer" ><i class="fa fa-edit"></i></button>';

                                options +=
                                    '<input type="hidden" id="customer_id_ajax" name="customer_id_ajax" value="' +
                                    data[i].id + '">';
                                $('input[name="name2" ]').val(data[i].name);
                                $('input[name="address2" ]').val(data[i].address);
                                var customer_id = $('#get_customer_sale').val();
                                var customer_Phone = $('#phone_number_after2');
                                customer_Phone.val(customer_id);
                                $('input[name="city2" ]').val(data[i].city);
                                $('input[name="email2" ]').val(data[i].email);
                                $('input[name="customer_id_edit" ]').val(data[i].id);
                                $("#province").val(data[i].state);
                            }
                            $.get('sales/getproduct/' + warehouse_id, function(data) {
                                lims_product_array = [];
                                product_code = data[0];
                                product_name = data[1];
                                product_qty = data[2];
                                product_type = data[3];
                                $.each(product_code, function(index) {
                                    lims_product_array.push(product_code[
                                            index] + ' (' +
                                        product_name[index] + ')');
                                });
                            });
                        } else {
                            options +=
                                '<i class="fa fa-close" style="font-size: 23px; color: #fff;padding: 5px;background-color: red;margin: 3px 15px;"></i>' +
                                '<span > No Customer Found >> </span> ';
                            options +=
                                '<input type="hidden" id="customer_id_ajax" name="customer_id_ajax">';
                            options +=
                                ' <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#addCustomer" ><i class="fa fa-plus"></i></button>';
                            var customer_id = $('#get_customer_sale').val();
                            var customer_Phone = $('#phone_number_after');
                            customer_Phone.val(customer_id);
                        }
                        options += '</select>'; // Append to the html 
                        section.append(options);
                    }
                });
            }
        });

        $('body').on('click',
            function(e) {
                $('.filter-window').hide('slide', {
                    direction: 'right'
                }, 'fast');
            });

        function populateProduct(data) {
            var tableData =
                '<table id="product-table" class="table no-shadow product-list"> <thead class="d-none"> <tr> <th></th> <th></th> <th></th> <th></th> <th></th> </tr></thead> <tbody><tr>';
            if (Object.keys(data)
                .length != 0) {
                $.each(data['name'], function(index) {
                    var product_info = data['code'][index] + ' (' + data['name'][index] + ')';
                    if (index % 5 == 0 && index != 0) tableData +=
                        '</tr><tr><td class="product-img sound-btn" title="' + data['name'][index] +
                        '" data-product = "' + product_info + '"><img  src="' + data['image'][index] +
                        '" width="100%" /><p>' + data['name'][index] + '</p><span>' + data['code'][
                            index
                        ] + '</span></td>';
                    else tableData += '<td class="product-img sound-btn" title="' + data['name'][index] +
                        '" data-product = "' + product_info + '"><img  src="' + data['image'][index] +
                        '" width="100%" /><p>' + data['name'][index] + '</p><span>' + data['code'][
                            index
                        ] + '</span></td>';
                });
                if (data['name'].length % 5) {
                    var number = 5 - (data['name'].length % 5);
                    while (number > 0) {
                        tableData += '<td style="border:none;"></td>';
                        number--;
                    }
                }
                tableData += '</tr> < /tbody > </table>';
                $(".table-container")
                    .html(tableData);
                $('#product-table')
                    .DataTable({
                        "order": [],
                        'pageLength': product_row_number,
                        'language': {
                            'paginate': {
                                'previous': '<i class="fa fa-angle-left"></i>',
                                'next': '<i class="fa fa-angle-right"></i>'
                            }
                        },
                        dom: 'tp'
                    });
                $('table.product-list')
                    .hide();
                $('table.product-list')
                    .show(500);
            } else {
                tableData += '<td class="text-center">No data avaialable</td> < /tr > </tbody> < /table > '
                $(".table-container")
                    .html(tableData);
            }
        }

        $('select[name="customer_id"]').on('change', function() {
            var id = $(this).val();
            $.get('sales/getcustomergroup/' + id, function(data) {
                customer_group_rate = (data / 100);
            });
        });

        var lims_productcodeSearch = $('#lims_productcodeSearch');

        lims_productcodeSearch.autocomplete({

            source: function(request, response) {

                var matcher = new RegExp("^" +
                    $.ui.autocomplete.escapeRegex(request.term), "i");
                response($.grep(lims_product_array, function(item) {
                    return matcher.test(item);
                }));
            },
            response: function(event, ui) {
                if (ui.content.length == 1) {
                    var data = ui.content[0].value;
                    $(this).autocomplete("close");
                    productSearch(data);
                    console.log("hi2");
                };
            },
            select: function(event, ui) {
                var data = ui.item.value;
                console.log("hi");
                productSearch(data);
            }
        });

        $('#myTable').keyboard({
            accepted: function(event, keyboard, el) {
                checkQuantity(el.value, true);
            }
        });

        $("#myTable").on('click', '.plus', function() {
            rowindex = $(this).closest('tr').index();
            var qty = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val()) + 1;
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') input[name="qty[]"]').val(qty);
            console.log(qty, rowindex, $('table.order-list tbody tr:nth-child(' + (rowindex + 1) +
                ') input[name="qty[]"]').val());
            checkQuantity(String(qty), true);
        });

        $("#myTable").on('click', '.minus', function() {
            rowindex = $(this).closest('tr').index();
            var qty = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) +
                ') input[name="qty[]"]').val()) - 1;
            if (qty > 0) {
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') input[name="qty[]"]').val(qty);
            } else {
                qty = 1;
            }
            checkQuantity(String(qty), true);
        });

        //Change quantity
        $("#myTable").on('input', '.qty', function() {
            console.log("hi");
            rowindex = $(this).closest('tr').index();
            if ($(this).val() < 1 && $(this).val() != '') {
                $('table.order-list tbody tr:nth-child(' + (rowindex +
                        1) +
                    ') .qty').val(1);
                alert("Quantity can't be less than 1");
            }
            checkQuantity($(this).val(), true);
        });
        $("#myTable").on('click', '.qty', function() {
            rowindex = $(this).closest('tr').index();
        });
        $(document).on('click', '.sound-btn', function() {
            var
                audio = $("#mysoundclip1")[0];
            audio.play();
        });
        $(document).on('click', '.product-img', function() {
            var
                customer_id = $('#customer_id_ajax').val();
            var
                warehouse_id = $('select[name="warehouse_id" ]').val();
            var data = $(this).data('product');
            data = data.split(" ");
            pos = product_code.indexOf(data[0]);
            if (pos < 0)
                alert(
                    'Product is not avaialable in the selected warehouse'
                );
            else {
                productSearch(data[0]);
            }
        });
        //Delete product
        $(" table.order-list tbody").on("click", ".ibtnDel", function(
            event) {
            var audio = $("#mysoundclip2")[0];
            audio.play();
            rowindex = $(this).closest('tr').index();
            product_price.splice(rowindex, 1);
            product_discount.splice(rowindex, 1);
            tax_rate.splice(rowindex, 1);
            tax_name.splice(rowindex, 1);
            tax_method.splice(rowindex, 1);
            unit_name.splice(rowindex,
                1);
            unit_operator.splice(rowindex, 1);
            unit_operation_value.splice(rowindex, 1);
            $(this).closest("tr").remove();
            calculateTotal();
        }); //Edit product 
        $("table.order-list").on("click", ".edit-product",
            function() {
                rowindex = $(this).closest('tr').index();
                edit();
            }); //Update product 
        $('button[name="update_btn"]').on("click", function() {
            var
                edit_discount = $('input[name="edit_discount" ]').val();
            var
                edit_qty = $('input[name="edit_qty" ]').val();
            var
                edit_unit_price = $('input[name="edit_unit_price" ]').val();
            if (parseFloat(edit_discount) > parseFloat(edit_unit_price)) {
                alert('Invalid Discount Input!');
                return;
            }

            if (edit_qty < 1) {
                $('input[name="edit_qty" ]').val(1);
                edit_qty = 1;
                alert("Quantity can't be less than 1");
            }
            var tax_rate_all = [];
            tax_rate[rowindex] =
                parseFloat(tax_rate_all[$('select[name="edit_tax_rate"] ').val()]);
            tax_name[rowindex] = $('select[name="edit_tax_rate" ] option: selected ').text();
            product_discount[rowindex] = $('input[name="edit_discount"] ').val();
            if (product_type[pos] == 'standard ') {
                var row_unit_operator = unit_operator[rowindex].slice(0,
                    unit_operator[rowindex].indexOf(","));
                var
                    row_unit_operation_value = unit_operation_value[
                        rowindex].slice(0,
                        unit_operation_value[rowindex].indexOf(",")
                    );
                if (row_unit_operator == '*') {
                    product_price[rowindex] = $('input[name="edit_unit_price"]').val() / row_unit_operation_value;
                } else {
                    product_price[rowindex] = $('input[name="edit_unit_price"]').val() * row_unit_operation_value;
                }
                var
                    position = $('select[name="edit_unit" ]').val();
                var
                    temp_operator = temp_unit_operator[
                        position];
                var
                    temp_operation_value =
                    temp_unit_operation_value[
                        position];
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                    '.sale-unit').val(temp_unit_name[position]);
                temp_unit_name.splice(position,
                    1);
                temp_unit_operator.splice(position, 1);
                temp_unit_operation_value.splice(position,
                    1);
                temp_unit_name.unshift($('select[name="edit_unit" ] option: selected ')
                    .text());
                temp_unit_operator.unshift(
                    temp_operator
                );
                temp_unit_operation_value
                    .unshift(
                        temp_operation_value
                    );
                unit_name[
                        rowindex] = temp_unit_name
                    .toString() +
                    ',';
                unit_operator[rowindex] =
                    temp_unit_operator.toString() +
                    ',';
                unit_operation_value[
                        rowindex] =
                    temp_unit_operation_value
                    .toString() +
                    ',';
            }
            checkQuantity(edit_qty, false);
        });
        $('button[name="shipping_cost_btn" ]').on(
            "click",
            function() {
                calculateGrandTotal();
            });
        $(".payment-btn").on("click", function(e) {

            if (confirm(
                    "Are you sure want to Create?")) {
                var customer_id = $('#customer_id_ajax').val();
                var source = $('#source').val();
                temp_data = $('#lims_productcodeSearch').val();
                $('input[name="paid_amount"]').val($("#grand-total").text());
                var total_qty = 0;

                $(".payment-form").submit();


            } else {
                return false;
            }
        });
        $('input[name="paying_amount"]').on("focusout", function(e) {
            var paid_amount =
                parseFloat(
                    $(
                        '#grand-total'
                    )
                    .text()
                );
            var
                paying_amount =
                $(
                    this
                )
                .val();
            change(
                paying_amount,
                paid_amount
            );
            console.log(
                $(this).val()
            );
            console.log(
                paid_amount
            );
        });
        $('input[name="paid_amount"]').on("input", function(e) {
            console.log("Paid Hi");
            if ($(this)
                .val() >
                parseFloat(
                    $(
                        'input[name="paying_amount"]'
                    )
                    .val()
                )
            ) {
                e
                    .preventDefault();
                alert
                    ('Paying amount cannot be bigger than recieved amount ');
                $(
                        this
                    )
                    .val(
                        ''
                    );
            } else if (
                $(
                    this
                )
                .val() >
                parseFloat(
                    $(
                        '#grand-total'
                    )
                    .text()
                )
            ) {
                e
                    .preventDefault();
                alert
                    ('Paying amount cannot be bigger than grand total ');
                $(
                        this
                    )
                    .val(
                        ''
                    );
            }

            change
                ($(
                        'input[name="paying_amount"]'
                    )
                    .val(),
                    $(
                        this
                    )
                    .val()
                );
            var id =
                $(
                    'select[name="paid_by_id_select"]'
                )
                .val();
            if (id ==
                2
            ) {
                var balance =
                    gift_card_amount[
                        $(
                            "#gift_card_id_select"
                        )
                        .val()
                    ] -
                    gift_card_expense[
                        $(
                            "#gift_card_id_select"
                        )
                        .val()
                    ];
                if ($(
                        this
                    )
                    .val() >
                    balance
                )
                    alert(
                        'Amount exceeds card balance! Gift Card balance: ' +
                        balance
                    );
            } else if (
                id ==
                6
            ) {
                if ($(
                        'input[name="paid_amount"]'
                    )
                    .val() >
                    deposit[
                        $(
                            '#customer_id'
                        )
                        .val()
                    ]
                )
                    alert(
                        'Amount exceeds customer deposit! Customer deposit:' + deposit[$('#customer_id').val()]);
            }
        });

        function change(paying_amount, paid_amount) {
            $("#change").text(parseFloat(paying_amount - paid_amount).toFixed(2));
        }

        function confirmDelete() {
            if (confirm("Are you sure want to delete?")) {
                return true;
            }
            return false;
        }

        function productSearch(data) {
            console.log("prod search", data, "hi");
            $.ajax({
                type: 'GET',
                url: 'sales/lims_product_search',
                data: {
                    data: data
                },
                success: function(data) {
                    if (!data)
                        alert("Product Not Found");
                    else {
                        var flag = 1;
                        $(".product-code").each(function(i) {
                            if ($(this).val() == data[1]) {
                                rowindex = i;
                                var pre_qty = $('table.order-list tbody tr:nth-child(' + (
                                    rowindex + 1) + ') .qty').val();
                                console.log("pre", pre_qty);
                                if (pre_qty) var qty = parseFloat(pre_qty) + 1;
                                else var qty = 1;
                                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) +
                                    ') .qty').val(qty);
                                flag = 0;
                                checkQuantity(String(qty), true);
                                flag = 0;
                            }
                        });
                        $("input[name='product_code_name']").val('');
                        if (flag) {
                            addNewProduct(data);
                        }
                    }
                }
            });
        }

        function addNewProduct(data) {
            var newRow = $("<tr>");
            var cols = '';
            temp_unit_name = (data[6]).split(',');
            cols += '<td class="col-sm-4 product-title"><strong>' + data[0] + '</strong> [' + data[1] + '] </td>';
            cols += '<td class="col-sm-2 product-price"></td>';
            cols +=
                '<td class="col-sm-3"><div class="input-group"><span class="input-group-btn"><button type="button" class="btn btn-default minus"><span class="fa fa-minus"></span></button></span><input type="text" name="qty[]" class="form-control qty numkey input-number" value="1" step="any" required><span class="input-group-btn"><button type="button" class="btn btn-default plus"><span class="fa fa-plus"></span></button></span></div></td>';
            cols += '<td class="col-sm-2 sub-total"></td>';
            cols +=
                '<td class="col-sm-1"><button type="button" class="ibtnDel btn btn-danger btn-sm"><i class="fa fa-cross"></i></button></td>';
            cols += '<input type="hidden" class="product-code" name="product_code[]" value="' + data[1] + '" />';
            cols += '<input type="hidden" class="product-id" name="product_id[]" value="' + data[9] + '" />';
            cols += '<input type="hidden" class="sale-unit" name="sale_unit[]" value="' + temp_unit_name[0] + '" />';
            cols += '<input type="hidden" class="net_unit_price" name="net_unit_price[]" />';
            cols += '<input type="hidden" class="discount-value" name="discount[]" />';
            cols += '<input type="hidden" class="tax-rate" name="tax_rate[]" value="' + data[3] + '" />';
            cols += '<input type="hidden" class="tax-value" name="tax[]" />';
            cols += '<input type="hidden" class="subtotal-value" name="subtotal[]" />';
            newRow.append(cols);
            $("table.order-list tbody").append(newRow);

            console.log(data[2]);
            product_price.push(parseFloat(data[2]));
            product_discount.push('0.00');
            tax_rate.push(parseFloat(data[3]));
            tax_name.push(data[4]);
            tax_method.push(data[5]);
            unit_name.push(data[6]);
            unit_operator.push(data[7]);
            unit_operation_value.push(data[8]);
            rowindex = newRow.index();
            checkQuantity(1, true);
        }


        function edit() {
            var row_product_name_code = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                'td:nth-child(1)').text();
            $('#modal_header').text(row_product_name_code);
            var qty = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val();
            $('input[name="edit_qty"]').val(qty);
            $('input[name="edit_discount"]').val(parseFloat(product_discount[rowindex]).toFixed(2));
            var tax_name_all = [];
            pos = tax_name_all.indexOf(tax_name[rowindex]);
            $('select[name="edit_tax_rate"]').val(pos);
            var row_product_code = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.product-code')
                .val();
            pos = product_code.indexOf(row_product_code);
            if (product_type[pos] == 'standard') {
                unitConversion();
                temp_unit_name = (unit_name[rowindex]).split(',');
                temp_unit_name.pop();
                temp_unit_operator = (unit_operator[rowindex]).split(',');
                temp_unit_operator.pop();
                temp_unit_operation_value = (unit_operation_value[rowindex]).split(',');
                temp_unit_operation_value.pop();
                $('select[name="edit_unit"]').empty();
                $.each(temp_unit_name, function(key, value) {
                    $('select[name="edit_unit"]').append('<option value="' + key + '">' + value + '</option>');
                });
                $("#edit_unit").show();
            } else {
                row_product_price = product_price[rowindex];
                $("#edit_unit").hide();
            }
            $('input[name="edit_unit_price"]').val(row_product_price.toFixed(2));
        }

        function checkQuantity(sale_qty, flag) {
            var row_product_code = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.product-code')
                .val();
            console.log(product_code, lims_product_array);
            pos = product_code.indexOf(row_product_code);
            total_qty = sale_qty
            available = products_available[row_product_code];
            if (sale_qty > available)
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(available);
            else {
                if (!flag) {
                    $('#editModal').modal('hide');
                    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(sale_qty);
                }
                calculateRowProductData(sale_qty);
            }
        }

        function calculateRowProductData(quantity) {
            console.log("qty", quantity);
            row_product_price = product_price[rowindex];

            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.discount-value').val((product_discount[
                rowindex] * quantity).toFixed(2));
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-rate').val(tax_rate[rowindex]
                .toFixed(2));

            var sub_total_unit = row_product_price - product_discount[rowindex];
            var net_unit_price = (100 / (100 + tax_rate[rowindex])) * sub_total_unit;
            var tax = (sub_total_unit - net_unit_price) * quantity;
            var sub_total = sub_total_unit * quantity;

            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.net_unit_price').val(net_unit_price
                .toFixed(2));
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-value').val(tax.toFixed(2));
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(2)').text(sub_total_unit
                .toFixed(2));
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(4)').text(sub_total
                .toFixed(2));
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.subtotal-value').val(sub_total
                .toFixed(2));
            calculateTotal();



        }

        function calculateTotal(offer = 0) {
            //Sum of quantity
            var total_qty = 0;
            var qty = [];
            $("table.order-list tbody .qty").each(function(index) {
                if ($(this).val() == '') {
                    total_qty += 0;
                    qty.push(0);
                } else {
                    total_qty += parseFloat($(this).val());
                    qty.push(parseFloat($(this).val()));
                }
            });
            $('input[name="total_qty"]').val(total_qty);

            //Sum of discount
            var total_discount = 0;
            $("table.order-list tbody .discount-value").each(function() {
                total_discount += parseFloat($(this).val());
            });

            $('input[name="total_discount"]').val(total_discount.toFixed(2));

            //Sum of tax
            var total_tax = 0;
            $(".tax-value").each(function() {
                total_tax += parseFloat($(this).val());
            });

            $('input[name="total_tax"]').val(total_tax.toFixed(2));

            //Sum of subtotal
            var total = 0;
            var subtotals = [];
            $(".sub-total").each(function() {
                total += parseFloat($(this).text());
                subtotals.push(parseFloat($(this).text()));
            });
            $('input[name="total_price"]').val(total.toFixed(2));



            var products = [];
            $('input[name="product_code[]"]').each(function() {

                products.push($(this).val());
            });


            //calc product prices 
            var prices = [];
            $(".product-price").each(function() {
                prices.push(parseFloat($(this).text()));
            });

            calculateGrandTotal(offer);
        }


        function calculateGrandTotal(offer = 0) {
            var item = $('table.order-list tbody tr:last').index();
            var total_qty = parseFloat($('input[name="total_qty"]').val());
            var subtotal = parseFloat($('input[name="total_price"]').val());
            order_discount = 0.00;
            $("#discount").text(order_discount.toFixed(2));
            var shipping_cost = parseFloat($('input[name="shipping_cost"]').val());
            if (!shipping_cost) shipping_cost = 0.00;
            item = ++item + '(' + total_qty + ')';
            order_tax = 0.0;
            var grand_total = ((subtotal + order_tax + shipping_cost) - order_discount) - offer;
            $('input[name="grand_total"]').val(grand_total.toFixed(2));
            coupon_discount = 0.00;
            grand_total -= coupon_discount;
            $('#item').text(item);
            $('input[name="item"]').val($('table.order-list tbody tr:last').index() + 1);
            $('#subtotal').text(subtotal.toFixed(2));
            $('#tax').text(order_tax.toFixed(2));
            $('input[name="order_tax"]').val(order_tax.toFixed(2));
            $('#shipping-cost').text(shipping_cost.toFixed(2));
            $('#grand-total').text(grand_total.toFixed(2));
            $('input[name="paying_amount"]').val($("#grand-total").text());
            $('input[name="grand_total"]').val(grand_total.toFixed(2));
        }

        function hide() {
            $(".card-element").hide();
            $(".card-errors").hide();
            $(".cheque").hide();
            $(".gift-card").hide();
            $('input[name="cheque_no"]').attr('required', false);
        }

        function cheque() {
            $(".cheque").show();
            $('input[name="cheque_no"]').attr('required', true);
            $(".card-element").hide();
            $(".card-errors").hide();
            $(".gift-card").hide();
        }

        function creditCard() {
            $.getScript("vendor/stripe/checkout.js");
            $(".card-element").show();
            $(".card-errors").show();
            $(".cheque").hide();
            $(".gift-card").hide();
            $('input[name="cheque_no"]').attr('required', false);
        }

        function deposits() {
            if ($('input[name="paid_amount"]').val() > deposit[$('#customer_id').val()]) {
                alert('Amount exceeds customer deposit! Customer deposit: ' + deposit[$('#customer_id').val()]);
            }
            $('input[name="cheque_no"]').attr('required', false);
            $('#add-payment select[name="gift_card_id_select"]').attr('required', false);
        }

        function confirmCancel() {
            var audio = $("#mysoundclip2")[0];
            audio.play();
            if (confirm("Are you sure want to cancel?")) {
                cancel($('table.order-list tbody tr:last').index());
                location.reload();
            }
            return false;
        }

        $(document).on('submit', '.customer-form', function(e) {
            e.preventDefault();
            let name = $('input[name="name"]').val();
            console.log("ssaq");
            let phone_number = $('input[name="phone_number"]').val();
            let email = $('input[name="email"]').val();
            let address = $('input[name="address"]').val();
            let city = $('input[name="city"]').val();
            let province = $('#province').val();
            let customer_group_id = $('input[name="customer_group_id"]').val();
            let pos = $('input[name="pos"]').val();
            $.ajax({
                url: "/customer/storeCustomer",
                type: "POST",
                data: {
                    "_token": "<?php echo e(csrf_token()); ?>",
                    name: name,
                    phone_number: phone_number,
                    email: email,
                    address: address,
                    city: city,
                    province: province,
                    customer_group_id: customer_group_id,
                    pos: pos,
                },
                success: function(response) {
                    if (response.errors != "")
                        alert(response.errors);
                    else {
                        $("#addCustomer").hide();
                        $(".modal-backdrop").show();
                        $(".modal-backdrop").hide();
                        $("#get_customer_sale").focus();
                        $("#lims_productcodeSearch").focus().delay(1000);
                    }
                },
            });
        });

        $(document).on('submit', '.customer-update-form', function(e) {
            e.preventDefault();
            let name = $('input[name="name2"]').val();
            console.log("ssaq");
            let phone_number = $('input[name="phone_number2"]').val();
            let email = $('input[name="email2"]').val();
            let address = $('input[name="address2"]').val();
            let city = $('input[name="city2"]').val();
            let province = $('#province2').val();
            let customer_group_id = $('input[name="customer_group_id"]').val();
            let customer_id_edit = $('input[name="customer_id_edit"]').val();
            let pos = $('input[name="pos"]').val();
            $.ajax({
                url: "<?php echo e(route('customer.update', 1)); ?>",
                type: "PUT",
                data: {
                    "_token": "<?php echo e(csrf_token()); ?>",
                    name: name,
                    phone_number: phone_number,
                    customer_id_edit: customer_id_edit,
                    email: email,
                    address: address,
                    city: city,
                    province: province,
                    customer_group_id: customer_group_id,
                    pos: pos,
                },
                success: function(response) {
                    if (response.errors != "")
                        alert(response.errors);
                    else {
                        console.log("succrss");
                        alert("Address Updated Successfully");
                        $("#get_customer_sale").focus();

                        $("#lims_productcodeSearch").focus().delay(500);

                    }
                },
            });
        });

        $('select[name="sale_by_employee_select"]').change(function() {
            $('input[name="sale_by_id"]').val($('select[name="sale_by_employee_select"]').val());
        });

        $('select[name="paid_by_id_select"]').change(function() {
            var id = $('select[name="paid_by_id_select"]').val();
            var details = $('.card-details');
            if (id == 3) {
                var cols = '';
                cols +=
                    '<div class="col-md-6"><input type="text" class="form-control" name="card_ref" required placeholder="Machine Reference" /></div>';
                cols +=
                    '<div class="col-md-6"><input type="number" class="form-control" name="card_last_digit" minlength="3" required placeholder="Last 4 Digit" /></div>';
                details.append(cols);
            } else {
                details.html(" ");
            }
        });
        $(document).on('submit', '.payment-form', function(e) {
            var rownumber = $('table.order-list tbody tr:last').index(),
                card_last = $('input[name="card_last_digit"]').val(),
                paid_by = $('select[name="paid_by_id_select"]').val();
            console.log(paid_by);
            
            if (paid_by == 3) {
                if (card_last.length < 4) {
                    alert("Please insert 4 digit ");
                    return false;
                    e.preventDefault();
                } else if (card_last.length > 4) {
                    alert(" Please insert 4 digit not more");
                    return false;
                    e.preventDefault();
                }
            }
            $('button[type="submit"]').unbind('click');
            $('button[type="submit"]').remove();
            $('input[type="submit"]').unbind('click');
            $('input[type="submit"]').remove();
            $('input[name="paid_by_id"]').val($('select[name="paid_by_id_select"]').val());
            $('input[name="order_tax_rate"]').val($('select[name="order_tax_rate_select"]').val());
        });

        $('#product-table').DataTable({
            "order": [],
            'pageLength': product_row_number,
            'language': {
                'paginate': {
                    'previous': '<i class="fa fa-angle-left"></i>',
                    'next': '<i class="fa fa-angle-right"></i>'
                }
            },
            dom: 'tp'
        });

        $(document).bind("contextmenu", function(e) {
            return false;
        });
        $(document).keydown(function(event) {
            if (event.keyCode == 123) {
                //Prevent F12
                return false;
            } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) {
                // Prevent Ctrl +Shift +I
                return false;
            }
        }); //prevent ctrl + s 

        $(document).bind('keydown', function(e) {
            if (e.ctrlKey && (e.which == 83)) {
                e.preventDefault();
                return false;
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.top-head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/stock_requests/create.blade.php ENDPATH**/ ?>