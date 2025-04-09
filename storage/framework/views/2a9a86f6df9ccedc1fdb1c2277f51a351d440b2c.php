
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('assets/css/bootstrap-rtl.min.css')); ?>" rel="stylesheet">
    <style>
        .pagination {
            font-size: 14px;
            /* Adjust this size to make the links smaller */
        }

        .page-link {
            padding: 0.25rem 0.75rem;
            /* Control padding for smaller buttons */
            font-size: 0.875rem;
            /* Smaller font size */
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="pagetitle">
        <div class="row">
            <div class="col-8">
                <h1>Confirmed Returns</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
                        <li class="breadcrumb-item">Edited Orders</li>
                    </ol>
                </nav>
            </div>
            <div class="col-4">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td>
                                <a href="#" onclick="addReturn()" style="float:right" class="btn btn-success">Add
                                    New</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <form class="" action="" id="sort_orders" method="GET">
                        <div class="card-header row gutters-5">
                            <div class="row col-12 justify-content-center">

                                <div class="col-4 justify-content-center">

                                    <div class="container">
                                        <label for="date">Filter By Daterange</label>
                                        <input type="text" value="<?php echo e($daterange); ?>" id="daterange" name="daterange"
                                            class="form-control">
                                    </div>

                                </div>
                                <div class="col-sm-3 justify-content-center">
                                    <div class="form-group mb-0">
                                        <label for="date">Search Return</label>
                                        <input type="text" class="form-control" id="search"
                                            name="search"<?php if(isset($search)): ?> value="<?php echo e($search); ?>" <?php endif; ?>
                                            placeholder="<?php echo e('Type Order or Return No & Hit Enter'); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-1 m-2 justify-content-center">
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-header row gutters-5">
                            <div class="row col-12">
                                <div class="col-sm-3">
                                    <h6 class="d-inline-block pt-10px">Total Returns</h6>
                                </div>
                                <div class="col-sm-4">
                                    <h6 class="d-inline-block pt-10px"><?php echo e($returns_count); ?> Orders</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Confirmed Return</h5>
                            

                            <!-- Table with stripped rows -->
                            <table class="table datatable" id="example">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">Return No</th>
                                        <th scope="col" class="text-center">Order No</th>
                                        <th scope="col" class="text-center">Status</th>
                                        <th scope="col" class="text-center">Note</th>
                                        <th scope="col" class="text-center">Old Order Amount</th>
                                        <th scope="col" class="text-center">Return Amount</th>
                                        <th scope="col" class="text-center">New Order Amount</th>
                                        <th scope="col" class="text-center">Quantity</th>
                                        <th scope="col" class="text-center">Returned By</th>
                                        <th scope="col" class="text-center">Shipping On</th>
                                        <th scope="col" class="text-center">Created Date</th>
                                        <th scope="col" class="text-center">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($returns)): ?>
                                        <?php $__currentLoopData = $returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $return): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($return): ?>
                                                <tr>
                                                    <td class="text-center">
                                                        Lvr<?php echo e($return->return_number); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <a class="btn-link"
                                                            href="<?php echo e(route('shopify.order.prepare', $return->order_number)); ?>">Lvs<?php echo e($return->order_number); ?></a>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if($return->status == 'In Progress'): ?>
                                                            <span
                                                                class="badge badge-inline badge-danger"><?php echo e($return->status); ?></span>
                                                        <?php elseif($return->status == 'Returned'): ?>
                                                            <span
                                                                class="badge badge-inline badge-info"><?php echo e($return->status); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center"><?php echo e($return->note??"-"); ?></td>
                                                    <td class="text-center">
                                                        <?php echo e($return->order->total_price); ?></td>
                                                    <td class="text-center"><?php echo e($return->amount); ?></td>
                                                    <td class="text-center"><?php echo e($return->order->total_price - $return->amount); ?></td>
                                                    <td class="text-center">
                                                        <?php echo e($return->qty); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e($return->user ? $return->user->name : '-'); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e(ucfirst($return->shipping_on)); ?>

                                                    </td>
                                                    <td><?php echo e(date('Y-m-d h:i:s', strtotime($return->created_at))); ?></td>
                                                    <td class="text-center">
                                                        <div class="col-5  mr-2 ml-2">
                                                            <div class="row mb-1">
                                                                <a class="btn btn-dark"
                                                                    href="<?php echo e(route('prepare.generate-return-invoice', $return->id)); ?>"
                                                                    title="Generate Invoice">
                                                                    <i class="bi bi-printer"></i>
                                                                </a>
                                                                Invoice
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>

                                    <tr>
                                        <th class="text-center"><?php echo e($returns_count); ?></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>


                                </tfoot>

                            </table>

                            <!-- End Table with stripped rows -->

                        </div>

                    </form>
                    <div class="row">
                        <div class="col-12">
                            <nav>
                                <ul class="pagination pagination-sm">
                                    <?php echo e($returns->links()); ?>

                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <div class="modal fade" id="confirm-modal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Return</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body fulfillment_form">
                    <form action="<?php echo e(route('returns.confirm')); ?>" class="row g-3" method="get">

                        <div class="col-md-6">
                            <label for="order_id">Order Number</label>
                            <input type="text" name="order_number" class="form-control"
                                placeholder="Enter Order Number and Hit Enter" required>
                        </div>
                        <div class="col-4 justify-content-center">
                            <button type="submit" class="btn btn-info">Submit</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script type="text/javascript">
        $("ul#edited").siblings('a').attr('aria-expanded', 'true');
        $("ul#edited").addClass("show");
        $("#confirmed_returns").addClass("active");
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
        $(document).ready(function() {
            $('#daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'MM/DD/YYYY'
                }
            });
        });

        function addReturn() {
            $('#confirm-modal').modal('show');
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/returns/confirmed.blade.php ENDPATH**/ ?>