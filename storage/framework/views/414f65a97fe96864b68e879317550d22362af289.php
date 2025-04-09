

<?php $__env->startSection('content'); ?>
<div class="container">

    <!-- Date Range Filter -->
    <div class="mb-5">
        <form action="<?php echo e(route('tickets.report')); ?>" method="GET">
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

    <!-- Scarf Sale Section -->
    <div class="mb-5">
        <h1 class="text-primary">Levoile</h1>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>User Name</th>
                    <th>Number of Tickets Added</th>
                    <th>Open Tickets</th>
                    <th>In Progress Tickets</th>
                    <th>Done Tickets</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $scarfSaleUsers = $tickets->where('user.role_id', 7)->groupBy('user.id');
                ?>
                <?php $__empty_1 = true; $__currentLoopData = $scarfSaleUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userTickets): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $openTickets = $userTickets->filter(function($userTicket) {
                        return $userTicket->status === 'open';
                    })->count();

                    $inProgressTickets = $userTickets->filter(function($userTicket) {
                        return $userTicket->status === 'in progress';
                    })->count();

                    $doneTickets = $userTickets->filter(function($userTicket) {
                        return $userTicket->status === 'done';
                    })->count();
                ?>

                    <tr>
                        <td><?php echo e($userTickets->first()->user->name); ?></td>
                        <td><?php echo e($userTickets->count()); ?></td>
                        <td><?php echo e($openTickets); ?></td>
                        <td><?php echo e($inProgressTickets); ?></td>
                        <td><?php echo e($doneTickets); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center">No tickets found for Scarf Sale.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>


    <!-- Best Shipping Company Section -->
    <div class="mb-5">
        <h1 class="text-success">Best Shipping Company</h1>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>User Name</th>
                    <th>Number of Tickets Added</th>
                    <th>Open Tickets</th>
                    <th>In Progress Tickets</th>
                    <th>Done Tickets</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $shippingCompanyUsers = $tickets->where('user.role_id', 8)->groupBy('user.id');
                ?>
                <?php $__empty_1 = true; $__currentLoopData = $shippingCompanyUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userTickets): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $openTickets = $userTickets->filter(function($userTicket) {
                            return $userTicket->status === 'open';
                        })->count();

                        $inProgressTickets = $userTickets->filter(function($userTicket) {
                            return $userTicket->status === 'in progress';
                        })->count();

                        $doneTickets = $userTickets->filter(function($userTicket) {
                            return $userTicket->status === 'done';
                        })->count();
                    ?>

                    <tr>
                        <td><?php echo e($userTickets->first()->user->name); ?></td>
                        <td><?php echo e($userTickets->count()); ?></td>
                        <td><?php echo e($openTickets); ?></td>
                        <td><?php echo e($inProgressTickets); ?></td>
                        <td><?php echo e($doneTickets); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center">No tickets found for Best Shipping Company.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$("ul#ticket").siblings('a').attr('aria-expanded', 'true');
$("ul#ticket").addClass("show");
$("#report_tickets").addClass("active");
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/tickets/report.blade.php ENDPATH**/ ?>