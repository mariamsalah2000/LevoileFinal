

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>All Tickets</h1>
    <!-- Report Cards -->
   <!-- Report Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <a href="<?php echo e(route('tickets.index', ['status' => ''])); ?>" class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title" style="color:white;">Total Tickets</h5>
                <p class="card-text"><?php echo e($totalTickets); ?></p>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo e(route('tickets.index', ['status' => 'open'])); ?>" class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Open</h5>
                <p class="card-text"><?php echo e($openCount); ?></p>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo e(route('tickets.index', ['status' => 'in progress'])); ?>" class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">In Progress</h5>
                <p class="card-text"><?php echo e($inProgressCount); ?></p>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo e(route('tickets.index', ['status' => 'done'])); ?>" class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title" style="color:white;">Done</h5>
                <p class="card-text"><?php echo e($doneCount); ?></p>
            </div>
        </a>
    </div>
</div>

<!-- Report Cards for Ticket Types -->
<div class="row mb-4">
    <div class="col-md-6">
        <a href="<?php echo e(route('tickets.index', ['ticket_type' => 'request'])); ?>" class="card text-white bg-secondary mb-3">
            <div class="card-body">
                <h5 class="card-title" style="color:white;">Request Tickets</h5>
                <p class="card-text"><?php echo e($requestCount); ?></p>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="<?php echo e(route('tickets.index', ['ticket_type' => 'complaint'])); ?>" class="card text-white bg-danger mb-3">
            <div class="card-body">
                <h5 class="card-title" style="color:white;">Complaint Tickets</h5>
                <p class="card-text"><?php echo e($complaintCount); ?></p>
            </div>
        </a>
    </div>
