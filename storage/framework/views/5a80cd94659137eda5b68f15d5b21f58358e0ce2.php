

<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h1 class="mb-4"><?php echo e($productName); ?>'s Variants</h1>
    <!-- Card for displaying tickets -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5>List of Variants</h5>
        </div>
        <div class="card-body">
            <?php if($variants->isEmpty()): ?>
                <div class="alert alert-warning" role="alert">
                    No Variants found for <?php echo e($productName); ?>.
                </div>
            <?php else: ?>
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Title</th>
                            <th>Image</th>
                            <th>price</th>
                            <th>Quantity</th>
                            <th>Color</th>
                            <th>Sizes</th>
                            <th>Materials</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $product = \DB::table('products')->where('id', $variant->product_id)->first();
                                $images = json_decode($product->images, true); // Assuming images are stored as JSON
                                foreach($images as $image){
                                    if($image['id'] == $variant->image_id){
                                        $imageSrc = $image['src'];
                                    }else{
                                        $imageSrc = 'N/A';
                                    }
                                }
                            ?>
                            <tr>
                            <td><?php echo e($variant->title); ?></td>
                            <td>
                                <?php if($imageSrc !== 'N/A'): ?>
                                    <img src="<?php echo e($imageSrc); ?>" alt="Product Image" style="max-width: 100px; max-height: 100px;">
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($variant->price); ?></td>
                            <td><?php echo e($variant->inventory_quantity); ?></td>
                            <td><?php echo e($variant->option1); ?></td>
                            <td><?php echo e($variant->option2); ?></td>
                            <td><?php echo e($variant->option3); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="<?php echo e(route('ads.index')); ?>" class="btn btn-secondary">Back to All Ads</a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Any additional JavaScript if needed
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/ads/variant.blade.php ENDPATH**/ ?>