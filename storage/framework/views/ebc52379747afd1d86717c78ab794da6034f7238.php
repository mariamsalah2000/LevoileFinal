
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('assets/css/bootstrap-rtl.min.css')); ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="pagetitle">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Shipment Stock Requests</h1>
        <nav>
            <ol class="flex space-x-2">
                <li><a href="<?php echo e(route('home')); ?>" class="text-blue-600 hover:underline">Home</a></li>
                <li class="text-gray-600">/ Shipments</li>
            </ol>
        </nav>
    </div>
</div><!-- End Page Title -->

<section class="py-6">
    <div class="max-w-7xl mx-auto">

        <div class="bg-white shadow-md rounded-lg">
            <form action="" id="sort_orders" method="GET">
                <div class="p-4 border-b">
                    <div class="flex flex-wrap space-x-4">

                        <div class="flex-1">
                            <label for="daterange" class="block mb-1 text-sm font-medium">Filter By Daterange</label>
                            <input type="text" value="<?php echo e($daterange); ?>" id="daterange" name="daterange" class="form-input w-full" />
                        </div>

                        <div class="flex-1">
                            <label for="search" class="block mb-1 text-sm font-medium">Search By Reference</label>
                            <input type="text" class="form-input w-full" value="<?php echo e($search); ?>" name="search" placeholder="<?php echo e('Enter Stock Request Reference'); ?>" />
                        </div>

                        <div class="flex-1">
                            <label for="branch_id" class="block mb-1 text-sm font-medium">Filter By Status</label>
                            <select class="form-select w-full" name="shipping" id="branch_id">
                                <option value="">Choose Status</option>
                                <option value="pending" <?php if($status == 'pending'): ?> selected <?php endif; ?>>Pending</option>
                                <option value="in_progress" <?php if($status == 'in_progress'): ?> selected <?php endif; ?>>In Progress</option>
                                <option value="closed" <?php if($status == 'closed'): ?> selected <?php endif; ?>>Closed</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto p-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-center py-3 px-4">Stock Request Date</th>
                                <th class="text-center py-3 px-4">Stock Request Reference</th>
                                <th class="text-center py-3 px-4">Branch Name</th>
                                <th class="text-center py-3 px-4">Created By</th>
                                <th class="text-center py-3 px-4">No of Products</th>
                                <th class="text-center py-3 px-4">Total Quantity</th>
                                <th class="text-center py-3 px-4">Note</th>
                                <th class="text-center py-3 px-4">Downloaded At</th>
                                <th class="text-center py-3 px-4">Status</th>
                                <th class="text-right py-3 px-4" width="25%">Options</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $stockRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $stockRequest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center py-3 px-4"><?php echo e(date('d/m/Y', strtotime($stockRequest->created_at))); ?></td>
                                    <td class="text-center py-3 px-4"><?php echo e($stockRequest->ref); ?></td>
                                    <td class="text-center py-3 px-4"><?php echo e($stockRequest->branch->name); ?></td>
                                    <td class="text-center py-3 px-4"><?php echo e(optional($stockRequest->user)->name ?? '-'); ?></td>
                                    <td class="text-center py-3 px-4"><?php echo e($stockRequest->total_products); ?></td>
                                    <td class="text-center py-3 px-4"><?php echo e($stockRequest->total_quantity); ?></td>
                                    <td class="text-center py-3 px-4"><?php echo e($stockRequest->note); ?></td>
                                    <td class="text-center py-3 px-4"><?php echo e($stockRequest->downloaded_at ? date('d/m/Y', strtotime($stockRequest->downloaded_at)) : '-'); ?></td>
                                    <td class="text-center py-3 px-4">
                                        <?php if($stockRequest->status == "pending"): ?>
                                        <span class="inline-block bg-yellow-50 text-yellow-600 text-sm font-medium px-4 py-2 rounded-full">
                                            <?php echo e($stockRequest->status); ?>

                                        </span>
                                        <?php else: ?>
                                        <span class="inline-block bg-green-50 text-green-600 text-sm font-medium px-4 py-2 rounded-full">
                                            <?php echo e($stockRequest->status); ?>

                                        </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right py-3 px-4">
                                        <div class="flex flex-col items-center space-y-1">
                                            <?php if($stockRequest->status == 'pending' && auth()->user()->role->name == 'Branches'): ?>
                                                <a class="btn btn-primary text-xs" href="<?php echo e(route('stock_requests.edit', $stockRequest->id)); ?>" title="Close">
                                                    <i class="bi bi-edit-fill"></i> Edit
                                                </a>
                                            <?php endif; ?>
                                            <a class="btn btn-warning text-xs" href="<?php echo e(route('stock_requests.show', $stockRequest->id)); ?>" title="Show">
                                                <i class="bi bi-eye-fill"></i> View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>

                    <div class="mt-4">
                        <?php echo e($stockRequests->appends(request()->input())->links()); ?>

                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script type="text/javascript">
        $("ul#branches").siblings('a').attr('aria-expanded', 'true');
        $("ul#branches").addClass("show");
        $("#requests").addClass("active");

        $(document).ready(function() {
            $('#daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'MM/DD/YYYY'
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/stock_requests/index.blade.php ENDPATH**/ ?>