
<?php $__env->startSection('content'); ?>
    <div class="pagetitle">
        <div class="row">
            <div class="col-8">
                <h1>Orders Pickups</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
                        <li class="breadcrumb-item">Pickups</li>
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


                            </div>
                        </div>
                        <div class="card-header row gutters-5">
                            <div class="row col-12">
                                <div class="col-2">
                                    <h6 class="d-inline-block pt-10px"><?php echo e('Choose Pickup Date'); ?></h6>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group mb-0">
                                        <input type="date" class="form-control" value="<?php echo e($date); ?>"
                                            name="date" value="date" placeholder="<?php echo e('Filter by date'); ?>"
                                            data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <select class="form-select aiz-selectpicker" name="shipping" id="branch_id">
                                        <option value="0">Choose Shipping Company</option>
                                        <option value="1" <?php if($shipping == 1): ?> selected <?php endif; ?>>BestExpress
                                        </option>
                                        <option value="2" <?php if($shipping == 2): ?> selected <?php endif; ?>>Sprint
                                        </option>
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <table class="table aiz-table mb-0">
                                <thead>
                                    <tr>
                                        <!--<th>#</th>-->
                                        <th>
                                            <div class="form-group">
                                                <div class="aiz-checkbox-inline">
                                                    <label class="aiz-checkbox">
                                                        <input type="checkbox" class="check-all">
                                                        <span class="aiz-square-check"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </th>
                                        <th>#</th>
                                        <th>Order Date</th>
                                        <th>Order Reference</th>
                                        <th data-breakpoints="md">CreatedBy</th>
                                        <th data-breakpoints="md">Shipping Company</th>
                                        <th data-breakpoints="md">Count</th>
                                        <th data-breakpoints="md">Order Status</th>
                                        <th data-breakpoints="md">Downloaded At</th>
                                        <th class="text-right" width="15%">options</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php $__currentLoopData = $pickups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $pickup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $get_user_name = \App\Models\User::find($pickup->user_id)->name;
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="form-group">
                                                    <div class="aiz-checkbox-inline">
                                                        <label class="aiz-checkbox">
                                                            <input type="checkbox" class="check-one" name="id[]"
                                                                value="<?php echo e($pickup->id); ?>">
                                                            <span class="aiz-square-check"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php echo e($key + 1); ?>

                                            </td>
                                            <td>
                                                <?php echo e(date('d/m/Y', strtotime($pickup->created_at))); ?>

                                            </td>
                                            <td>
                                                <?php echo e($pickup->pickup_id); ?>

                                            </td>
                                            <td>
                                                <?php echo e($get_user_name); ?>

                                            </td>
                                            <td>
                                                <?php if($pickup->company_id == 1): ?>
                                                    <span>BestExpress</span>
                                                <?php else: ?>
                                                    <span>Sprint</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php echo e($pickup->shipment_count); ?>

                                            </td>
                                            <td>
                                                <span class="">Ordered</span>
                                            </td>
                                            <td>
                                                Finance : <?php echo e($pickup->downloaded_at_finance ? date('d/m/Y', strtotime($pickup->downloaded_at_finance)) : '-'); ?>

                                                <br>
                                                Shipping : <?php echo e($pickup->downloaded_at_shipping ? date('d/m/Y', strtotime($pickup->downloaded_at_shipping)) : '-'); ?>

                                                <br>
                                                Prepare : <?php echo e($pickup->downloaded_at_prepare ? date('d/m/Y', strtotime($pickup->downloaded_at_prepare)) : '-'); ?>

                                            </td>
                                            <td class="text-right">
                                                <a class="btn btn-primary"
                                                    href="<?php echo e(route('downloads', $pickup->pickup_id)); ?>"
                                                    title="Download">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>

                            <div class="aiz-pagination">
                                <?php echo e($pickups->appends(request()->input())->links()); ?>

                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script type="text/javascript">
        $("ul#pickups").siblings('a').attr('aria-expanded', 'true');
        $("ul#pickups").addClass("show");
        $("ul#pickups #index").addClass("active");

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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/pickups/index.blade.php ENDPATH**/ ?>