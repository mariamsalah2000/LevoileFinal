

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
            <div class="col-lg-12">
                <div class="">
                    <div class="col-xxl-12 col-md-12">
                        <div class="card info-card sales-card">
                            <div class="card-body pb-0 mt-2">
                                <h5 class="card-title">Order Details</h5>
                                <table class="table table-borderless">
                                    <thead>
                                        <th>Payment Status</th>
                                        <th class="text-center">Fulfillment Status</th>
                                        <th style="float:right">Order Date</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo e($order->getPaymentStatus()); ?></td>
                                            <td class="text-center"><?php echo e($order->getFulfillmentStatus()); ?></td>
                                            <td style="float: right;">
                                                <?php echo e(date('F d, Y', strtotime($order['created_at_date']))); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <form action="<?php echo e(route('prepare.review.post')); ?>" method="POST" id="prepare_orders_store">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <h5 class="card-title">Items</h5>
                        <input type="hidden" id="order_id" name="order_id" value="<?php echo e($order['order_number']); ?>">
                        <?php if($prepare->products): ?>
                            <div class="row">
                                <?php $__currentLoopData = $prepare->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-3 mb-2">
                                        <div>
                                            <?php if(isset($product_images)): ?>
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
                                            <?php elseif($item['product_status'] == 'prepared'): ?>
                                                <span class="badge bg-success">Prepared</span>
                                                <input name = "product_status[]" type="hidden" value="prepared">
                                            <?php else: ?>
                                                <span class="badge bg-danger">Prepared</span>
                                                <input name = "product_status[]" type="hidden" value="hold">
                                            <?php endif; ?>
                                            <input type="hidden" name="line_item_id[]" value="<?php echo e($item['product_id']); ?>">
                                            <input type="hidden" name="qty[]" value="<?php echo e($item['order_qty']); ?>">
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-12">
                                    <div class="form-group mt-4">
                                        <input type="submit" value="Submit" class="btn btn-primary">
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php
                        $shipping_address = $order['shipping_address'];
                        if (!is_array($order['shipping_address'])) {
                            $shipping_address = json_decode($order['shipping_address']);
                            $shipping_address = $shipping_address
                                ? $shipping_address
                                : (is_array($order['customer'])
                                    ? $order['customer']
                                    : json_decode($order['customer']));
                        }

                    ?>

                    <div class="card-footer">
                        <table class="table table-hover table-xl mb-0 total-table">
                            <tbody>
                                <?php if(!empty($order->getDiscountBreakDown())): ?>
                                    <?php $__currentLoopData = $order->getDiscountBreakDown(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $title => $discount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="text-truncate text-left">Discount Code</td>
                                            <td class="text-truncate text-left"><b><?php echo e($title ?? ''); ?></b>
                                            </td>
                                            <td class="text-truncate text-right"><span style="float:right">-
                                                    <?php echo e($order_currency . ' ' . number_format($discount, 2)); ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(count($order->getDiscountBreakDown()) > 1): ?>
                                        <tr>
                                            <td class="text-truncate text-left">Total Discount</td>
                                            <td class="text-truncate text-left"></td>
                                            <td class="text-truncate text-right"><span style="float:right">-
                                                    <?php echo e($order_currency . ' ' . number_format($order->total_discounts, 2)); ?></span>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <tr>
                                    <td class="text-truncate text-left">Subtotal</td>
                                    <td class="text-truncate text-left"><?php echo e(count($order['line_items'])); ?>

                                        <?php echo e(count($order['line_items']) > 1 ? 'Items' : 'Item'); ?></td>
                                    <td class="text-truncate text-right"><span style="float:right"><?php echo e($order_currency); ?>

                                            <?php echo e(number_format($order['subtotal_price'], 2)); ?></span></td>
                                </tr>
                                <?php if(!empty($order['shipping_lines'])): ?>
                                    <?php $total_shipping = 0; ?>
                                    <?php $__currentLoopData = $order['shipping_lines']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ship): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="text-truncate text-left">Shipping</td>
                                            <td class="text-truncate text-left">
                                                <?php echo e(strlen($ship['title']) < 20 ? $ship['title'] : 'Standard Shipping'); ?>

                                            </td>
                                            <td class="text-truncate text-right"><span
                                                    style="float:right"><?php echo e($order_currency . ' ' . number_format($ship['price'], 2)); ?></span>
                                            </td>
                                        </tr>
                                        <?php $total_shipping = $ship['price']; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(!empty($order['shipping_lines']) && count($order['shipping_lines']) > 0): ?>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <tr>
                                    <td class="text-truncate text-left text-bold">TOTAL AMOUNT</td>
                                    <td class="text-truncate text-left"></td>
                                    <td class="text-truncate text-right text-bold"><span
                                            style="float:right"><?php echo e($order_currency . ' '); ?><?php echo e(number_format($order['total_price'], 2)); ?></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </form>
            </div>
            <?php echo $__env->make('modals.fulfill_item', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Shipping Address</h5>
                        <div class="alert alert-light" role="alert">

                            <p>
                                <?php echo e(isset($shipping_address['name']) ? $shipping_address['name'] : ''); ?> <br>
                                <?php echo e(isset($shipping_address['phone']) ? $shipping_address['phone'] : ''); ?> <br>
                                <?php echo e(isset($shipping_address['address1']) ? $shipping_address['address1'] : ''); ?> <br>
                                <?php echo e(isset($shipping_address['address2']) ? $shipping_address['address2'] : ''); ?> <br>
                                <?php echo e(isset($shipping_address['province']) ? $shipping_address['province'] : ''); ?>

                                <?php echo e(isset($shipping_address['city']) ? $shipping_address['city'] : ''); ?> <br>
                                <?php echo e(isset($shipping_address['country']) ? $shipping_address['country'] : ''); ?>

                                <?php echo e(isset($shipping_address['zip']) ? $shipping_address['zip'] : ''); ?>

                            </p>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Billing Address</h5>
                        <div class="alert alert-light" role="alert">
                            <p>
                                <?php echo e($order['billing_address']['name'] ?? ''); ?> <br>
                                <?php echo e($order['billing_address']['phone'] ?? ''); ?> <br>
                                <?php echo e($order['billing_address']['address1'] ?? ''); ?> <br>
                                <?php echo e($order['billing_address']['address2'] ?? ''); ?> <br>
                                <?php echo e($order['billing_address']['province'] ?? ''); ?>

                                <?php echo e($order['billing_address']['city'] ?? ''); ?>

                                <br>
                                <?php echo e($order['billing_address']['country'] ?? ''); ?>

                                <?php echo e($order['billing_address']['zip'] ?? ''); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(document).ready(function() {
            // $('.actions').change(function () {
            //     var val = $(this).val();
            //     if(val == 'fulfill_items') {
            //         $('.items_card').removeClass('col-lg-8').addClass('col-lg-12');
            //         $('.fulfill-th').css({'display':'block'});
            //         $('.fulfill-td').css({'display':'block'});
            //     }
            // });

            $('.fulfill_this_item').click(function() {
                var lineItemId = $(this).data('line_item_id');
                $('.fulfill_submit').css({
                    'display': 'block'
                });
                $('.fulfill_loading').css({
                    'display': 'none'
                });
                $('#lineItemId').val(parseInt(lineItemId));
                var qty = parseInt($(this).data('qty'));
                var select_html = '';
                for (var i = 1; i <= qty; i++) {
                    select_html += "<option value=" + i + ">" + i + "</option>";
                }
                $('#no_of_packages').html(select_html);
                $('.fulfillment_form').find('input:text').val('');
                $('.fulfillment_form').find('input:checkbox').prop('checked', false);
                $('#fulfill_items_modal').modal('show');
            });

            $('.fulfill_submit').click(function(e) {
                e.preventDefault();
                $(this).attr('disabled', true);
                $('.fulfill_submit').css({
                    'display': 'none'
                });
                $('.fulfill_loading').removeAttr('style');
                var data = {};
                $('.fulfillment_form').find('[id]').each(function(i, v) {
                    var input = $(this); // resolves to current input element.
                    data[input.attr('id')] = input.val();
                });
                data['order_id'] = $('#order_id').val();
                data['lineItemId'] = $('#lineItemId').val();
                data['notify_customer'] = $('#notify_customer').prop('checked') ? 'on' : 'off';
                $.ajax({
                    type: 'POST',
                    url: "<?php echo e(route('shopify.order.fulfill')); ?>",
                    data: data,
                    async: false,
                    success: function(response) {
                        console.log(response);
                        //window.top.location.reload();
                    }
                });
            });
        });
        $('form#prepare_orders_store').submit(function() {
            $(this).find(':input[type=submit]').prop('disabled', true);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/preparation/review.blade.php ENDPATH**/ ?>