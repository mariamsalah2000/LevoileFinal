
<?php $__env->startSection('content'); ?>
    <div class="pagetitle">
        <div class="row">
            <div class="col-8">
                <h1>Inventory Transfers</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
                        <li class="breadcrumb-item">Transfers</li>
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
                                    <h6 class="d-inline-block pt-10px"><?php echo e('Choose Transfer Date'); ?></h6>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group mb-0">
                                        <input type="date" class="form-control" value="<?php echo e($date); ?>"
                                            name="date" value="date" placeholder="<?php echo e('Filter by date'); ?>"
                                            data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true"
                                            autocomplete="off">
                                    </div>
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
                                        <th>Transfer Date</th>
                                        <th>Transfer Reference</th>
                                        <th data-breakpoints="md">CreatedBy</th>
                                        <th data-breakpoints="md">Items</th>
                                        <th data-breakpoints="md">Quantity</th>
                                        <th data-breakpoints="md">Note</th>
                                        <th class="text-right" width="15%">options</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php $__currentLoopData = $transfers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $transfer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $get_user_name = \App\Models\User::find($transfer->user_id)->name;
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="form-group">
                                                    <div class="aiz-checkbox-inline">
                                                        <label class="aiz-checkbox">
                                                            <input type="checkbox" class="check-one" name="id[]"
                                                                value="<?php echo e($transfer->id); ?>">
                                                            <span class="aiz-square-check"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php echo e(date('d/m/Y', strtotime($transfer->created_at))); ?>

                                            </td>
                                            <td>
                                                <a class="btn-link"
                                                    href="<?php echo e(route('inventories.show', $transfer->id)); ?>"><?php echo e($transfer->ref); ?></a>
                                            </td>
                                            <td>
                                                <?php echo e($get_user_name); ?>

                                            </td>
                                            <td>
                                                <?php echo e($transfer->items); ?>

                                            </td>
                                            <td>
                                                <?php echo e($transfer->qty); ?>

                                            </td>
                                            <td>
                                                <?php echo e($transfer->note); ?>

                                            </td>
                                            <td class="text-right">
                                                <a class="btn btn-danger" download href="<?php echo e($transfer->sheet); ?>"
                                                    title="Download">
                                                    <i class="bi bi-wallet"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>

                            <div class="aiz-pagination">
                                <?php echo e($transfers->appends(request()->input())->links()); ?>

                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script type="text/javascript" src="<?php echo asset('vendor/jquery/jquery.min.js'); ?>"></script>
    <script type="text/javascript">
        $("ul#warehouse").siblings('a').attr('aria-expanded', 'true');
        $("ul#warehouse").addClass("show");
        $("#transfers").addClass("active");
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/transfers/index.blade.php ENDPATH**/ ?>