

<?php $__env->startSection('content'); ?>
<div class="container">
    <!-- Back Button and Header -->
    <div class="d-flex mb-4">
        <a href="<?php echo e(route('tickets.index')); ?>" class="btn btn-light me-2" style="font-size: 1.5rem;">
            <i class="bi bi-arrow-left" style="font-size: 1.5rem; font-weight: bold;"></i>
        </a>
        <h1>Ticket Details</h1>
    </div>

    <div class="row">
        <!-- LHS: Ticket Details -->
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Ticket Details</h5>
                    <p><strong>Type:</strong> <?php echo e(ucfirst($ticketUser->ticket->type)); ?></p>
                    <p><strong>User:</strong> <?php echo e($ticketUser->user->name); ?></p>
                    <p><strong>Status:</strong> <?php echo e(ucfirst($ticketUser->status)); ?></p>
                    <p><strong>Created At:</strong> <?php echo e($ticketUser->created_at->format('d M Y, H:i')); ?></p>
                    <p><strong>Note:</strong> <?php echo e(str_replace('_', ' ', $ticketUser->content)); ?></p>
                    <?php if(auth()->user()->role_id == 7): ?>
                        <?php if($ticketUser->is_asked_to_close == 1 && $ticketUser->status === "in progress"): ?>
                        <p><strong>Request From Shipping:</strong> Shipping Company is asked to Close Ticket.</p>
                        <?php endif; ?>
                        <?php if($ticketUser->is_returned == 1): ?>
                        <p><strong>Response From Shipping:</strong> Order is Successfully Returned</p>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if(auth()->user()->role_id == 8): ?>
                        <?php if($ticketUser->is_asked_to_return == 1): ?>
                        <p><strong>Request From Levoile:</strong> Levoile asking to return order.</p>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Checkbox to mark as done-->
                     <div class="d-flex">
                        <?php if(($ticketUser->status === 'in progress' && $ticketUser->user_id == auth()->user()->id) || ($ticketUser->status === 'in progress' && auth()->user()->role_id == 7)): ?>
                            <form action="<?php echo e(route('tickets.checkAsDone', $ticketUser->id)); ?>" method="POST" class="mt-3 me-3">
                                <?php echo csrf_field(); ?>
                                <div class="form-check">
                                    <input type="checkbox" name="done" class="form-check-input" id="checkAsDone" required>
                                    <label class="form-check-label" for="checkAsDone">Check as Done</label>
                                </div>
                                <button type="submit" class="btn btn-success mt-2">Update Status</button>
                            </form>
                        <?php endif; ?>
                        <!-- Checkbox to mark as done and create return-->

                        <?php if($ticketUser->status === 'in progress' && auth()->user()->role_id == 7): ?>
                            <form action="<?php echo e(route('tickets.checkAsDoneAndReturn', $ticketUser->id)); ?>" method="POST" class="mt-3">
                                <?php echo csrf_field(); ?>
                                <div class="form-check">
                                    <input type="checkbox" name="done" class="form-check-input" id="checkAsDoneAndDone" required>
                                    <label class="form-check-label" for="checkAsDoneAndDone">Create Return</label>
                                </div>
                                <button type="submit" class="btn btn-success mt-2">Update Status</button>
                            </form>
                        <?php endif; ?>
                     </div>
                    
                    <!-- Checkbox to mark as reopen from admin-->

                    <?php if($ticketUser->status === 'done' && auth()->user()->role_id == 1): ?>
                        <form action="<?php echo e(route('tickets.reopen', $ticketUser->id)); ?>" method="POST" class="mt-3">
                            <?php echo csrf_field(); ?>
                            <div class="form-check">
                                <input type="checkbox" name="reopen" class="form-check-input" id="reopen" required>
                                <label class="form-check-label" for="reopen">Reopen Ticket</label>
                            </div>
                            <button type="submit" class="btn btn-success mt-2">Update Status</button>
                        </form>
                    <?php endif; ?>

                    <!-- start all Done Scenarios -->
                    <?php if($ticketUser->status === 'done'): ?>
                       <!-- is asked to return scenarios start for role 7  -->
                        <?php if(auth()->user()->role_id == 7 && $ticketUser->is_asked_to_return == 0 && $ticketUser->is_returned == 0): ?>
                            <form action="<?php echo e(route('tickets.updateStatus', $ticketUser->id)); ?>" method="POST" class="mt-3">
                                <?php echo csrf_field(); ?>
                                <div class="form-check">
                                    <input type="checkbox" name="create_return" class="form-check-input" id="createReturn" required>
                                    <label class="form-check-label" for="createReturn">Create Return</label>
                                </div>
                                <button type="submit" class="btn btn-primary mt-2">Submit</button>
                            </form>
                        <?php endif; ?>
                        <?php if(auth()->user()->role_id == 7 && $ticketUser->is_asked_to_return == 1): ?>
                            <form action="<?php echo e(route('tickets.updateStatus', $ticketUser->id)); ?>" method="POST" class="mt-3">
                                <?php echo csrf_field(); ?>
                                <div class="form-check">
                                    <input type="checkbox" name="cancel_return" class="form-check-input" id="cancelReturn" required>
                                    <label class="form-check-label" for="cancelReturn">Cancel Request Return</label>
                                </div>
                                <button type="submit" class="btn btn-primary mt-2">Submit</button>
                            </form>
                        <?php endif; ?>
                       <!-- is asked to return scenarios end for role 7  -->
                       <!-- confirm return scenarios end for role 8 start -->
                        <?php if(auth()->user()->role_id == 8 && $ticketUser->is_asked_to_return == 1): ?>
                            <form action="<?php echo e(route('tickets.updateStatus', $ticketUser->id)); ?>" method="POST" class="mt-3">
                                <?php echo csrf_field(); ?>
                                    <div class="form-check">
                                        <input type="checkbox" name="confirm_return" class="form-check-input" id="confirmReturn" required>
                                        <label class="form-check-label" for="confirmReturn">Confirm Return</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                            </form>
                        <?php endif; ?>
                        <!-- confirm return scenarios end for role 8 end -->
                    <?php endif; ?>

                    <!-- start all in progress Scenarios -->

                    <!-- all asked to close scenario from 8 start -->
                    <?php if($ticketUser->status === 'in progress' && auth()->user()->role_id == 8  && $ticketUser->is_asked_to_close == 0): ?>
                        <form action="<?php echo e(route('tickets.updateStatus', $ticketUser->id)); ?>" method="POST" class="mt-3">
                            <?php echo csrf_field(); ?>
                            <div class="form-check">
                                <input type="checkbox" name="ask_to_close" class="form-check-input" id="askToClose" required>
                                <label class="form-check-label" for="askToClose">Ask to Close Ticket</label>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Submit</button>
                        </form>
                    <?php endif; ?>
                    <?php if($ticketUser->status === 'in progress' && auth()->user()->role_id == 8 && $ticketUser->is_asked_to_close == 1): ?>
                        <form action="<?php echo e(route('tickets.updateStatus', $ticketUser->id)); ?>" method="POST" class="mt-3">
                            <?php echo csrf_field(); ?>
                            <div class="form-check">
                                <input type="checkbox" name="cancel_ask_to_close" class="form-check-input" id="cancelaskToClose" required>
                                <label class="form-check-label" for="cancelaskToClose">Cancel Ask to Close Ticket</label>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Submit</button>
                        </form>
                    <?php endif; ?>
                    <!-- all asked to close scenario from 8 end -->

                    <?php if($ticketUser->status === 'in progress' && auth()->user()->role_id == 7 && $ticketUser->is_asked_to_close == 1): ?>
                            <form action="<?php echo e(route('tickets.updateStatus', $ticketUser->id)); ?>" method="POST" class="mt-3" id="closeRequestForm">
                                <?php echo csrf_field(); ?>
                                    <div class="form-check">
                                        <input type="checkbox" name="accept_shipping" class="form-check-input" id="acceptShipping">
                                        <label class="form-check-label" for="acceptShipping">Accept Close Request</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="refuse_shipping" class="form-check-input" id="refuseShipping">
                                        <label class="form-check-label" for="refuseShipping">Refuse Close Request</label>
                                    </div>
                                <button type="submit" class="btn btn-primary mt-2">Submit</button>
                            </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- RHS: Order Details -->
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Order Information</h5>

                    <?php
                        // Fetch the order details using the order_number in the ticketUser
                        $order = \App\Models\Order::where('order_number', $ticketUser->order_number)->first();
                        $customer = json_decode($order->customer);
                        $shipping = $order->total_shipping_price_set; 
                    ?>

                    <?php if($order): ?>
                        <p><strong>Order Date:</strong> <?php echo e($order->created_at_date); ?></p>
                        <p><strong>Order Number:</strong> #<?php echo e($order->order_number); ?></p>
                        <p><strong>Contact Email:</strong> <?php echo e($order->contact_email); ?></p>
                        <p><strong>Client Name:</strong> <?php echo e($customer->first_name); ?> <?php echo e($customer->last_name); ?></p>
                        <p><strong>Phone:</strong> <?php echo e($customer->phone ?? 'N/A'); ?></p>
                        <p><strong>Address:</strong> <?php echo e($customer->default_address->address1); ?>, <?php echo e($customer->default_address->city); ?>, <?php echo e($customer->default_address->province); ?>, <?php echo e($customer->default_address->country); ?></p>
                        <p><strong>Shipping:</strong> $<?php echo e(number_format($shipping['shop_money']['amount'], 2)); ?></p>
                        <p><strong>Total Price:</strong> $<?php echo e(number_format($order->total_price, 2)); ?></p>
                    <?php else: ?>
                        <p>Order details not found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Comment Section -->
    <div class="card">
        <div class="card-header" style="border:none;">
            <h5>Comments</h5>
        </div>
        <div class="card-body" style="border:none;">
            <?php if($ticketUser->comments->isNotEmpty()): ?>
                <ul class="list-group" style="border:none;">
                    <?php $__currentLoopData = $ticketUser->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="list-group-item" style="border:none;">
                            <strong><?php echo e($comment->user->name ?? 'Unknown User'); ?>:</strong> <?php echo e($comment->body); ?>

                            <br><i><small><span class="float-right"><?php echo e($comment->created_at->diffForHumans()); ?></span></small></i>
                        </li>
                        <hr/>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php else: ?>
                <p>No comments yet.</p>
            <?php endif; ?>
        </div>

        <!-- Add Comment -->
        <?php if($ticketUser->status !== 'done'): ?>
            <div class="card-footer">
                <form action="<?php echo e(route('comments.store', $ticketUser->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <textarea name="body" class="form-control" rows="3" placeholder="Add your comment..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Add Comment</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script>
    document.getElementById('closeRequestForm').addEventListener('submit', function (event) {
        const acceptShipping = document.getElementById('acceptShipping').checked;
        const refuseShipping = document.getElementById('refuseShipping').checked;

        if (!acceptShipping && !refuseShipping) {
            event.preventDefault();  // Prevent form submission
            alert('Please select at least one option.');
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/tickets/show.blade.php ENDPATH**/ ?>