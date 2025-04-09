<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('assets/css/bootstrap-rtl.min.css')); ?>" rel="stylesheet">
    <style>
        .shadow {
            -moz-box-shadow: 3px 3px 5px 6px #ccc;
            -webkit-box-shadow: 3px 3px 5px 6px #ccc;
            box-shadow: 3px 3px 5px 6px #ccc;
            border-radius: 4%;
            /*supported by all latest Browser*/
            -moz-border-radius: 4%;
            /*For older Browser*/
            -webkit-border-radius: 4%;
            /*For older Browser*/

            width: 130px;
            height: 50px;
        }

        .shadow2 {
            border-radius: 4%;

            width: 130px;
            height: 115px;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="pagetitle">
        <div class="row">
            <div class="col-8">
                <h1>Orders</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
                        <li class="breadcrumb-item">Assigned Orders</li>
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

                                <div class="col-2 justify-content-center">

                                    <div class="shadow justify-content-center text-center"
                                        style="background-color: rgb(239, 208, 99); color:white">
                                        New Orders
                                        <br>
                                        <?php echo e(isset($all_orders['processing']) ? $all_orders['processing'] : 0); ?>

                                    </div>

                                </div>
                                <div class="col-2 justify-content-center">

                                    <div class="shadow justify-content-center text-center"
                                        style="background-color: rgb(33, 241, 68); color:white">
                                        Prepared Orders
                                        <br>
                                        <?php echo e(isset($all_orders['prepared']) ? $all_orders['prepared'] : 0); ?>

                                    </div>

                                </div>
                                <div class="col-2 justify-content-center">

                                    <div class="shadow justify-content-center text-center"
                                        style="background-color: rgb(231, 138, 66); color:white">
                                        Hold Orders
                                        <br>
                                        <?php echo e(isset($all_orders['hold']) ? $all_orders['hold'] : 0); ?>

                                    </div>

                                </div>
                                <div class="col-2 justify-content-center">

                                    <div class="shadow justify-content-center text-center"
                                        style="background-color: rgb(15, 15, 14); color:white">
                                        Cancelled Orders
                                        <br>
                                        <?php echo e(isset($all_orders['cancelled']) ? $all_orders['cancelled'] : 0); ?>

                                    </div>

                                </div>
                                <div class="col-2 justify-content-center">

                                    <div class="shadow justify-content-center text-center"
                                        style="background-color: rgb(5, 64, 85); color:white">
                                        Fulfilled Orders
                                        <br>
                                        <?php echo e(isset($all_orders['fulfilled']) ? $all_orders['fulfilled'] : 0); ?>

                                    </div>

                                </div>
                                <div class="col-2 justify-content-center">

                                    <div class="shadow justify-content-center text-center"
                                        style="background-color: rgb(3, 36, 22); color:white">
                                        Shipped Orders
                                        <br>
                                        <?php echo e(isset($all_orders['shipped']) ? $all_orders['shipped'] : 0); ?>

                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="card-header row gutters-5">
                            <div class="row col-12">

                                <div class="col-4 justify-content-center">

                                    <div class="container">
                                        <label for="daterange">Choose Date Range</label>
                                        <input type="text" value="<?php echo e($daterange); ?>" id="daterange" name="daterange"
                                            class="form-control">
                                    </div>

                                </div>
                                <div class="col-1"></div>
                                <div class="col-4 justify-content-center">

                                    <div class="container">
                                        <label for="search">Search Order</label>
                                        <input type="text" class="form-control" id="search"
                                            name="search"<?php if(isset($sort_search)): ?> value="<?php echo e($sort_search); ?>" <?php endif; ?>
                                            placeholder="<?php echo e('Type Order code & hit Enter'); ?>">
                                    </div>

                                </div>
                                <div class="col-auto justify-content-center">
                                    <div class="container">
                                        <button type="submit" class="btn btn-primary"><?php echo e('Filter'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-header row gutters-5">
                            <div class="row col-12">
                                <div class="col-sm-3">
                                    <h6 class="d-inline-block pt-10px"><?php echo e('Filter Orders'); ?></h6>
                                </div>
                                <div class="col-sm-3">
                                    <div class="">
                                        <select class="form-select aiz-selectpicker" name="delivery_status"
                                            id="delivery_status">
                                            <option value=""><?php echo e('Filter by Delivery Status'); ?></option>
                                            <option value="processing" <?php if($delivery_status == 'processing'): ?> selected <?php endif; ?>>
                                                <?php echo e('Processing'); ?> </option>
                                            <option value="distributed" <?php if($delivery_status == 'distributed'): ?> selected <?php endif; ?>>
                                                <?php echo e('Distributed'); ?> </option>
                                            <option value="prepared" <?php if($delivery_status == 'prepared'): ?> selected <?php endif; ?>>
                                                <?php echo e('Prepared'); ?></option>
                                            <option value="hold" <?php if($delivery_status == 'hold'): ?> selected <?php endif; ?>>
                                                <?php echo e('Hold'); ?></option>
                                            <option value="reviewed" <?php if($delivery_status == 'reviewed'): ?> selected <?php endif; ?>>
                                                <?php echo e('Reviewed'); ?></option>
                                            <option value="shipped" <?php if($delivery_status == 'shipped'): ?> selected <?php endif; ?>>
                                                <?php echo e('Shipped'); ?></option>
                                            <option value="cancelled" <?php if($delivery_status == 'cancelled'): ?> selected <?php endif; ?>>
                                                <?php echo e('Cancel'); ?></option>
                                            <option value="fulfilled" <?php if($delivery_status == 'fulfilled'): ?> selected <?php endif; ?>>
                                                <?php echo e('Fulfilled'); ?></option>
                                            <option value="returned" <?php if($delivery_status == 'returned'): ?> selected <?php endif; ?>>
                                                    <?php echo e('Returned'); ?></option>
                                            <option value="delivered" <?php if($delivery_status == 'delivered'): ?> selected <?php endif; ?>>
                                                <?php echo e('Delivered'); ?></option>
                                            <option value="partial_return" <?php if($delivery_status == 'partial_return'): ?> selected <?php endif; ?>>
                                                <?php echo e('Partial Return'); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row col-12">
                                <div class="col-7">
                                    <h5 class="card-title">Assigned Orders</h5>
                                </div>
                                <div class="col-2">
                                    <h5 class="card-title">Re-Assign:</h5>
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
                                        <th scope="col">Order No.</th>
                                        <th scope="col">Order Source</th>
                                        <th scope="col">Customer Data</th>
                                        <th scope="col">Channel</th>
                                        <th scope="col" class="text-center">Payment Type</th>
                                        <th scope="col" class="text-center">Subtotal</th>
                                        <th scope="col">Shipping</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Delivery Status</th>
                                        <th scope="col" class="text-center">Assigned To</th>

                                        <th scope="col">Created Date</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($orders)): ?>
                                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($order): ?>
                                                <?php
                                                    $total_shipping = 0;
                                                    foreach ($order->order['shipping_lines'] as $ship) {
                                                        $total_shipping = $ship['price'];
                                                    }
                                                    $returns = 0;
                                                    $return = \App\Models\ReturnedOrder::where(
                                                        'order_number',
                                                        $order->order->order_number,
                                                    )->first();
                                                    if ($return) {
                                                        $returns = \App\Models\ReturnDetail::where(
                                                            'return_id',
                                                            $return->id,
                                                        )->sum('amount');
                                                    }

                                                ?>
                                                <tr>
                                                    <td>
                                                        <div class="form-group">
                                                            <div class="aiz-checkbox-inline">
                                                                <label class="aiz-checkbox">
                                                                    <input type="checkbox" class="check-one" name="id[]"
                                                                        value="<?php echo e($order->order_id); ?>">
                                                                    <span class="aiz-square-check"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><a class="btn-link"
                                                            href="<?php echo e(route('shopify.order.prepare', $order->order->order_number)); ?>">Lvs<?php echo e($order->order->order_number); ?></a>
                                                    </td>
                                                    <?php
                                                        $shipping_address = $order->order['shipping_address'];

                                                    ?>
                                                    <td><?php echo e($order->channel ?? 'Online'); ?>

                                                    </td>
                                                    <td>
                                                        <p><?php echo e(isset($shipping_address['name']) ? $shipping_address['name'] : ''); ?>

                                                             / </p>
                                                        <p>
                                                            <?php echo e(isset($shipping_address['phone']) ? $shipping_address['phone'] : ''); ?>

                                                        </p>
                                                    </td>
                                                    <td><?php echo e($order->channel ?? 'Online'); ?></td>
                                                    <td class="text-center">
                                                        <?php echo e($order->order->financial_status == 'paid' ? 'Paid' : 'Cash'); ?>

                                                    </td>
                                                    <td class="text-center"><?php echo e($order->order->subtotal_price); ?></td>
                                                    <td class="text-center"><?php echo e($total_shipping); ?></td>
                                                    <td class="text-center"><?php echo e($order->order->total_price - $returns); ?></td>
                                                    
                                                    <td>
                                                        <?php if($order->delivery_status == 'processing'): ?>
                                                            <span
                                                                class="badge badge-inline badge-danger"><?php echo e($order->delivery_status); ?></span>
                                                        <?php elseif($order->delivery_status == 'distributed'): ?>
                                                            <span
                                                                class="badge badge-inline badge-info"><?php echo e($order->delivery_status); ?></span>
                                                        <?php elseif($order->delivery_status == 'prepared'): ?>
                                                            <span
                                                                class="badge badge-inline badge-success"><?php echo e($order->delivery_status); ?></span>
                                                        <?php elseif($order->delivery_status == 'shipped'): ?>
                                                            <span
                                                                class="badge badge-inline badge-primary"><?php echo e($order->delivery_status); ?></span>
                                                        <?php elseif($order->delivery_status == 'hold'): ?>
                                                            <span
                                                                class="badge badge-inline badge-warning"><?php echo e($order->delivery_status); ?></span>
                                                        <?php elseif($order->delivery_status == 'reviewed'): ?>
                                                            <span
                                                                class="badge badge-inline badge-secondary"><?php echo e($order->delivery_status); ?></span>
                                                        <?php elseif($order->delivery_status == 'cancelled'): ?>
                                                            <span
                                                                class="badge badge-inline badge-danger"><?php echo e($order->delivery_status); ?></span>
                                                        <?php elseif($order->delivery_status == 'fulfilled'): ?>
                                                            <span
                                                                class="badge badge-inline badge-dark"><?php echo e($order->delivery_status); ?></span>
                                                        <?php elseif($order->delivery_status == 'returned'): ?>
                                                            <span
                                                            class="badge badge-inline badge-danger"><?php echo e("Returned"); ?></span>
                                                        <?php elseif($order->delivery_status == 'delivered'): ?>
                                                            <span
                                                            class="badge badge-inline badge-success"><?php echo e($order->delivery_status); ?></span>
                                                        <?php elseif($order->delivery_status == 'partial_return'): ?>
                                                            <span
                                                            class="badge badge-inline badge-warning"><?php echo e("Partial Return"); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo e($order->user->name); ?></td>
                                                    <td><?php echo e(date('Y-m-d h:i:s', strtotime($order->order->created_at))); ?></td>


                                                    <td class="text-right">
                                                        <div class="row">
                                                            <div class="col-3 mr-2 ml-2">
                                                                <div class="row  mb-1">
                                                                    <a class="btn btn-warning"
                                                                        href="<?php echo e(route('prepare.order-history', $order->order_id)); ?>"
                                                                        title="Order History">
                                                                        <i class="bi bi-clock-history"></i>
                                                                    </a>
                                                                    History
                                                                </div>
                                                            </div>

                                                            <?php if($order->delivery_status != 'shipped'): ?>
                                                                <div class ="col-3  mr-2 ml-2">
                                                                    <div class="row mb-1">
                                                                        <a class="btn btn-danger"
                                                                            onclick="cancel_order(<?php echo e($order->order_id); ?>)"
                                                                            title="Cancel Order">
                                                                            <i class="bi bi-x-square"></i>
                                                                        </a>
                                                                        Cancel
                                                                    </div>
                                                                </div>
                                                                <div class ="col-3  mr-2 ml-2">
                                                                    <div class="row mb-1">
                                                                        <a class="btn btn-secondary"
                                                                            href="<?php echo e(url('/pos/edit/' . $order->order_id)); ?>"
                                                                            title="Edit Order">
                                                                            <i class="bi bi-pen"></i>
                                                                        </a>
                                                                        Edit
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>

                                                            <?php if($order->delivery_status == 'prepared' && auth()->user()->role_id != 6): ?>
                                                                <div class="col-3  mr-2 ml-2">
                                                                    <div class="row mb-1">
                                                                        <a class="btn btn-primary"
                                                                            href="<?php echo e(route('prepare.review', $order->order_id)); ?>"
                                                                            title="Review Order">
                                                                            <i class="bi bi-check-lg"></i>
                                                                        </a>
                                                                        Review
                                                                    </div>
                                                                </div>
                                                            <?php elseif($order->delivery_status == 'fulfilled'): ?>
                                                                <div class="col-3  mr-2 ml-2">
                                                                    <div class="row mb-1">
                                                                        <a class="btn btn-dark"
                                                                            href="<?php echo e(route('prepare.generate-invoice', $order->order_id)); ?>"
                                                                            title="Generate Invoice">
                                                                            <i class="bi bi-printer"></i>
                                                                        </a>
                                                                        Invoice
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
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
        $("#prepares").addClass("active");
        $(document).ready(function() {
            $('#daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'MM/DD/YYYY'
                }
            });
        });
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

            if (selected_user == 0) {

            } else {
                if (confirm('Are You Sure to Assign These order to ' + selected_name)) {
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/preparation/all.blade.php ENDPATH**/ ?>