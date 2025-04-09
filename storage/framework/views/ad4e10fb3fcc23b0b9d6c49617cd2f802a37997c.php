

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Reason Report</h1>


    <!-- Date Range Filter -->
    <div class="mb-5">
        <form action="<?php echo e(route('reason.report')); ?>" method="GET">
            <div class="row">
                <div class="col-md-4">
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo e(request('start_date')); ?>">
                </div>
                <div class="col-md-4">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo e(request('end_date')); ?>">
                </div>
                <div class="col-md-4">
                    <label>&nbsp;</label> <!-- Empty label for spacing -->
                    <button type="submit" class="btn btn-primary form-control">Filter</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Table to show reasons and counts -->
    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>Reason</th>
                <th>Count</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $reasonCounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reason => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e(ucfirst(str_replace('_', ' ', $reason))); ?></td>
                <td><?php echo e($count); ?></td>
                <td>
                    <a href="<?php echo e(route('tickets.showReason', $reason)); ?>" class="btn btn-primary">Show</a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$("ul#ticket").siblings('a').attr('aria-expanded', 'true');
$("ul#ticket").addClass("show");
$("#reason_report").addClass("active");
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/tickets/reason.blade.php ENDPATH**/ ?>