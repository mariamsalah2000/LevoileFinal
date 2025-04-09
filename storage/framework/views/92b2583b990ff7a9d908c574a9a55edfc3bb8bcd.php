
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('assets/css/bootstrap-rtl.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="pagetitle">
        <div class="row">

            <div class="col-8">
                <h1>Orders</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
                        <li class="breadcrumb-item">Re-Synced Orders</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <form class="" action="" id="sort_orders" method="GET">
                        <div class="card-header row gutters-5">
                            <div class="row col-12">

                                <div class="col-sm-4">
                                    <div class="form-group mb-0">
                                        <input type="date" class="form-control" value="<?php echo e($date); ?>"
                                            name="date" placeholder="<?php echo e('Filter by date'); ?>" data-format="DD-MM-Y"
                                            data-separator=" to " data-advanced-range="true" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control" id="search"
                                            name="search"<?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?>
                                            placeholder="<?php echo e('Type Order code & hit Enter'); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="">
                                        <select class="form-select aiz-selectpicker" name="old_status" id="old_status">
                                            <option value=""><?php echo e('Filter by Delivery Status'); ?></option>
                                            <option value="processing" <?php if($delivery_status == 'processing'): ?> selected <?php endif; ?>>
                                                <?php echo e('Processing'); ?> </option>
                                            <option value="distributed" <?php if($delivery_status == 'distributed'): ?> selected <?php endif; ?>>
                                                <?php echo e('Distributed'); ?> </option>
                                            <option value="prepared" <?php if($delivery_status == 'prepared'): ?> selected <?php endif; ?>>
                                                <?php echo e('Prepared'); ?></option>
                                            <option value="hold" <?php if($delivery_status == 'hold'): ?> selected <?php endif; ?>>
                                                <?php echo e('Hold'); ?></option>
                                            <option value="reviewed" <?php if($delivery_status == 'reviewed'): ?> selected <?php endif; ?>>
                                                <?php echo e('Reviewed'); ?></option>
                                            <option value="shipped" <?php if($delivery_status == 'shipped'): ?> selected <?php endif; ?>>
                                                <?php echo e('Shipped'); ?></option>
                                            <option value="cancelled" <?php if($delivery_status == 'cancelled'): ?> selected <?php endif; ?>>
                                                <?php echo e('Cancel'); ?></option>
                                            <option value="fulfilled" <?php if($delivery_status == 'fulfilled'): ?> selected <?php endif; ?>>
                                                <?php echo e('Fulfilled'); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-primary"><?php echo e('Filter'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Re-Synced Orders</h5>
                            

                            <!-- Table with stripped rows -->
                            <table class="table datatable" id="example">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">Order No</th>
                                        <th scope="col" class="text-center">Resynced By</th>
                                        <th scope="col" class="text-center">Assigned To</th>
                                        <th scope="col" class="text-center">Edit Reason</th>
                                        <th scope="col" class="text-center">Old Total</th>
                                        <th scope="col" class="text-center">New Total</th>
                                        <th scope="col" class="text-center">Old Delivery Status</th>
                                        <th scope="col" class="text-center">Created Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($orders)): ?>
                                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($order): ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <?php echo e($order->order_id); ?>

                                                    </td>
                                                    <td class="text-center"><?php echo e($order->editor->name); ?></td>
                                                    <td class="text-center">
                                                        <?php echo e($order->assignee ? $order->assignee->name : '-'); ?>

                                                    </td>
                                                    <td class="text-center"><?php echo e($order->reason); ?></td>
                                                    <td class="text-center"><?php echo e($order->old_total); ?></td>
                                                    <td class="text-center"><?php echo e($order->new_total); ?></td>
                                                    <td class="text-center">
                                                        <?php if($order->old_status == 'processing'): ?>
                                                            <span
                                                                class="badge badge-inline badge-danger"><?php echo e($order->old_status); ?></span>
                                                        <?php elseif($order->old_status == 'distributed'): ?>
                                                            <span
                                                                class="badge badge-inline badge-info"><?php echo e($order->old_status); ?></span>
                                                        <?php elseif($order->old_status == 'prepared'): ?>
                                                            <span
                                                                class="badge badge-inline badge-success"><?php echo e($order->old_status); ?></span>
                                                        <?php elseif($order->old_status == 'shipped'): ?>
                                                            <span
                                                                class="badge badge-inline badge-primary"><?php echo e($order->old_status); ?></span>
                                                        <?php elseif($order->old_status == 'hold'): ?>
                                                            <span
                                                                class="badge badge-inline badge-warning"><?php echo e($order->old_status); ?></span>
                                                        <?php elseif($order->old_status == 'reviewed'): ?>
                                                            <span
                                                                class="badge badge-inline badge-secondary"><?php echo e($order->old_status); ?></span>
                                                        <?php elseif($order->old_status == 'cancelled'): ?>
                                                            <span
                                                                class="badge badge-inline badge-danger"><?php echo e($order->old_status); ?></span>
                                                        <?php elseif($order->old_status == 'fulfilled'): ?>
                                                            <span
                                                                class="badge badge-inline badge-dark"><?php echo e($order->old_status); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo e(date('Y-m-d h:i:s', strtotime($order->created_at))); ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="text-center pb-2">
                                <?php echo e($orders->links()); ?>

                            </div>
                            <!-- End Table with stripped rows -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script type="text/javascript">
        $("ul#edited").siblings('a').attr('aria-expanded', 'true');
        $("ul#edited").addClass("show");
        $("#resynced").addClass("active");
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/orders/resynced_orders.blade.php ENDPATH**/ ?>