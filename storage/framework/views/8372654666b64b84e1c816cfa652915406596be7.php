
<?php $__env->startSection('content'); ?>

    <div class="pagetitle">
        <div class="row">

            <div class="col-8">
                <h1>Orders</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
                        <li class="breadcrumb-item">Orders</li>
                    </ol>
                </nav>
            </div>
            <div class="col-4">
                <a href="<?php echo e(route('orders.sync')); ?>" style="float: right" class="btn btn-primary">Sync Orders</a>
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

                                <div class="col-2">
                                    <h6 class="d-inline-block pt-10px"><?php echo e('Choose Order Date'); ?></h6>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group mb-0">
                                        <input type="date" class="form-control" value="<?php echo e($date); ?>"
                                            name="date" placeholder="<?php echo e('Filter by date'); ?>" data-format="DD-MM-Y"
                                            data-separator=" to " data-advanced-range="true" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-1"></div>
                                <div class="col-1 text-right">
                                    <h6 class="d-inline-block pt-10px text-right"><?php echo e('Search Order'); ?></h6>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group mb-0">
                                        <input type="text" class="form-control" id="search"
                                            name="search"<?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?>
                                            placeholder="<?php echo e('Type Order code & hit Enter'); ?>">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-primary"><?php echo e('Filter'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body mt-1">
                            <div class="row col-12">
                                <div class="col-7">
                                    <h5 class="card-title">All Orders</h5>
                                </div>
                                <div class="col-2">
                                    <h5 class="card-title">Assign:</h5>
                                </div>
                                <div class="col-3 justify-content-center">
                                    <select class="form-select mt-2" name="prepare_emp" id="prepare_emp">
                                        <option value="0"><?php echo e('Choose Prepare Emp'); ?></option>
                                        <?php if(isset($prepare_users_list['name'])): ?>
                                            <?php $__currentLoopData = $prepare_users_list['name']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user_prepare): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a class="dropdown-item" href="#"> <?php echo e($user_prepare); ?></a>
                                                <option value="<?php echo e($prepare_users_list['id'][$key]); ?>"><?php echo e($user_prepare); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>

                            

                            <!-- Table with stripped rows -->
                            <table class="table datatable" id="example">
                                <thead>
                                    <tr>
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
                                        <th scope="col">Created Date</th>
                                        <th scope="col">Order No.</th>
                                        <th scope="col">Order Source</th>
                                        <th scope="col">Customer Data</th>
                                        <th scope="col" class="text-center">Payment Type</th>
                                        <th scope="col" class="text-center">Subtotal</th>
                                        <th scope="col" class="text-center">Shipping</th>
                                        <th scope="col" class="text-center">Total</th>

                                        <th scope="col">Total Products</th>
                                        <th scope="col">Total Items</th>
                                        <th scope="col" class="text-right">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($orders)): ?>
                                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($order): ?>
                                                <?php
                                                    $total_shipping = 0;
                                                    if (
                                                        isset($order['shipping_lines']) &&
                                                        $order['shipping_lines'] &&
                                                        is_array($order['shipping_lines'])
                                                    ) {
                                                        foreach ($order['shipping_lines'] as $ship) {
                                                            $total_shipping = $ship['price'];
                                                        }
                                                    }

                                                    $history = \App\Models\OrderHistory::where(
                                                        'order_id',
                                                        $order->id,
                                                    )->count();
                                                    // Check if customer phone number is blacklisted
                                                    $phone = $order['shipping_address']['phone'] ?? '';
                                                    $phone2 = str_replace('+2', '', $phone);
                                                    $isBlacklisted = !empty($phone)
                                                        ? \App\Models\BlackList::where(
                                                            'phone',
                                                            'like',
                                                            '%' . $phone . '%',
                                                        )
                                                            ->orWhere('phone', 'like', '%' . $phone2 . '%')
                                                            ->first()
                                                        : false;
                                                ?>
                                                <tr>
                                                    <td>
                                                        <div class="form-group">
                                                            <div class="aiz-checkbox-inline">
                                                                <label class="aiz-checkbox">
                                                                    <input type="checkbox" class="check-one" name="id[]"
                                                                        value="<?php echo e($order->id); ?>"
                                                                        <?php if($isBlacklisted): ?> disabled <?php endif; ?>>
                                                                    <span class="aiz-square-check"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><?php echo e(date('Y-m-d h:i:s', strtotime($order->created_at))); ?></td>
                                                    <td><a class="btn-link"
                                                            href="<?php echo e(route('shopify.order.show', $order->table_id)); ?>">Lvs<?php echo e($order->order_number); ?></a>
                                                    </td>
                                                    <td><?php echo e($order->source_name ?? 'Online'); ?></td>
                                                    <?php
                                                        $shipping_address = $order['shipping_address'];

                                                    ?>

                                                    <td>
                                                        <span><?php echo e(isset($shipping_address['name']) ? $shipping_address['name'] : ''); ?>

                                                            /</span>
                                                        <span>
                                                            <?php echo e(isset($shipping_address['phone']) ? $shipping_address['phone'] : ''); ?>

                                                        </span>
                                                        <?php if($isBlacklisted): ?>
                                                            <span class="badge bg-danger">Blacklisted</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e($order->financial_status == 'pending' ? 'Cash' : 'Paid'); ?></td>
                                                    <td class="text-center"><?php echo e($order->subtotal_price); ?></td>
                                                    <td class="text-center"><?php echo e($total_shipping); ?></td>
                                                    <td class="text-center"><?php echo e($order->total_price); ?></td>

                                                    <td class="text-center"><?php echo e(count($order->line_items)); ?></td>
                                                    <td class="text-center">
                                                        <?php echo e(collect($order->line_items)->sum('quantity')); ?></td>
                                                    <input type="hidden" name="total_items[]" class="total_items"
                                                        value="<?php echo e(count($order->line_items)); ?>">
                                                    <input type="hidden" name="total_qty[]" class="total_qty"
                                                        value="<?php echo e(collect($order->line_items)->sum('quantity')); ?>">

                                                    <td class="text-right">
                                                        <?php if($history > 0): ?>
                                                            <div class="row">
                                                                <div class="col-6 justify-content-center ml-3">
                                                                    <div class="row  mb-1 justify-content-center text-center">
                                                                        <a class="btn btn-warning text-center"
                                                                            href="<?php echo e(route('prepare.order-history', $order->id)); ?>"
                                                                            title="Order History">
                                                                            <i class="bi bi-clock-history"></i>
                                                                        </a>
                                                                        History
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class ="col-3  mr-2 ml-2">
                                                            <div class="row mb-1">
                                                                <a class="btn btn-secondary"
                                                                    href="<?php echo e(url('/pos/edit/' . $order->id)); ?>"
                                                                    title="Edit Order">
                                                                    <i class="bi bi-pen"></i>
                                                                </a>
                                                                Edit
                                                            </div>
                                                        </div>
                                                        <?php if($isBlacklisted): ?>
                                                            <div class ="col-3  mr-2 ml-2">
                                                                <div class="row mb-1">
                                                                    <a class="btn btn-danger"
                                                                        onclick="cancel_order(<?php echo e($order->id); ?>)"
                                                                        title="Cancel Order">
                                                                        <i class="bi bi-x-square"></i>
                                                                    </a>
                                                                    Cancel
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>

                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="text-center pb-2">
                                <?php echo e($orders->links()); ?>

                            </div>
                            <!-- End Table with stripped rows -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="cancel-order-modal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cancel Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body fulfillment_form">
                        <form action="<?php echo e(route('orders.update_delivery_status')); ?>" class="row g-3" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="order_id" />
                            <input type="hidden" name="status" value="cancelled" />

                            <div class="col-md-6">
                                <label for="reason">Reason</label>
                                <select class="form-select" name="reason" data-minimum-results-for-search="Infinity">
                                    <option value="" disabled="">Select</option>
                                    <option value="CUSTOMER_REQUEST">Customer changed or canceled order</option>
                                    <option value="BROUGHT_FROM_STORE">Customer Brought From Store</option>
                                    <option value="ORDER_LATE">Order Late Recieve</option>
                                    <option value="WRONG_SHIPPING_INFO">Wrong Shipping Info</option>
                                    <option value="REPEATED_ORDER">Repeated Order</option>
                                    <option value="FAKE_ORDER">FAKE ORDER</option>
                                    <option value="ORDER_CONFIRMED_BY_MISTAKE">Client Confirmed Order By Mistake</option>
                                    <option value="INVENTORY">Items unavailable</option>
                                    <option value="ORDER_UPDATED_AFTER_SHIPPING">Client Updated the Order After Being
                                        Shipped
                                    </option>
                                    <option value="OTHER">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="note">Cancelling Note*</label>
                                <input type="text" name="note" class="form-control"
                                    placeholder="Enter Reason and Hit Enter" required>
                            </div>
                            <div class="col-4 justify-content-center">
                                <button type="submit" class="btn btn-danger">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script type="text/javascript">
        $("ul#operation").siblings('a').attr('aria-expanded', 'true');
        $("ul#operation").addClass("show");
        $("#sync").addClass("active");

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
        $("#prepare_emp").change(function() {
            var data = new FormData($('#sort_orders')[0]);
            var selected_name = $(this).find("option:selected").text();
            var selected_user = this.value;
            data.append('emp_name', selected_name);
            var selected_items = 0;
            var selected_qty = 0;
            var total_orders = 0;
            $('table tbody tr').each(function() {
                const checkbox = $(this).find('.check-one');

                if (checkbox.is(':checked')) {
                    // Get the quantity of the checked row
                    const qty = parseInt($(this).find('.total_items').val());
                    selected_items += qty;
                    const qty2 = parseInt($(this).find('.total_qty').val());
                    selected_qty += qty2;
                    total_orders += 1;
                }
            });

            if (selected_user == 0) {

            } else {
                if (confirm('Are You Sure to Assign These ' + total_orders + ' Orders With ' + selected_items +
                        ' Products and ' +
                        selected_qty + ' Items to ' + selected_name)) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "<?php echo e(route('bulk-order-assign')); ?>",
                        type: 'POST',
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response == 0) {
                                location.reload();
                            } else {
                                location.reload();
                            }
                        }
                    });
                } else {}
            }

            console.log(selected_name);
            console.log(selected_user);
            console.log(data);
        });

        function cancel_order(id) {
            $('input[name=order_id]').val(id);
            $('#cancel-order-modal').modal('show');
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/orders/index.blade.php ENDPATH**/ ?>