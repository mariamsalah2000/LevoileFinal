
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('assets/css/bootstrap-rtl.min.css')); ?>" rel="stylesheet">
    <style>
        .pagination {
            font-size: 14px;
            /* Adjust this size to make the links smaller */
        }

        .page-link {
            padding: 0.25rem 0.75rem;
            /* Control padding for smaller buttons */
            font-size: 0.875rem;
            /* Smaller font size */
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="pagetitle">
        <div class="row">

            <div class="col-8">
                <h1>Shortage Products Orders</h1>
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
                            <div class="row col-12">

                                <div class="col-sm-3">
                                    <div class="form-group mb-0">
                                        <label for="date">Filter By Order Date</label>
                                        <input type="date" class="form-control" value="<?php echo e($date); ?>"
                                            name="date" placeholder="<?php echo e('Filter by order date'); ?>" data-format="DD-MM-Y"
                                            data-separator=" to " data-advanced-range="true" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group mb-0">
                                        <label for="date">Filter By Hold Date</label>
                                        <input type="date" class="form-control" value="<?php echo e($hold_date); ?>"
                                            name="hold_date" placeholder="<?php echo e('Filter by hold date'); ?>" data-format="DD-MM-Y"
                                            data-separator=" to " data-advanced-range="true" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group mb-0">
                                        <label for="search">Search Product</label>
                                        <input type="text" class="form-control" value="<?php echo e($search); ?>"
                                            name="search" placeholder="<?php echo e('Search For Product'); ?>">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group mb-0 mt-4">
                                        <button type="submit" name="button" value="filter"
                                            class="btn btn-primary"><?php echo e('Filter'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-header row gutters-5">
                            <div class="row col-12">
                                <div class="col-sm-3">
                                    <h6 class="d-inline-block pt-10px">Total Orders</h6>
                                </div>
                                <div class="col-sm-4">
                                    <h6 class="d-inline-block pt-10px"><?php echo e($orders_count); ?> Orders</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Shortage Products Orders</h5>
                            

                            <!-- Table with stripped rows -->
                            <table class="table datatable" id="example">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">Order Date</th>
                                        <th scope="col" class="text-center">Hold Date</th>
                                        <th scope="col" class="text-center">Total Items</th>
                                        <th scope="col" class="text-center">Shortage Items</th>
                                        <th scope="col" class="text-center">Total Price</th>
                                        <th scope="col" class="text-center">Shortage Price</th>
                                        <th scope="col" class="text-center">Assigned To</th>
                                        <th scope="col" class="text-center">Status</th>
                                        <th scope="col" class="text-center">Trials</th>
                                        <th scope="col" class="text-center">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($orders)): ?>
                                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($order): ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <?php echo e(date('Y-m-d h:i:s', strtotime($order->created_at))); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e(date('Y-m-d h:i:s', strtotime($order->hold_date))); ?></td>
                                                    <td class="text-center"><?php echo e($order->total_items); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e($order->shortage_items); ?>

                                                    </td>
                                                    <td class="text-center"><?php echo e($order->total_price); ?></td>
                                                    <td class="text-center">
                                                        <?php echo e($order->shortage_price); ?>

                                                    </td>
                                                    <td class="text-center"><?php echo e($order->user->name); ?></td>
                                                    <td class="text-center"><?php echo e($order->status); ?></td>
                                                    <td class="text-center"><?php echo e($order->trial); ?></td>
                                                    <td class="text-center">
                                                        <div class="row justify-content-center">
                                                            <div class="col-3 mr-2 ml-2">
                                                                <div class="row  mb-1">
                                                                    <a class="btn btn-primary"
                                                                        href="<?php echo e(route('shortage.make_call', $order->id)); ?>"
                                                                        title="Make a Call">
                                                                        <i class="bi bi-telephone"></i>
                                                                    </a>
                                                                    Make a Call
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </td>

                                                </tr>
                                            <?php endif; ?>
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

                            <!-- End Table with stripped rows -->

                        </div>

                    </form>
                    <div class="row">
                        <div class="col-12">
                            <nav>
                                <ul class="pagination pagination-sm">
                                    <?php echo e($orders->links()); ?>

                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script type="text/javascript" src="<?php echo asset('vendor/jquery/jquery.min.js'); ?>"></script>
    <script type="text/javascript">
        $("ul#reports").siblings('a').attr('aria-expanded', 'true');
        $("ul#reports").addClass("show");
        $("#shortage_report").addClass("active");
        $(document).on("change", ".check-all", function() {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/reports/shortage_products.blade.php ENDPATH**/ ?>