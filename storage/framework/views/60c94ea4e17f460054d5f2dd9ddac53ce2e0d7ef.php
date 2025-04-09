
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('assets/css/bootstrap-rtl.min.css')); ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="pagetitle">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold">Shipment Transactions</h1>
        <nav>
            <ol class="breadcrumb flex space-x-2">
                <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>" class="text-blue-600">Home</a></li>
                <li class="breadcrumb-item">Shipments</li>
            </ol>
        </nav>
    </div>
</div><!-- End Page Title -->

<section class="section mt-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="" id="sort_orders" method="GET" class="p-5">
                    <div class="grid grid-cols-12 gap-4 mb-5">
                        <div class="col-span-1 flex items-center">
                            <label class="text-sm font-medium"><?php echo e('Choose Transaction Date'); ?></label>
                        </div>
                        <div class="col-span-2">
                            <input type="date" class="form-control border-gray-300 rounded-md"
                                value="<?php echo e($date); ?>" name="date" placeholder="<?php echo e('Filter by Date'); ?>"
                                autocomplete="off">
                        </div>
                        <div class="col-span-4">
                            <input type="text" class="form-control border-gray-300 rounded-md" 
                                value="<?php echo e($search); ?>" name="search" placeholder="<?php echo e('Enter Transaction Reference or Shipment Number'); ?>">
                        </div>
                        <div class="col-span-2">
                            <select class="form-select border-gray-300 rounded-md" name="shipping" id="branch_id">
                                <option value="">Choose Status</option>
                                <option value="open" <?php if($status == 'open'): ?> selected <?php endif; ?>>Open</option>
                                <option value="closed" <?php if($status == 'closed'): ?> selected <?php endif; ?>>Closed</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <select class="form-select border-gray-300 rounded-md" name="sort_by" id="sort_by">
                                <option value="">Sort By</option>
                                <option value="success1" <?php if($sort_by == 'success1'): ?> selected <?php endif; ?>>Success (Low to High)</option>
                                <option value="failed1" <?php if($sort_by == 'failed1'): ?> selected <?php endif; ?>>Failed (Low to High)</option>
                                <option value="success2" <?php if($sort_by == 'success2'): ?> selected <?php endif; ?>>Success (High to Low)</option>
                                <option value="failed2" <?php if($sort_by == 'failed2'): ?> selected <?php endif; ?>>Failed (High to Low)</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse border border-gray-300">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="text-center py-3 px-4 border border-gray-300">Transaction Date</th>
                                    <th class="text-center py-3 px-4 border border-gray-300">Transaction Reference</th>
                                    <th class="text-center py-3 px-4 border border-gray-300">Shipment Number</th>
                                    <th class="text-center py-3 px-4 border border-gray-300">Created By</th>
                                    <th class="text-center py-3 px-4 border border-gray-300">Shipping Company</th>
                                    <th class="text-center py-3 px-4 border border-gray-300">Total Orders</th>
                                    <th class="text-center py-3 px-4 border border-gray-300">Total COD</th>
                                    <th class="text-center py-3 px-4 border border-gray-300">Total Website Price</th>
                                    <th class="text-center py-3 px-4 border border-gray-300">Total Shipping</th>
                                    <th class="text-center py-3 px-4 border border-gray-300">Total Website Shipping</th>
                                    <th class="text-center py-3 px-4 border border-gray-300">Total Net</th>
                                    <th class="text-center py-3 px-4 border border-gray-300">Total COD Difference</th>
                                    <th class="text-center py-3 px-4 border border-gray-300">Total Shipping Difference</th>
                                    <th class="text-center py-3 px-4 border border-gray-300">Successful Orders</th>
                                    <th class="text-center py-3 px-4 border border-gray-300">Failed Orders</th>
                                    <th class="text-center py-3 px-4 border border-gray-300">Note</th>
                                    <th class="text-center py-3 px-4 border border-gray-300">Status</th>
                                    <th class="text-right py-3 px-4 border border-gray-300" width="15%">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border-b">
                                        <td class="text-center py-4 px-4 border border-gray-300"><?php echo e(date('d/m/Y', strtotime($transaction->created_at))); ?></td>
                                        <td class="text-center py-4 px-4 border border-gray-300">#<?php echo e($transaction->transaction_number); ?></td>
                                        <td class="text-center py-4 px-4 border border-gray-300"><?php echo e($transaction->shipment_number); ?></td>
                                        <td class="text-center py-4 px-4 border border-gray-300"><?php echo e(optional($transaction->user)->name ?? '-'); ?></td>
                                        <td class="text-center py-4 px-4 border border-gray-300">BestExpress</td>
                                        <td class="text-center py-4 px-4 border border-gray-300"><?php echo e($transaction->total_orders); ?></td>
                                        <td class="text-center py-4 px-4 border border-gray-300"><?php echo e($transaction->total_cod); ?></td>
                                        <td class="text-center py-4 px-4 border border-gray-300"><?php echo e($transaction->details->sum('order_price')); ?></td>
                                        <td class="text-center py-4 px-4 border border-gray-300"><?php echo e($transaction->total_shipping); ?></td>
                                        <td class="text-center py-4 px-4 border border-gray-300"><?php echo e($transaction->details->sum('order_shipping')); ?></td>
                                        <td class="text-center py-4 px-4 border border-gray-300"><?php echo e($transaction->total_net); ?></td>
                                        <td class="text-center py-4 px-4 border border-gray-300"><?php echo e($transaction->details->sum('order_price') - $transaction->total_cod); ?></td>
                                        <td class="text-center py-4 px-4 border border-gray-300"><?php echo e($transaction->details->sum('order_shipping') - $transaction->shipping); ?></td>
                                        <td class="text-center py-4 px-4 border border-gray-300"><?php echo e($transaction->success_orders); ?></td>
                                        <td class="text-center py-4 px-4 border border-gray-300"><?php echo e($transaction->failed_orders); ?></td>
                                        <td class="text-center py-4 px-4 border border-gray-300"><?php echo e($transaction->note); ?></td>
                                        <td class="text-center py-4 px-4 border border-gray-300">
                                            <?php if(auth()->user()->role_id == 8): ?>
                                                <?php if($transaction->shipping_status == 'new'): ?>
                                                    <span class="bg-blue-500 text-white px-2 py-1 rounded"><?php echo e('New'); ?></span>
                                                <?php elseif($transaction->shipping_status == 'in_progress'): ?>
                                                    <span class="bg-yellow-500 text-white px-2 py-1 rounded"><?php echo e('In Progress'); ?></span>
                                                <?php else: ?>
                                                    <span class="bg-gray-800 text-white px-2 py-1 rounded"><?php echo e('Done'); ?></span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php if($transaction->status == 'open'): ?>
                                                    <span class="bg-green-500 text-white px-2 py-1 rounded"><?php echo e($transaction->status); ?></span>
                                                <?php else: ?>
                                                    <span class="bg-gray-800 text-white px-2 py-1 rounded"><?php echo e($transaction->status); ?></span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-right py-4 px-4 border border-gray-300">
                                            <div class="flex justify-center space-x-2">
                                                <?php if(auth()->user()->role_id != 8): ?>
                                                    <?php if($transaction->status == 'open'): ?>
                                                        <a class="bg-blue-500 text-white px-2 py-1 rounded" 
                                                            href="<?php echo e(route('shipping_trx.show', $transaction->id)); ?>" title="Close">
                                                            <i class="bi bi-door-open-fill"></i>
                                                        </a>
                                                        <span>Close</span>
                                                    <?php else: ?>
                                                        <a class="bg-yellow-500 text-white px-2 py-1 rounded" 
                                                            href="<?php echo e(route('shipping_trx.show', $transaction->id)); ?>" title="Show">
                                                            <i class="bi bi-eye-fill"></i>
                                                        </a>
                                                        <span>View</span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <a class="bg-blue-500 text-white px-2 py-1 rounded" 
                                                        download href="<?php echo e($transaction->sheet); ?>" title="Download">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                    <span>Download</span>
                                                    <?php if($transaction->shipping_status == 'new'): ?>
                                                        <a class="bg-red-500 text-white px-2 py-1 rounded" 
                                                            href="<?php echo e($transaction->sheet); ?>" title="Delete">
                                                            <i class="bi bi-x-square"></i>
                                                        </a>
                                                        <span>Delete</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-5">
                        <?php echo e($transactions->appends(request()->input())->links()); ?>

                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script type="text/javascript">
        $("ul#finance").siblings('a').attr('aria-expanded', 'true');
        $("ul#finance").addClass("show");
        $("#transactions").addClass("active");
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/finance/shipment_transactions.blade.php ENDPATH**/ ?>