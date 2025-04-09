

<?php $__env->startSection('content'); ?>
    <div class="pagetitle">
        <div class="row">
            <div class="col-12">
                <div class="col-9">
                    <h1>Order <?php echo e($order->name); ?></h1>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo e(route('shopify.orders')); ?>">Orders</a></li>
                            <li class="breadcrumb-item">Order <?php echo e($order->name ?? ''); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="card">
                <form action="<?php echo e(route('shopify.order.fulfillitems')); ?>" method="POST" id="prepare_orders_store">
                    <?php echo csrf_field(); ?>
                    <div class="card-body text-center">
                        <?php if($prepare->order->prepare_note): ?>
                            <br>
                            <hr>
                            <h6 class="text-center" style="color:red">Order Prepare Note :
                                <?php echo e($prepare->order->prepare_note); ?></h4>
                                <br>
                                <hr>
                        <?php endif; ?>
                        <h5 class="card-title">Items</h5>
                        <input type="hidden" id="order_id" name="order_id" value="<?php echo e($order['table_id']); ?>">
                        <input type="hidden" id="order_number" name="order_number" value="<?php echo e($order['order_number']); ?>">
                        <?php if($prepare->products): ?>
                            <div class="row">
                                <?php $__currentLoopData = $prepare->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-3 mb-2">
                                        <div>
                                            <strong
                                                style="color:red"><?php echo e($item['product_status'] != 'prepared' && $item['product_status'] != 'unfulfilled' ? '(' . str_replace('_', ' ', ucfirst($item['product_status'])) . ')' : '-'); ?></strong>
                                        </div>
                                        <div>
                                            <?php if(isset($item)): ?>
                                                <?php if(isset($item->variant_image)): ?>
                                                    <a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#imagesmodal-<?php echo e($item['product_id']); ?>">
                                                        <img height="300" width="auto" src="<?php echo e($item->variant_image); ?>"
                                                            alt="Image here">
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <br>
                                        </div>
                                        <div>
                                            <strong>
                                                <br>
                                                <?php echo e($item['product_name']); ?><br>
                                                <small>Qty : <?php echo e($item['order_qty']); ?></small>
                                                <br>
                                                <small>Variation : <?php echo e($item['variation_id']); ?></small>
                                                <br>
                                                <small>Price : <?php echo e($item['price'] ?? ''); ?></small><br>
                                                <small>Sku : <?php echo e($item['product_sku'] ?? ''); ?></small><br>
                                            </strong>
                                        </div>

                                        <div>

                                            <?php if(in_array($item['product_id'], $refunds)): ?>
                                                <span class="badge bg-dark">Removed</span>
                                                <input name = "product_status[]" type="hidden" value="prepared">
                                            <?php elseif(in_array($item['product_id'], $returns)): ?>
                                                <span class="badge bg-secondary">Returned</span>
                                                <input name = "product_status[]" type="hidden" value="prepared">
                                            <?php elseif($item['product_status'] == 'prepared'): ?>
                                                <span class="badge bg-success mb-1">Prepared</span>
                                                <input name = "product_status[]" type="hidden" value="prepared">
                                                <?php if($order->fulfillment_status == 'shipped'): ?>
                                                    <br>
                                                    <select name="returns[]" id="return_order<?php echo e($item['product_id']); ?>"
                                                        class="form-select" aria-label="Default select example"
                                                        onchange="returnn(<?php echo e($item['product_id']); ?>)">
                                                        <option value="">Options ...
                                                        </option>
                                                        <option value="<?php echo e($item['product_id']); ?>"> Return</option>
                                                    </select>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <select name="product_status[]" class="form-select"
                                                    aria-label="Default select example" required>
                                                    <option value=" ">Choose Status ...
                                                    </option>
                                                    <option value="prepared"
                                                        <?php if($item['product_status'] == 'prepared'): ?> selected <?php endif; ?>>
                                                        Prepared</option>
                                                    <option value="hold"
                                                        <?php if($item['product_status'] != 'prepared' && $item['product_status'] != 'unfulfilled'): ?> selected <?php endif; ?>>
                                                        Hold</option>
                                                </select>
                                                <?php $__errorArgs = ['product_status.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <span class="text-danger"><?php echo e($message); ?></span>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <?php endif; ?>

                                            <input type="hidden" name="line_item_id[]" value="<?php echo e($item['product_id']); ?>">
                                            <input type="hidden" name="qty[]" value="<?php echo e($item['order_qty']); ?>">
                                            <input type="hidden" name="prepare_product_id[]" value="<?php echo e($item['id']); ?>">
                                        </div>
                                        <div id="<?php echo e($item['product_id']); ?>" style="display:none">
                                            <label for="return_qty[]">Return Qty</label>
                                            <input type="number" class="form-control" name="return_qty[]"
                                                placeholder="Enter Qty" max="<?php echo e($item['order_qty']); ?>" min="1"
                                                oninput="validity.valid || (value = <?php echo e($item['order_qty']); ?>);"
                                                value="<?php echo e($item['order_qty']); ?>">
                                            <?php $__errorArgs = ['qty.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-danger"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <input type="hidden" name="items[]" id="items<?php echo e($item['product_id']); ?>">
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
                                            <?php $__errorArgs = ['reason.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-danger"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <label for="amount[]">Amount</label>
                                            <input type="number" name="amount[]" class="form-control"
                                                max="<?php echo e($item['price']); ?>" min="0"
                                                oninput="validity.valid || (value = <?php echo e($item['price']); ?>);"
                                                value="<?php echo e($item['price']); ?>">
                                            <?php $__errorArgs = ['amount.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-danger"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>
                            <hr>
                            <?php if($prepare->delivery_status == 'distributed' || $prepare->delivery_status == 'hold'): ?>
                                <div class="row justify-content-end">
                                    <div class="col-md-12">
                                        <div class="form-group mt-4">
                                            <input type="submit" value="Save Order" class="btn btn-primary">
                                        </div>
                                    </div>
                                </div>
                            <?php elseif($order->fulfillment_status == 'shipped'): ?>
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
                                <?php if(count($prepare->products) > count($returns)): ?>
                                <div class="row justify-content-center m-2" id="return_all_submit">
                                    <div class="col-3">
                                        <button onclick="return_order(1)" type="button" class="btn btn-danger">Return
                                            All</button>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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
                url: "<?php echo e(route('orders.return')); ?>",
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/preparation/prepare.blade.php ENDPATH**/ ?>