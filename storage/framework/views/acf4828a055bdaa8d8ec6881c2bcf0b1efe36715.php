
<?php $__env->startSection('content'); ?>
    <div class="pagetitle">
        <div class="row">
            <div class="col-8">
                <h1>Orders</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
                        <li class="breadcrumb-item">New Assigned Orders</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header">
                        <h1 class="h2 fs-16 mb-0">Order Details</h1>
                    </div>
                    <div class="card-body">
                        <div class="row gutters-5">
                            <?php
                                $delivery_status = $order->fulfillment_status;
                                $payment_status = $order->payment_status;
                                $payment_type = $order->payment_status;
                                $admin_user_id = App\Models\User::where('role_id', 1)->first()->id;
                            ?>

                        </div>
                        <div class="mb-4 m-2">

                        </div>
                        <div class="row gutters-5">
                            <div class="col text-md-left text-center">
                                <?php if($order->shipping_address): ?>
                                    <address>
                                        <strong class="text-main">
                                            <?php echo e($order->shipping_address['name']); ?>

                                        </strong><br>
                                        <?php echo e($order->email); ?><br>
                                        <?php echo e($order->shipping_address['phone']); ?><br>
                                        <?php echo e($order->shipping_address['address1']); ?>, <?php echo e($order->shipping_address['city']); ?>,

                                        <?php echo e($order->shipping_address['country']); ?>

                                    </address>
                                <?php else: ?>
                                    <address>
                                        <strong class="text-main">
                                            <?php echo e($order->name); ?>

                                        </strong><br>
                                        <?php echo e($order->email); ?><br>
                                        <?php echo e($order->phone); ?><br>
                                    </address>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4 ml-auto">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="text-main text-bold">Order #</td>
                                            <td class="text-info text-bold text-right"> <?php echo e($order->id); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-main text-bold">Order Status</td>
                                            <td class="text-right">
                                                <?php if($delivery_status == 'delivered'): ?>
                                                    <span class="badge badge-inline badge-success">
                                                        <?php echo e(ucfirst(str_replace('_', ' ', $delivery_status))); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge badge-inline badge-info">
                                                        <?php echo e(ucfirst(str_replace('_', ' ', $delivery_status))); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-main text-bold">Order Date </td>
                                            <td class="text-right"><?php echo e($order->created_at_date); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-main text-bold">
                                                Total amount
                                            </td>
                                            <td class="text-right">
                                                <?php echo e($order->total_price); ?>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-main text-bold">Payment method</td>
                                            <td class="text-right">
                                                <?php echo e($order->payment_status); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr class="new-section-sm bord-no">
                        <div class="row">
                            <div class="col-lg-12 table-responsive">
                                <table class="table-bordered aiz-table invoice-summary table">
                                    <thead>
                                        <tr class="bg-trans-dark">
                                            <th>#</th>
                                            <th>Created</th>
                                            <th>Order Number</th>
                                            <th>Action</th>
                                            <th>Action By</th>
                                            <th data-breakpoints="lg" class="min-col">Note</th>
                                            <th data-breakpoints="lg" class="min-col">Item</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $order_history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($key + 1); ?></td>
                                                <td><?php echo e($history->created_at); ?></td>
                                                <td><?php echo e($order->id); ?></td>
                                                <td><?php echo e($history->action); ?></td>
                                                <td><?php echo e($history->user->name); ?></td>
                                                <td><?php echo $history->note; ?></td>
                                                <td><?php echo e($history->item); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>
                        <hr>
                        <h4>Tickets History</h4>
                        <br>
                        <div class="row">
                            <div class="col-lg-12 table-responsive">
                                <table class="table-bordered aiz-table invoice-summary table">
                                    <thead>
                                        <tr class="bg-trans-dark">
                                            <th>#</th>
                                            <th>Created</th>
                                            <th>Order Number</th>
                                            <th>Ticket Number</th>
                                            <th>Action</th>
                                            <th>Action By</th>
                                            <th data-breakpoints="lg" class="min-col">Note</th>
                                            <th data-breakpoints="lg" class="min-col">Item</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $ticket_history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($key + 1); ?></td>
                                                <td><?php echo e($history->created_at); ?></td>
                                                <td><?php echo e($order->id); ?></td>
                                                <td><?php echo e($history->id); ?></td>
                                                <td><?php echo e($history->action); ?></td>
                                                <td><?php echo e($history->user->name); ?></td>
                                                <td><?php echo $history->note; ?></td>
                                                <td><?php echo e($history->item); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/preparation/order_history.blade.php ENDPATH**/ ?>