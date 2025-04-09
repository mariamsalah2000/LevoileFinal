

<?php $__env->startSection('content'); ?>
    <div class="pagetitle">
        <div class="row">
            <div class="col-12">
                <div class="col-9">
                    <h1>Confirm Return <?php echo e($return->return_number); ?></h1>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo e(route('shopify.orders')); ?>">Orders</a></li>
                            <li class="breadcrumb-item">Return <?php echo e($return->return_number ?? ''); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="card">
                <form action="<?php echo e(route('returns.confirm.post')); ?>" method="POST" id="prepare_orders_store">
                    <?php echo csrf_field(); ?>
                    <div class="card-body text-center">
                        <h5 class="card-title">Items</h5>
                        <input type="hidden" id="return_id" name="return_id" value="<?php echo e($return['id']); ?>">
                        <input type="hidden" id="order_number" name="order_number" value="<?php echo e($return['order_number']); ?>">
                        <?php if($details): ?>
                            <div class="row">
                                <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-3 mb-2">
                                        <?php
                                            $item = $detail->product;
                                        ?>
                                        <?php if(isset($item)): ?>
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
                                                <br>
                                                <strong><?php echo e($item['product_name']); ?></strong><br>
                                                <small><strong>Return Qty :</strong> <?php echo e($detail['qty']); ?></small>
                                                <br>
                                                <small><strong>Variation : </strong><?php echo e($item['variation_id']); ?></small>
                                                <br>
                                                <small><strong>Return Amount :
                                                    </strong><?php echo e($detail['amount'] ?? ''); ?></small><br>
                                                <small><strong>Sku : </strong><?php echo e($item['product_sku'] ?? ''); ?></small><br>
                                                <small><strong>Return Reason :
                                                    </strong><?php echo e($detail['reason'] ?? ''); ?></small><br>

                                            </div>

                                            <div>
                                                <input type="hidden" name="detail_id[]" value="<?php echo e($detail->id); ?>">
                                                <?php if($detail['status'] == 'received'): ?>
                                                    <span class="badge bg-success mb-1">Received</span>
                                                    <input name = "status[]" type="hidden" value="received">
                                                <?php else: ?>
                                                    <select name="status[]" class="form-select"
                                                        aria-label="Default select example" required>
                                                        <option value=" ">Choose Return Status ...
                                                        </option>
                                                        <option value="received">
                                                            Received</option>
                                                        <option value="not_received"
                                                            <?php if($item['status'] == 'not_received'): ?> <?php endif; ?>>
                                                            Not Received</option>
                                                        <option value="wrong_item"
                                                            <?php if($item['status'] == 'wront_item'): ?> <?php endif; ?>>
                                                            Wrong Item</option>
                                                    </select>
                                                    <?php $__errorArgs = ['status.*'];
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
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>
                        <?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(document).ready(function() {

            $('form#prepare_orders_store').submit(function() {
                $(this).find(':input[type=submit]').prop('disabled', true);
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/returns/details.blade.php ENDPATH**/ ?>