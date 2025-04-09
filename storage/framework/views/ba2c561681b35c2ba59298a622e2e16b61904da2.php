

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Create New Ticket</h1>
    
    <form action="<?php echo e(route('tickets.store')); ?>" method="POST" id="createTicketForm">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label for="order">Order Number</label>
            <input type="text" name="order" id="order" class="form-control" required>
            <small id="orderStatus" class="form-text text-danger d-none">Order number is invalid</small>
        </div>
        
        <!-- This section will be hidden until order validation passes -->
        <div id="additionalFields" class="d-none">
            <div class="form-group">
                <label for="ticket_type">Ticket Type</label>
                <select name="ticket_type" id="ticket_type" class="form-control" required>
                    <option value="" disabled selected>Choose Ticket Type</option>
                    <option value="request">Request</option>
                    <option value="complaint">Complaint</option>
                </select>
            </div>

            <div class="form-group">
                <label for="reason">Reason</label>
                <select name="reason" id="reason" class="form-control" required disabled>
                    <!-- Dynamic options based on ticket type -->
                </select>
            </div>

            <div class="form-group">
                <label for="content">Note</label>
                <textarea name="content" id="content" class="form-control" required></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-4 d-none" id="saveTicketBtn">Save Ticket</button>
    </form>
</div>

<script>
    // Pass role_id to JavaScript
    const roleId = <?php echo json_encode($roleId, 15, 512) ?>;
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>

$("ul#ticket").siblings('a').attr('aria-expanded', 'true');
$("ul#ticket").addClass("show");
$("#add_ticket").addClass("active");

document.addEventListener('DOMContentLoaded', function () {
    const orderInput = document.querySelector('#order');
    const orderStatus = document.querySelector('#orderStatus');
    const additionalFields = document.querySelector('#additionalFields');
    const saveTicketBtn = document.querySelector('#saveTicketBtn');
    const ticketTypeSelect = document.querySelector('#ticket_type');
    const reasonSelect = document.querySelector('#reason');

    // Trigger order validation on Enter key press
    orderInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Prevent form submission
            checkOrder();
        }
    });

    function checkOrder() {
        const orderNumber = orderInput.value;
        
        // Make an AJAX request to check if order exists
        fetch(`/check-order?order_number=${orderNumber}`)
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    // If order exists, show additional fields and check if ticket already exists for this order
                    additionalFields.classList.remove('d-none');
                    orderStatus.classList.add('d-none');

                    // Check if ticket already exists
                    fetch(`/check-ticket?order_number=${orderNumber}`)
                        .then(response => response.json())
                        .then(ticketData => {
                            if (ticketData.exists) {
                                // Show confirmation dialog
                                const redirectConfirmation = confirm('Ticket already exists for this order. Do you want to view the existing ticket?');
                                
                                if (redirectConfirmation) {
                                    // If "Yes" is clicked, redirect to the ticket page
                                    window.location.href = `/tickets/${ticketData.ticket_id}`;
                                } else {
                                    // If "No" is clicked, stay on the create ticket page
                                    orderInput.value = '';  // Clear order input
                                    additionalFields.classList.add('d-none');
                                    saveTicketBtn.classList.add('d-none');
                                }
                            } else {
                                saveTicketBtn.classList.remove('d-none');
                            }
                        });
                } else {
                    // Show error if order doesn't exist
                    additionalFields.classList.add('d-none');
                    saveTicketBtn.classList.add('d-none');
                    orderStatus.classList.remove('d-none');
                }
            });
    }

    // Update reasons based on ticket type and role_id
    ticketTypeSelect.addEventListener('change', function () {
        const type = this.value;
        reasonSelect.innerHTML = '';
        reasonSelect.disabled = false;

        if (roleId === 7) { // Role ID 7
            if (type === 'request') {
                reasonSelect.innerHTML = `
                    <option value="change_delivery_time">Change Delivery Time</option>
                    <option value="change_delivery_location">Change Delivery Location</option>
                    <option value="change_consignee_data">Change Consignee Data</option>
                    <option value="other">Other</option>
                `;
            } else if (type === 'complaint') {
                reasonSelect.innerHTML = `
                    <option value="shipment_not_delivered">Shipment Not Delivered</option>
                    <option value="delivery_delay">Delivery Delay</option>
                    <option value="mistreatment">Mistreatment</option>
                    <option value="other">Other</option>
                `;
            }
        } else if (roleId === 8) { // Role ID 8
            if (type === 'request') {
                reasonSelect.innerHTML = `
                    <option value="no_answer">No Answer</option>
                    <option value="refuse_receiving">Refuse Receiving</option>
                    <option value="reschedules_delivery">Reschedules Delivery</option>
                    <option value="other">Other</option>
                `;
            } else if (type === 'complaint') {
                reasonSelect.innerHTML = `
                    <option value="wrong_number">Wrong Number</option>
                    <option value="wrong_address">Wrong Address</option>
                    <option value="wrong_total_price">Wrong Total Price</option>
                    <option value="payment_issues">Payment Issues</option>
                `;
            }
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/tickets/create.blade.php ENDPATH**/ ?>