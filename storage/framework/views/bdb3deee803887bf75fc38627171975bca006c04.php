
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('assets/css/bootstrap-rtl.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="pagetitle">
        <div class="row">
            <div class="col-9">
                <h1>Staff Report</h1>
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

                    <form class="" action="<?php echo e(route('reports.staff')); ?>" id="sort_orders" method="GET">
                        <div class="card-header row gutters-5">
                            <div class="row col-12">
                                <div class="col-1">
                                    <h6 class="d-inline-block pt-10px"><?php echo e('Choose Order Date'); ?></h6>
                                </div>
                                <div class="col-4 justify-content-center">

                                    <div class="container">
                                        <input type="text" value="<?php echo e($daterange); ?>" id="daterange" name="daterange"
                                            class="form-control">
                                    </div>

                                </div>
                                <div class="col-2">
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-primary" name="action"
                                            value="filter"><?php echo e('Filter'); ?></button>
                                    </div>
                                </div>

                                <div class="col-2">

                                </div>

                                <div class="col-3">
                                    <button type="submit" style="float: right" class="btn btn-danger" name="action"
                                        value="export">Export
                                        Report</button>
                                </div>

                            </div>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Staff Report</h5>

                            <table class="table datatable" id="example">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">Staff Name</th>
                                        <th scope="col" class="text-center">Total Orders</th>
                                        <th scope="col" class="text-center">New Orders</th>
                                        <th scope="col" class="text-center">Prepared Orders</th>
                                        <th scope="col" class="text-center">Hold Orders</th>
                                        <th scope="col" class="text-center">Fulfilled Orders</th>
                                        <th scope="col" class="text-center">Shipped Orders</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($all_prepares)): ?>
                                        <?php $__currentLoopData = $prepare_users_list['name']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?php echo e($prepare_users_list['name'][$key]); ?>

                                                </td>
                                                <td class="text-center">
                                                    <a style="text-decoration: none"
                                                        href="<?php echo e(url('/all-orders?daterange=' . $daterange . '&prepare_emp=' . $prepare_users_list['id'][$key])); ?>">
                                                        <span class="badge badge-inline badge-secondary">
                                                            <?php echo e($prepare_users_list['all'][$key]); ?>

                                                        </span>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <a style="text-decoration: none"
                                                        href="<?php echo e(url('/all-orders?delivery_status=distributed&daterange=' . $daterange . '&prepare_emp=' . $prepare_users_list['id'][$key])); ?>">
                                                        <span class="badge badge-inline badge-info">
                                                            <?php echo e($prepare_users_list['new'][$key]); ?>

                                                        </span>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <a style="text-decoration: none"
                                                        href="<?php echo e(url('/all-orders?delivery_status=prepared&daterange=' . $daterange . '&prepare_emp=' . $prepare_users_list['id'][$key])); ?>">
                                                        <span class="badge badge-inline badge-success">
                                                            <?php echo e($prepare_users_list['prepared'][$key]); ?>

                                                        </span>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <a style="text-decoration: none"
                                                        href="<?php echo e(url('/all-orders?delivery_status=hold&daterange=' . $daterange . '&prepare_emp=' . $prepare_users_list['id'][$key])); ?>">
                                                        <span class="badge badge-inline badge-warning">
                                                            <?php echo e($prepare_users_list['hold'][$key]); ?>

                                                        </span>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <a style="text-decoration: none"
                                                        href="<?php echo e(url('/all-orders?delivery_status=fulfilled&daterange=' . $daterange . '&prepare_emp=' . $prepare_users_list['id'][$key])); ?>">
                                                        <span class="badge badge-inline badge-primary">
                                                            <?php echo e($prepare_users_list['fulfilled'][$key]); ?>

                                                        </span>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <a style="text-decoration: none"
                                                        href="<?php echo e(url('/all-orders?delivery_status=shipped&daterange=' . $daterange . '&prepare_emp=' . $prepare_users_list['id'][$key])); ?>">
                                                        <span class="badge badge-inline badge-dark">
                                                            <?php echo e($prepare_users_list['shipped'][$key]); ?>

                                                        </span>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>

                                    <tr>
                                        <th class="text-center"><?php echo e(count($prepare_users_list['name'])); ?></th>
                                        <th class="text-center"><?php echo e(array_sum($prepare_users_list['all'])); ?>

                                        </th>
                                        <th class="text-center"><?php echo e(array_sum($prepare_users_list['new'])); ?>

                                        </th>
                                        <th class="text-center"><?php echo e(array_sum($prepare_users_list['prepared'])); ?>

                                        </th>
                                        <th class="text-center"><?php echo e(array_sum($prepare_users_list['hold'])); ?>

                                        </th>
                                        <th class="text-center"><?php echo e(array_sum($prepare_users_list['fulfilled'])); ?>

                                        </th>
                                        <th class="text-center"><?php echo e(array_sum($prepare_users_list['shipped'])); ?>

                                        </th>
                                    </tr>


                                </tfoot>
                            </table>
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
        $("ul#reports").siblings('a').attr('aria-expanded', 'true');
        $("ul#reports").addClass("show");
        $("#staff").addClass("active");

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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/reports/staff.blade.php ENDPATH**/ ?>