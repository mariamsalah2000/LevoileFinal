
<?php $__env->startSection('content'); ?>
    <div class="pagetitle">
        <div class="row">
            <div class="col-8">
                <h1>Products</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>">Home</a></li>
                        <li class="breadcrumb-item">Products</li>
                    </ol>
                </nav>
            </div>
            <div class="col-4">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td><a href="<?php echo e(route('shopify.product.create')); ?>" style="float:right"
                                    class="btn btn-success">Create Product</a></td>
                            <td><a href="<?php echo e(route('shopify.products.sync')); ?>" style="float: right"
                                    class="btn btn-primary">Sync Products</a></td>
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
                    <div class="card-body">
                        <h5 class="card-title">Your products</h5>
                        
                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Vendor</th>
                                    <th scope="col">Created Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($products)): ?>
                                    <?php if($products !== null): ?>
                                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($key + 1); ?></td>
                                                <td><?php echo e($product['title']); ?></td>
                                                <td><?php echo e($product['product_type']); ?></td>
                                                <td><?php echo e($product['vendor']); ?></td>
                                                <td><?php echo e(date('Y-m-d', strtotime($product['created_at']))); ?></td>
                                                <td><a href="<?php echo e(route('change.product.addToCart')); ?>?product_id=<?php echo e($product['table_id']); ?>"
                                                        class="btn btn-primary"><?php echo e($product->getAddToCartStatus()['message']); ?></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->

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
        $("#products").addClass("active");
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/products/index.blade.php ENDPATH**/ ?>