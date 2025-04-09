

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Pending Tickets</h1>

    <!-- Filter Form -->
    <form action="<?php echo e(route('tickets.new')); ?>" method="GET" class="mb-4 border border p-3">
        <div class="row">
            <div class="col-md-4">
                <label for="order_number">Order Number</label>
                <input type="text" name="order_number" id="order_number" class="form-control" value="<?php echo e(request('order_number')); ?>">
            </div>
            <div class="col-md-4">
                <label for="ticket_type">Ticket Type</label>
                <select name="ticket_type" id="ticket_type" class="form-control">
                    <option value="">All</option>
                    <option value="request" <?php echo e(request('ticket_type') == 'request' ? 'selected' : ''); ?>>Request - طلب</option>
                    <option value="complaint" <?php echo e(request('ticket_type') == 'complaint' ? 'selected' : ''); ?>>Complaint - شكوي</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="">All</option>
                    <option value="open" <?php echo e(request('status') == 'open' ? 'selected' : ''); ?>>Open - فتح</option>
                    <option value="in progress" <?php echo e(request('status') == 'in progress' ? 'selected' : ''); ?>>In Progress - جاري العمل عليه</option>
                </select>
            </div>
        </div>    
        <div class="row">
            <div class="col-md-3">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo e(request('start_date')); ?>">
            </div>
            <div class="col-md-3">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo e(request('end_date')); ?>">
            </div>
            <div class="col-md-3">
                <label for="reason">Reason</label>
                <select name="reason" id="reason" class="form-control">
                    <option value="">Select Reason</option>

                    <!-- Role 7 - Request -->
                    <option value="change_delivery_time">Change Delivery Time - تغير معاد التوصيل</option>
                    <option value="change_delivery_location">Change Delivery Location - تغير مكان التوصيل</option>
                    <option value="change_consignee_data">Change Consignee Data - تغير بيانات المرسل اليه</option>
                    <option value="other">Other - اخري</option>

                    <!-- Role 7 - Complaint -->
                    <option value="shipment_not_delivered">Shipment Not Delivered - الشحنه لم تصل</option>
                    <option value="delivery_delay">Delivery Delay - تاخير الاستلام</option>
                    <option value="mistreatment">Mistreatment - سوء معامله</option>

                    <!-- Role 8 - Request -->
                    <option value="no_answer">No Answer - لا أحد يجيب</option>
                    <option value="refuse_receiving">Refuse Receiving - رفض الاستلام</option>
                    <option value="reschedules_delivery">Reschedules Delivery - اعاده جدوله التوصيل</option>

                    <!-- Role 8 - Complaint -->
                    <option value="wrong_number">Wrong Number - رقم خاطئ</option>
                    <option value="wrong_address">Wrong Address - عنوان خاطئ</option>
                    <option value="wrong_total_price">Wrong Total Price - اجمالي السعر خاطئ</option>
                    <option value="payment_issues">Payment Issues - مشكله دفع</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="status">Pending Response</label>
                <select name="response" id="response" class="form-control">
                    <option value="">All</option>
                    <option value="lev" <?php echo e(request('response') === 'lev' ? 'selected' : ''); ?>>Pending Levoile - في انتظار لفوال</option>
                    <option value="best" <?php echo e(request('response') === 'best' ? 'selected' : ''); ?>>Pending Shipping - في انتظار شركه الشحن</option>
                </select>
            </div>

        </div>
        <button type="submit" class="btn btn-secondary mt-3" onclick="resetPendingResponse()">Filter</button>
        <button type="button" class="btn btn-success mt-3" onclick="window.location.href='<?php echo e(route('tickets.new')); ?>';">Reset</button>

    </form>
    
    <table id="ticketsTable" class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>Created At</th>
                <th>Order</th>
                <th>Type</th>
                <th>User</th>
                <th>Status</th>
                <th>Reason</th>
                <th>Messages</th>
                <th>pending Response</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td>
                    <?php echo e($ticket->created_at ? $ticket->created_at->format('d M Y, H:i') : 'N/A'); ?>

                </td>
                <td>
                    <b><?php echo e($ticket->order_number ?? 'N/A'); ?></b>
                </td>
                <td><?php echo e(ucfirst($ticket->ticket->type)); ?></td>
                <td>
                    <?php echo e($ticket->user->name ?? 'N/A'); ?> - <?php if($ticket->user->role_id == 7): ?> LV <?php elseif($ticket->user->role_id == 8): ?> BE <?php else: ?> 'N/A' <?php endif; ?>
                </td>
                <td>
                <span class="badge 
                <?php if(ucfirst($ticket->status) === 'In progress'): ?> bg-secondary <?php endif; ?>
                <?php if(ucfirst($ticket->status) === 'Open'): ?> bg-warning <?php endif; ?>"><?php echo e(ucfirst($ticket->status) ?? 'N/A'); ?></span>
                </td>
                <td>
                    <?php echo e(str_replace('_', ' ', $ticket->content) ?? 'N/A'); ?>

                </td>
                <td>
                    <?php
                        $latestComment = DB::table('comments')
                            ->where('ticket_user_id', $ticket->id)
                            ->latest()
                            ->first();
                            $commentUser = $latestComment ? DB::table('users')->where('id', $latestComment->user_id)->first() : null;

                    ?>

                    <?php echo e($latestComment->body ?? 'No comments yet'); ?><br>
                    <?php if($commentUser): ?>
                        <small><i> by: <?php echo e($commentUser->name); ?> </i></small>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($commentUser->role_id == 7 && (ucfirst($ticket->status) === 'In progress' || ucfirst($ticket->status) === 'Open' )): ?> <span class="badge bg-success">Pending Shipping</span> <?php elseif($commentUser->role_id == 8 && (ucfirst($ticket->status) === 'In progress' || ucfirst($ticket->status) === 'Open' )): ?> <span class="badge bg-success">Pending Levoile</span><?php endif; ?>
                </td>
                <td>
                <?php if($ticket->user->role_id == 7 && auth()->user()->role_id == 8 && ucfirst($ticket->status) !== 'Done'): ?>
                    <!-- Role 8 can open tickets created by Role 7 -->
                    <form action="<?php echo e(route('tickets.open', $ticket->id)); ?>" method="POST" style="display:inline-block;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-info">Open</button>
                    </form>
                <?php elseif($ticket->user->role_id == 8 && auth()->user()->role_id == 7 && ucfirst($ticket->status) !== 'Done'): ?>
                    <!-- Role 7 can open tickets created by Role 8 -->
                    <form action="<?php echo e(route('tickets.open', $ticket->id)); ?>" method="POST" style="display:inline-block;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-info">Open</button>
                    </form>
                <?php else: ?>
                    <!-- Show button for all other scenarios -->
                    <a href="<?php echo e(route('tickets.show', $ticket->id)); ?>" class="btn btn-success">Show</a>
                <?php endif; ?>


                    <!-- <form action="<?php echo e(route('tickets.destroy', $ticket->id)); ?>" method="POST" style="display:inline-block;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this ticket?')">Delete</button>
                    </form> -->
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
$("#new_tickets").addClass("active");
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const responseDropdown = document.getElementById('response');
    const ticketsTable = document.getElementById('ticketsTable');
    const rows = ticketsTable.getElementsByTagName('tr');

    responseDropdown.addEventListener('change', function() {
        filterTable(this.value);
    });

    function filterTable(selectedValue) {
        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            const pendingResponseCell = cells[7]; // Adjust the index if necessary

            if (pendingResponseCell) {
                const cellText = pendingResponseCell.textContent || pendingResponseCell.innerText;
                
                if (selectedValue === '' || cellText.includes(selectedValue === 'lev' ? 'Pending Levoile' : 'Pending Shipping')) {
                    rows[i].style.display = ''; // Show the row
                } else {
                    rows[i].style.display = 'none'; // Hide the row
                }
            }
        }
    }

    window.resetPendingResponse = function() {
        responseDropdown.value = ''; // Reset to default
        filterTable(''); // Call filter function to show all tickets
    };
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/tickets/new.blade.php ENDPATH**/ ?>