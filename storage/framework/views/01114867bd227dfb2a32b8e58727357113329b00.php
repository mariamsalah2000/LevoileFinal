<div class="aiz-pos-cart-list mb-4 mt-3 c-scrollbar-light">
    <?php
        $subtotal = 0;
        $tax = 0;
    ?>
    <?php if(Session::has('pos.cart')): ?>
        <ul class="list-group list-group-flush">
            <?php $__empty_1 = true; $__currentLoopData = Session::get('pos.cart'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cartItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $subtotal += $cartItem['price'] * $cartItem['quantity'];
                    $tax += $cartItem['tax'] * $cartItem['quantity'];
                    $stock = \App\Models\ProductVariant::where('sku', $cartItem['stock_id'])->first();
                ?>
                <li class="list-group-item py-0 pl-2">
                    <div class="row gutters-5 align-items-center">
                        <div class="col-2">
                            <img src="<?php echo e($cartItem['image']); ?>" width="80px" height="80px">
                        </div>
                        <div class="col-2">
                            <div class="row no-gutters align-items-center flex-column aiz-plus-minus">
                                <button class="btn col-auto btn-icon btn-sm fs-15 add-pluss" type="button"
                                    data-type="plus" data-stock-id="<?php echo e($cartItem['stock_id']); ?>"
                                    data-field="qty-<?php echo e($key); ?>">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <input type="text" name="qty-<?php echo e($key); ?>" id="qty-<?php echo e($key); ?>"
                                    class="col border-0 text-center flex-grow-1 fs-16 input-number" placeholder="1"
                                    value="<?php echo e($cartItem['quantity']); ?>" min="<?php echo e(1); ?>"
                                    max="<?php echo e($stock->inventory_quantity); ?>"
                                    onchange="updateQuantity(<?php echo e($key); ?>)">
                                <button class="btn col-auto btn-icon btn-sm fs-15 add-minus" type="button"
                                    data-type="minus" data-stock-id="<?php echo e($cartItem['stock_id']); ?>"
                                    data-field="qty-<?php echo e($key); ?>">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="text-truncate-2"><?php echo e($stock->product->title); ?></div>
                            <span
                                class="span badge badge-inline fs-12 badge-soft-secondary"><?php echo e($cartItem['variant']); ?></span>
                        </div>
                        <div class="col-2">
                            <div class="fs-12 opacity-60"><?php echo e($cartItem['price']); ?> x <?php echo e($cartItem['quantity']); ?>

                            </div>
                            <div class="fs-15 fw-600"><?php echo e($cartItem['price'] * $cartItem['quantity']); ?> LE</div>
                        </div>
                        <div class="col-1">
                            <button type="button" class="btn btn-circle btn-icon btn-sm btn-soft-danger ml-2 mr-0"
                                onclick="removeFromCart(<?php echo e($key); ?>)">
                                <i class="fa fa-trash"></i>
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
        <textarea name="note" class="form-control" placeholder="Add Note"><?php echo e(isset($request) ? $request->note : ''); ?></textarea>
    </div>
</div>

<br>
<hr>
<?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/stock_requests/cart.blade.php ENDPATH**/ ?>