</div>

    <!-- Filter Form -->
    <form action="<?php echo e(route('tickets.index')); ?>" method="GET" class="mb-4 border border p-3">
        <div class="row">
            <div class="col-md-3">
                <label for="order_number">Order Number</label>
                <input type="text" name="order_number" id="order_number" class="form-control" value="<?php echo e(request('order_number')); ?>">
            </div>
            <div class="col-md-3">
                <label for="ticket_type">Ticket Type</label>
                <select name="ticket_type" id="ticket_type" class="form-control">
                    <option value="">All</option>
                    <option value="request" <?php echo e(request('ticket_type') == 'request' ? 'selected' : ''); ?>>Request - طلب</option>
                    <option value="complaint" <?php echo e(request('ticket_type') == 'complaint' ? 'selected' : ''); ?>>Complaint - شكوي</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="">All</option>
                    <option value="open" <?php echo e(request('status') == 'open' ? 'selected' : ''); ?>>Open - مفتوح</option>
                    <option value="in progress" <?php echo e(request('status') == 'in progress' ? 'selected' : ''); ?>>In Progress - جاري العمل به</option>
                    <option value="done" <?php echo e(request('status') == 'done' ? 'selected' : ''); ?>>Done - تم الانتهاء</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="status">Final State</label>
                <select name="state" id="status" class="form-control">
                    <option value="">All</option>
                    <option value="failed" <?php echo e(request('state') == 'failed' ? 'selected' : ''); ?>>Failed - فشل</option>
                    <option value="pending" <?php echo e(request('state') == 'pending' ? 'selected' : ''); ?>>Pending Return - في انتظار الاسترجاع</option>
                    <option value="success" <?php echo e(request('state') == 'success' ? 'selected' : ''); ?>>Success - نجح</option>
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
                    <option value="change_delivery_time">Change Delivery Time - تغير وقت الاستلام</option>
                    <option value="change_delivery_location">Change Delivery Location - تغير مكان الاستلام</option>
                    <option value="change_consignee_data">Change Consignee Data - تغير بيانات المرسل اليه</option>
                    <option value="other">Other - اخري</option>

                    <!-- Role 7 - Complaint -->
                    <option value="shipment_not_delivered">Shipment Not Delivered - الشحنه لم تصل</option>
                    <option value="delivery_delay">Delivery Delay - تأخير التوصيل</option>
                    <option value="mistreatment">Mistreatment - سوء معامله</option>

                    <!-- Role 8 - Request -->
                    <option value="no_answer">No Answer - لا احد يجيب</option>
                    <option value="refuse_receiving">Refuse Receiving - رفض الاستلام</option>
                    <option value="reschedules_delivery">Reschedules Delivery - اعادة جدوله التسليم</option>

                    <!-- Role 8 - Complaint -->
                    <option value="wrong_number">Wrong Number - رقم خاطئ</option>
                    <option value="wrong_address">Wrong Address - عنوان خاطئ</option>
                    <option value="wrong_total_price">Wrong Total Price - اجمالي المبلغ خاطئ</option>
                    <option value="payment_issues">Payment Issues - مشاكل الدفع</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="status">Pending Response</label>
                <select name="response" id="response" class="form-control">
                    <option value="">All</option>
                    <option value="lev" <?php echo e(request('response') === 'lev' ? 'selected' : ''); ?>>Pending Levoile - في انتظار لفوال</option>
                    <option value="best" <?php echo e(request('response') === 'best' ? 'selected' : ''); ?>>Pending Shipping - في انتظار شركه الشحن</option>
                    <option value="hold" <?php echo e(request('response') === 'hold' ? 'selected' : ''); ?>>Holding Ticket -  تم تعليق التيكت</option>
                </select>
            </div>

        </div>
        <button type="submit" class="btn btn-secondary mt-3" onclick="resetPendingResponse()">Filter</button>
        <button type="button" class="btn btn-success mt-3" onclick="window.location.href='<?php echo e(route('tickets.index')); ?>';">Reset</button>

    </form>

    <button onclick="exportTableToExcel('ticketsTable', new Date().toISOString().slice(0, 10) + '_' + new Date().toTimeString().slice(0, 8).replace(/:/g, '-')+'_tickets_data')" class="btn btn-success mb-2" style="float:right;">Export</button>
    
    <table id="ticketsTable" class="table table-bordered table-hover">
        <thead class="thead-dark" style="background-color:#4154f1;color:white;">
            <tr>
                <th>Created At</th>
                <th>Order</th>
                <th>Type</th>
                <th>User</th>
                <th>Status</th>
                <th>Final State</th>
                <th>Reason</th>
                <th>Messages</th>
                <th>pending Response</th>
                <?php if(auth()->user()->role_id == 8): ?><th>Confirm Return</th><?php endif; ?>
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
                <?php if(ucfirst($ticket->status) === 'Done'): ?> bg-success <?php endif; ?> 
                <?php if(ucfirst($ticket->status) === 'In progress'): ?> bg-secondary <?php endif; ?>
                <?php if(ucfirst($ticket->status) === 'Open'): ?> bg-warning <?php endif; ?>"><?php echo e(ucfirst($ticket->status) ?? 'N/A'); ?></span>
                </td>
                <td>
                <?php if(ucfirst($ticket->status) === 'Done' && $ticket->is_asked_to_return == 0 && $ticket->is_returned == 0): ?>
                <span class="badge bg-success">Success</span>
                <?php elseif(ucfirst($ticket->status) === 'In progress' && $ticket->is_asked_to_return == 1 && $ticket->is_returned == 0): ?>
                <span class="badge bg-warning">Pending Return</span>
                <?php elseif(ucfirst($ticket->status) === 'Done' && $ticket->is_returned == 1 && $ticket->is_asked_to_return == 0): ?>
                <span class="badge bg-danger">Failed</span>
                <?php else: ?>
                <span>--</span>
                <?php endif; ?>
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
                    <?php if($commentUser->role_id == 7 && $ticket->is_hold == 0 && (ucfirst($ticket->status) === 'In progress' || ucfirst($ticket->status) === 'Open' )): ?> <span class="badge bg-success">Pending Shipping</span> <?php elseif($commentUser->role_id == 8 && $ticket->is_hold == 0 && (ucfirst($ticket->status) === 'In progress' || ucfirst($ticket->status) === 'Open' )): ?> <span class="badge bg-success">Pending Levoile</span> <?php elseif($commentUser->role_id == 8 &&  $ticket->is_hold == 0 && (ucfirst($ticket->status) === 'In progress' || ucfirst($ticket->status) === 'Open' )): ?> <span class="badge bg-success">Pending Levoile</span> <?php elseif(ucfirst($ticket->status) === 'In progress' && $ticket->is_hold == 1): ?> <span class="badge bg-danger">Holding Ticket</span><?php else: ?> <span>--</span> <?php endif; ?>
                </td>
                <?php if(auth()->user()->role_id == 8): ?>
                <td>
                    <div class="d-flex">
                        <?php if($ticket->is_asked_to_return == 1 && $ticket->is_returned == 0): ?>
                            <span class="badge bg-warning">Confirm Return</span>
                            <input type="checkbox" id="confirmReturnCheckbox-<?php echo e($ticket->id); ?>" class="confirm-return-checkbox" data-ticket-id="<?php echo e($ticket->id); ?>">
                        <?php endif; ?>
                    </div>
                </td>
                <?php endif; ?>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>


