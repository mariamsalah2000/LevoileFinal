
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('assets/css/bootstrap-rtl.min.css')); ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="container mx-auto py-8 px-4">
    <!-- Page Title Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Shipping Transaction Details</h1>
        <nav>
            <ol class="flex space-x-2 text-sm text-gray-600">
                <li><a href="<?php echo e(route('home')); ?>" class="text-blue-600 hover:underline">Home</a></li>
                <li>/</li>
                <li>Transaction Details</li>
            </ol>
        </nav>
    </div>

    <section class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <!-- Success Products Section -->
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-xl font-semibold text-gray-800">Success Orders</h4>
            <a href="<?php echo e(route('shipping_trx.export_success', $trx->id)); ?>" class="bg-yellow-500 text-white px-5 py-2 rounded-lg hover:bg-yellow-600 transition duration-150">
                Export
            </a>
        </div>
        <div class="overflow-hidden rounded-lg border border-gray-200 shadow">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <!-- Table Headers -->
                        <?php $__currentLoopData = ['#', 'Order Number', 'Website Price', 'COD', 'Shipping', 'Website Shipping', 'Net', 'COD Difference', 'Shipping Difference', 'Reason', 'Status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase"><?php echo e($header); ?></th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__currentLoopData = $details->where('status', 'success'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $success): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-center"><?php echo e($key + 1); ?></td>
                            <td class="px-6 py-4 text-center"><?php echo e($success->order_id); ?></td>
                            <td class="px-6 py-4 text-center"><?php echo e($success->order_price); ?> LE</td>
                            <td class="px-6 py-4 text-center"><?php echo e($success->cod); ?> LE</td>
                            <td class="px-6 py-4 text-center"><?php echo e($success->shipping); ?> LE</td>
                            <td class="px-6 py-4 text-center"><?php echo e($success->order_shipping); ?> LE</td>
                            <td class="px-6 py-4 text-center"><?php echo e($success->net); ?> LE</td>
                            <td class="px-6 py-4 text-center"><?php echo e($success->order_price - $success->cod); ?> LE</td>
                            <td class="px-6 py-4 text-center"><?php echo e($success->order_shipping - $success->shipping); ?> LE</td>
                            <td class="px-6 py-4 text-center"><?php echo e($success->reason); ?></td>
                            <td class="px-6 py-4 text-center"><?php echo e($success->order_status); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <!-- Footer Totals -->
                        <th colspan="2" class="px-6 py-3 text-center font-semibold">Totals</th>
                        <td class="px-6 py-3 text-center"><?php echo e($details->where('status', 'success')->sum('order_price')); ?> LE</td>
                        <td class="px-6 py-3 text-center"><?php echo e($details->where('status', 'success')->sum('cod')); ?> LE</td>
                        <td class="px-6 py-3 text-center"><?php echo e($details->where('status', 'success')->sum('shipping')); ?> LE</td>
                        <td class="px-6 py-3 text-center"><?php echo e($details->where('status', 'success')->sum('order_shipping')); ?> LE</td>
                        <td class="px-6 py-3 text-center"><?php echo e($details->where('status', 'success')->sum('net')); ?> LE</td>
                        <td class="px-6 py-3 text-center"><?php echo e($details->where('status', 'success')->sum('order_price') - $details->where('status', 'success')->sum('cod')); ?> LE</td>
                        <td class="px-6 py-3 text-center"><?php echo e($details->where('status', 'success')->sum('order_shipping') - $details->where('status', 'success')->sum('shipping')); ?> LE</td>
                        <td colspan="2" class="px-6 py-3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>

    <!-- Failed Products Section -->
    <section class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-xl font-semibold text-gray-800">Failed Orders</h4>
            <div class="flex space-x-4">
                <a href="<?php echo e(route('shipping_trx.export_failed', $trx->id)); ?>" class="bg-yellow-500 text-white px-5 py-2 rounded-lg hover:bg-yellow-600 transition duration-150">
                    Export
                </a>
                <a href="<?php echo e(route('shipping_trx.check_failed', $trx->id)); ?>" class="bg-gray-200 text-gray-800 px-5 py-2 rounded-lg hover:bg-gray-300 transition duration-150">
                    Check All
                </a>
            </div>
        </div>
        <div class="overflow-hidden rounded-lg border border-gray-200 shadow">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <?php $__currentLoopData = ['#', 'Order Number', 'Website Price', 'COD', 'Shipping', 'Website Shipping', 'Net', 'COD Difference', 'Shipping Difference', 'Reason', 'Action']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase"><?php echo e($header); ?></th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__currentLoopData = $details->where('status', 'failed'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $failed): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-center"><?php echo e($key + 1); ?></td>
                            <td class="px-6 py-4 text-center"><?php echo e($failed->order_id); ?></td>
                            <td class="px-6 py-4 text-center"><?php echo e($failed->order_price); ?> LE</td>
                            <td class="px-6 py-4 text-center"><?php echo e($failed->cod); ?> LE</td>
                            <td class="px-6 py-4 text-center"><?php echo e($failed->shipping); ?> LE</td>
                            <td class="px-6 py-4 text-center"><?php echo e($failed->order_shipping); ?> LE</td>
                            <td class="px-6 py-4 text-center"><?php echo e($failed->net); ?> LE</td>
                            <td class="px-6 py-4 text-center"><?php echo e($failed->order_price - $failed->cod); ?> LE</td>
                            <td class="px-6 py-4 text-center"><?php echo e($failed->order_shipping - $failed->shipping); ?> LE</td>
                            <td class="px-6 py-4 text-center"><?php echo e($failed->reason ?? '-'); ?></td>
                            <td class="px-6 py-4 text-center">
                                <?php if($trx->status == 'open'): ?>
                                    <button class="bg-green-500 text-white px-3 py-2 rounded-lg hover:bg-green-600 transition duration-150" onclick="approveTrxItem2(<?php echo e($failed->id); ?>)">
                                        Approve
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Submit Form for Open Transactions -->
    <?php if($trx->status == 'open'): ?>
        <form action="<?php echo e(route('shipping_trx.submit')); ?>" method="post" class="flex justify-end">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="trx_id" value="<?php echo e($trx->id); ?>">
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition duration-150">
                Submit
            </button>
        </form>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script type="text/javascript">
        $("ul#finance").siblings('a').attr('aria-expanded', 'true');
        $("ul#finance").addClass("show");
        $("#transactions").addClass("active");


        function approveTrxItem(id) {
            $.ajax({

                url: "<?php echo e(route('shipping_trx.approve')); ?>",
                type: 'GET',
                data: {
                    id: id
                },
                success: function(response) {
                    if (response.message == '') {
                        location.reload();
                    } else {
                        location.reload();
                    }
                }
            });
        }

        function approveTrxItem2(id) {
            var item = "approve_item_" + id;
            console.log(id, item);
            document.getElementById(item).style.display = "block";
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/finance/show_shipment_transaction.blade.php ENDPATH**/ ?>