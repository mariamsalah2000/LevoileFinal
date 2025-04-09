

<?php $__env->startSection('content'); ?>
    <div class="pagetitle">
        <h1>Dashboard</h1>
    </div><!-- End Page Title -->

    <?php if(auth()->user()->role_id != 8 && auth()->user()->role_id != 7): ?>
    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">

                    <!-- Preparation Reports -->
                    <div class="col-12">
                        <div class="card">

                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>

                                    <li><a class="dropdown-item" href="<?php echo e(route('home', ['filter=today'])); ?>">Today</a>
                                    </li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('home', ['filter=month'])); ?>">This
                                            Month</a>
                                    </li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('home', ['filter=year'])); ?>">This Year</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Preparation Reports</h5>

                                <table class="table table-borderless datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Staff Name</th>
                                            <th scope="col" class="text-center">Total Orders</th>
                                            <th scope="col" class="text-center">Hold Orders</th>
                                            <th scope="col" class="text-center">Fulfilled Orders</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($reports['name'])): ?>
                                            <?php $__currentLoopData = $reports['name']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <?php echo e($reports['name'][$key]); ?>

                                                    </td>
                                                    <td class="text-center">

                                                        <?php echo e($reports['all'][$key]); ?>


                                                    </td>
                                                    <td class="text-center">

                                                        <?php echo e($reports['hold'][$key]); ?>


                                                    </td>
                                                    <td class="text-center">

                                                        <?php echo e($reports['fulfilled'][$key]); ?>


                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>

                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Preparation Reports -->

                    <!-- Moderation Reports -->
                    <div class="col-12">
                        <div class="card">

                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>

                                    <li><a class="dropdown-item" href="<?php echo e(route('home', ['filter=today'])); ?>">Today</a>
                                    </li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('home', ['filter=month'])); ?>">This
                                            Month</a>
                                    </li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('home', ['filter=year'])); ?>">This Year</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Moderation Reports</h5>

                                <table class="table table-borderless datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Moderator Name</th>
                                            <th scope="col" class="text-center">Total Orders</th>
                                            <th scope="col" class="text-center">Facebook Orders</th>
                                            <th scope="col" class="text-center">Instagram Orders</th>
                                            <th scope="col" class="text-center">Whatsapp Orders</th>
                                            <th scope="col" class="text-center">Cancelled Orders</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(isset($reports2['name'])): ?>
                                            <?php $__currentLoopData = $reports2['name']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <?php echo e($reports2['name'][$key]); ?>

                                                    </td>
                                                    <td class="text-center">

                                                        <?php echo e($reports2['all'][$key]); ?>


                                                    </td>
                                                    <td class="text-center">

                                                        <?php echo e($reports2['facebook'][$key]); ?>


                                                    </td>
                                                    <td class="text-center">

                                                        <?php echo e($reports2['instagram'][$key]); ?>


                                                    </td>
                                                    <td class="text-center">

                                                        <?php echo e($reports2['whatsapp'][$key]); ?>


                                                    </td>
                                                    <td class="text-center">

                                                        <?php echo e($reports2['cancelled'][$key]); ?>


                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>

                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Moderation Reports -->

                </div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">

                <!-- Recent Activity -->
                <div class="card">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>

                            <li><a class="dropdown-item" href="<?php echo e(route('home', ['filter=today'])); ?>">Today</a>
                            </li>
                            <li><a class="dropdown-item" href="<?php echo e(route('home', ['filter=month'])); ?>">This
                                    Month</a>
                            </li>
                            <li><a class="dropdown-item" href="<?php echo e(route('home', ['filter=year'])); ?>">This
                                    Year</a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Recent Activity</h5>

                        <div class="activity">

                            <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $start = Carbon\Carbon::parse($activity->created_at);
                                    $end = now();
                                    $options = [
                                        'join' => ', ',
                                        'parts' => 1,
                                        'syntax' => Carbon\CarbonInterface::DIFF_ABSOLUTE,
                                    ];

                                    $duration = $end->diffForHumans($start, $options);
                                    //
                                ?>
                                <div class="activity-item d-flex">
                                    <div class="activite-label"><?php echo e($duration); ?></div>
                                    <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                    <div class="activity-content">
                                        <?php echo $activity->note; ?>

                                    </div>
                                </div><!-- End activity item-->
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>

                    </div>
                </div><!-- End Recent Activity -->
            </div><!-- End Right side columns -->

        </div>
    </section>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/home.blade.php ENDPATH**/ ?>