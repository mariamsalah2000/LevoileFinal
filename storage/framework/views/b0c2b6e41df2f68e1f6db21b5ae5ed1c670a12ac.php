
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('assets/css/bootstrap-rtl.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="pagetitle">
        <div class="row">
            <div class="col-8">
                <h1>Cancelled Orders</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
                        <li class="breadcrumb-item">Reports</li>
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
                            <div class="row col-12 justify-content-center">
                                <?php
                                    $reasons = [
                                        'CUSTOMER_REQUEST',
                                        'BROUGHT_FROM_STORE',
                                        'ORDER_LATE',
                                        'WRONG_SHIPPING_INFO',
                                        'REPEATED_ORDER',
                                        'FAKE_ORDER',
                                        'ORDER_CONFIRMED_BY_MISTAKE',
                                        'INVENTORY',
                                        'ORDER_UPDATED_AFTER_SHIPPING',
                                        'OTHER',
                                    ];
                                ?>
                                <div class="col-2 justify-content-center m-2">

                                    <div class="shadow2 justify-content-center text-center"
                                        style="background-color: rgb(166, 166, 164); color:white">
                                        <strong>All Orders</strong>
                                        <br>
                                        <?php echo e($orders_count); ?>

                                    </div>

                                </div>
                                <div class="col-2 justify-content-center m-2">

                                    <div class="shadow2 justify-content-center text-center"
                                        style="background-color: rgb(166, 166, 164); color:white">
                                        <strong>All Amount</strong>
                                        <br>
                                        <?php echo e($orders_amount); ?> LE.
                                    </div>

                                </div>
                                <?php $__currentLoopData = $reasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-2 justify-content-center m-2">

                                        <div class="shadow2 justify-content-center text-center"
                                            style="background-color: rgb(166, 166, 164); color:white">
                                            <strong><?php echo e(ucfirst(str_replace('_', ' ', $status))); ?></strong>
                                            <br>
                                            <?php echo e($orders_new->where('reason', $status)->count()); ?>

                                        </div>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-1"></div>

                            </div>
                        </div>
                        <div class="card-header row gutters-5">
                            <div class="row col-12">
                                <div class="col-4 justify-content-center">

                                    <div class="container">
                                        <label for="daterange">Choose Date Range</label>
                                        <input type="text" value="<?php echo e($daterange); ?>" id="daterange" name="daterange"
                                            class="form-control">
                                    </div>

                                </div>
                                <div class="col-sm-3 justify-content-center">
                                    <div class="form-group mb-0">
                                        <label for="date">Search Order</label>
                                        <input type="text" class="form-control" id="search"
                                            name="search"<?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?>
                                            placeholder="<?php echo e('Type Order Name & Hit Enter'); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-3 justify-content-center">
                                    <div class="">
                                        <label for="date">Filter By Cancel Reason</label>
                                        <select class="form-select aiz-selectpicker" name="reason" id="reason">
                                            <option value="" <?php if($reason == ''): ?> selected <?php endif; ?>>Select
                                            </option>
                                            <?php $__currentLoopData = $reasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($value); ?>"
                                                    <?php if($reason == $value): ?> selected <?php endif; ?>>
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $value))); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-auto justify-content-center">
                                    <div class="form-group mb-0 mt-4">
                                        <button type="submit" class="btn btn-primary"><?php echo e('Filter'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Cancelled Orders</h5>

                            <!-- Table with stripped rows -->
                            <table class="table datatable" id="example">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">Order No.</th>
                                        <th scope="col" class="text-center">Reason</th>
                                        <th scope="col" class="text-center">Cancelled By</th>
                                        <th scope="col" class="text-center">Note</th>
                                        <th scope="col" class="text-center">Cancelled Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($orders)): ?>
                                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="text-center"><a class="btn-link"
                                                        href="<?php echo e(route('shopify.order.show', $order->order->table_id)); ?>">Lvs<?php echo e($order->order->order_number); ?></a>
                                                </td>
                                                <td class="text-center"><?php echo e($order->reason); ?></td>
                                                <td class="text-center"><?php echo e($order->user->name); ?></td>
                                                <td class="text-center"><?php echo e($order->note); ?></td>
                                                <td class="text-center">
                                                    <?php echo e(date('Y-m-d h:i:s', strtotime($order->created_at))); ?>

                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>

                                    <tr>
                                        <th class="text-center"><?php echo e($orders_count); ?></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>


                                </tfoot>
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
    <script type='text/javascript'>
        $("ul#reports").siblings('a').attr('aria-expanded', 'true');
        $("ul#reports").addClass("show");
        $("#cancelled").addClass("active");
        $(document).ready(function() {
            $('#daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'MM/DD/YYYY'
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/reports/cancelled.blade.php ENDPATH**/ ?>