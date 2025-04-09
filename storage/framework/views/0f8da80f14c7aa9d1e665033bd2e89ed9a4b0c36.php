

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h1 class="mb-4">Tickets for Reason: <?php echo e(ucfirst(str_replace('_', ' ', $reason))); ?></h1>

    <!-- Card for displaying tickets -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5>List of Tickets</h5>
        </div>
        <div class="card-body">
            <?php if($tickets->isEmpty()): ?>
                <div class="alert alert-warning" role="alert">
                    No tickets found for this reason.
                </div>
            <?php else: ?>
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Created At</th>
                            <th>Order Number</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($ticket->created_at); ?></td>
                            <td><?php echo e($ticket->order_number); ?></td>
                            <?php 
                                $user = \App\Models\User::find($ticket->user_id);
                            ?>
                            <td><?php echo e($user->name); ?></td>

                            <td>
                                <span class="badge 
                                    <?php if($ticket->status === 'Done'): ?> bg-success 
                                    <?php elseif($ticket->status === 'In Progress'): ?> bg-secondary 
                                    <?php elseif($ticket->status === 'Open'): ?> bg-warning 
                                    <?php else: ?> bg-danger <?php endif; ?>">
                                    <?php echo e(ucfirst($ticket->status)); ?>

                                </span>
                            </td>
                            <td>
                                <a href="<?php echo e(route('tickets.show', $ticket->id)); ?>" class="btn btn-info btn-sm">View</a>
                                <!-- Add more action buttons as necessary -->
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="<?php echo e(route('reason.report')); ?>" class="btn btn-secondary">Back to Reason Report</a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Any additional JavaScript if needed
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/tickets/reason-show.blade.php ENDPATH**/ ?>