<script>

$("ul#ticket").siblings('a').attr('aria-expanded', 'true');
$("ul#ticket").addClass("show");
$("#all_tickets").addClass("active");

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
            const pendingResponseCell = cells[8]; // Adjust the index if necessary

            if (pendingResponseCell) {
                const cellText = pendingResponseCell.textContent || pendingResponseCell.innerText;
                
                // Determine visibility based on selected filter
                const shouldShow = (
                    selectedValue === '' || // Show all
                    (selectedValue === 'lev' && cellText.includes('Pending Levoile')) ||
                    (selectedValue === 'best' && cellText.includes('Pending Shipping')) ||
                    (selectedValue === 'hold' && cellText.includes('Holding Ticket'))
                );

                rows[i].style.display = shouldShow ? '' : 'none';
            }
        }
    }

    window.resetPendingResponse = function() {
        responseDropdown.value = ''; // Reset to default
        filterTable(''); // Call filter function to show all tickets
    };
});

</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.confirm-return-checkbox');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const ticketId = this.getAttribute('data-ticket-id');
            const isChecked = this.checked;

            // AJAX request
            fetch('/tickets/confirm-return', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    ticket_id: ticketId,
                    confirmed: isChecked
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Return status updated successfully.');
                    window.location.reload();
                } else {
                    alert('Failed to update return status.');
                    this.checked = !isChecked; // Revert checkbox state if there's an error
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                this.checked = !isChecked; // Revert checkbox state if there's an error
            });
        });
    });
});

</script>

<script>
function exportTableToExcel(tableID, filename = '') {
    // Clone the original table
    const originalTable = document.getElementById(tableID);
    const clonedTable = originalTable.cloneNode(true);

    // Locate the index of the "Action" column dynamically
    let actionColumnIndex = -1;
    clonedTable.querySelectorAll('thead tr th').forEach((header, index) => {
        if (header.textContent.trim() === 'Action') {
            actionColumnIndex = index;
        }
    });

    // Ensure "Action" column was found before attempting to remove it
    if (actionColumnIndex !== -1) {
        // Remove the "Action" header cell
        clonedTable.querySelectorAll('thead tr').forEach(row => {
            row.deleteCell(actionColumnIndex);
        });

        // Remove the "Action" cell in each body row
        clonedTable.querySelectorAll('tbody tr').forEach(row => {
            row.deleteCell(actionColumnIndex);
        });
    }

    // Convert the modified cloned table to a worksheet
    const worksheet = XLSX.utils.table_to_sheet(clonedTable);

    // Create a new workbook and append the worksheet
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

    // Use the provided filename or default
    filename = filename ? filename + '.xlsx' : 'exported_data.xlsx';

    // Export the file
    XLSX.writeFile(workbook, filename);
}


</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/tickets/index.blade.php ENDPATH**/ ?>