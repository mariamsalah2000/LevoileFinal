<?php
    $total_shipping = 0;
    foreach ($order['shipping_lines'] as $ship) {
        $total_shipping = $ship['price'];
    }
?>
<tr>
    <td>
        <div class="form-group">
            <div class="aiz-checkbox-inline">
                <label class="aiz-checkbox">
                    <input type="checkbox" class="check-one" name="id[]" value="<?php echo e($order->id); ?>">
                    <span class="aiz-square-check"></span>
                </label>
            </div>
        </div>
    </td>
    <td><a class="btn-link"
            href="<?php echo e(route('shopify.order.prepare', $order->order_number)); ?>">Lvs-<?php echo e($order->order_number); ?></a>
    </td>
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

    <td><?php echo e(is_array($shipping_address) && isset($shipping_address['name']) ? $shipping_address['name'] : ''); ?>

    </td>
    <td class="text-center"><?php echo e($order->getPaymentStatus()); ?></td>
    <td class="text-center"><?php echo e($order->subtotal_price); ?></td>
    <td class="text-center"><?php echo e($total_shipping); ?></td>
    <td class="text-center"><?php echo e($order->total_price); ?></td>
    <td><?php echo e(is_array($shipping_address) && isset($shipping_address['phone']) ? $shipping_address['phone'] : ''); ?>

    </td>
    <td>
        <?php if($order->fulfillment_status == 'processing'): ?>
            <span class="badge badge-inline badge-danger"><?php echo e($order->fulfillment_status); ?></span>
        <?php elseif($order->fulfillment_status == 'distributed'): ?>
            <span class="badge badge-inline badge-info"><?php echo e($order->fulfillment_status); ?></span>
        <?php elseif($order->fulfillment_status == 'prepared'): ?>
            <span class="badge badge-inline badge-success"><?php echo e($order->fulfillment_status); ?></span>
        <?php elseif($order->fulfillment_status == 'shipped'): ?>
            <span class="badge badge-inline badge-primary"><?php echo e($order->fulfillment_status); ?></span>
        <?php elseif($order->fulfillment_status == 'hold'): ?>
            <span class="badge badge-inline badge-warning"><?php echo e($order->fulfillment_status); ?></span>
        <?php elseif($order->fulfillment_status == 'reviewed'): ?>
            <span class="badge badge-inline badge-secondary"><?php echo e($order->fulfillment_status); ?></span>
        <?php elseif($order->fulfillment_status == 'cancelled'): ?>
            <span class="badge badge-inline badge-danger"><?php echo e($order->fulfillment_status); ?></span>
        <?php elseif($order->fulfillment_status == 'fulfilled'): ?>
            <span class="badge badge-inline badge-dark"><?php echo e($order->fulfillment_status); ?></span>
        <?php endif; ?>
    </td>
    <td><?php echo e(date('Y-m-d h:i:s', strtotime($order->created_at_date))); ?></td>
    <td>
        <div class="row">
            <?php if($order->fulfillment_status == 'prepared'): ?>
                <div class="col-5">
                    <div class="row">
                        <a class="btn btn-primary" href="<?php echo e(route('prepare.review', $order->id)); ?>"
                            title="Review Order">
                            <i class="bi bi-check-lg"></i>
                        </a>
                        Review
                    </div>
                </div>
                <div class="col-1"></div>
            <?php elseif($order->fulfillment_status == 'fulfilled'): ?>
                <div class="col-5  mr-2 ml-2">
                    <div class="row mb-1">
                        <a class="btn btn-dark" href="<?php echo e(route('prepare.generate-invoice', $order->id)); ?>"
                            title="Generate Invoice">
                            <i class="bi bi-printer"></i>
                        </a>
                        Invoice
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </td>
</tr>
<?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/preparation/reviewed_template.blade.php ENDPATH**/ ?>