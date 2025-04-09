

<?php $__env->startSection('css'); ?>
    <style>
        .card-collection {
            width: 18rem;
            margin: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 5px;
            text-align: center;
            background-color: #f9f9f9;
            transition: transform 0.3s ease;
            position: relative;
        }

        .card-collection:hover {
            transform: scale(1.05);
        }

        .card-collection h5 {
            font-weight: 450;
            font-size: 1.25rem;
            color: #333;
            margin-top: 20px;
        }

       

        .icon-btns .btn {
            padding: 5px;
        }

        .filter-section {
            border: 1px solid #ddd;
            padding: 20px;
            margin-top: 20px;
        }

        .badge {
            font-size: 0.9rem;
            margin-right: 5px;
        }

        .badge-active {
            background-color: #28a745;
        }

        .badge-not-started {
            background-color: #dc3545;
        }

        .badge-out-of-stock {
            background-color: #ffc107;
        }

        .badge-deactivated {
            background-color: #6c757d;
        }

        .action-btns .btn {
            margin-right: 5px;
        }

        .table th {
            background-color: #f8f9fa;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>All Collections</h1>

    <!-- Add Collection Button -->
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCollectionModal">Add Collection</button>
    </div>

    <!-- Modal for Adding Collection -->
    <div class="modal fade" id="addCollectionModal" tabindex="-1" aria-labelledby="addCollectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="<?php echo e(route('collections.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCollectionModalLabel">Add New Collection</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="collection_name" class="form-label">Collection Name</label>
                            <input type="text" class="form-control" id="collection_name" name="name" placeholder="Enter collection name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Collection</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for Editing Collection -->
    <div class="modal fade" id="editCollectionModal" tabindex="-1" aria-labelledby="editCollectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="editCollectionForm">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCollectionModalLabel">Edit Collection</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="collection_name_edit" class="form-label">Collection Name</label>
                            <input type="text" class="form-control" id="collection_name_edit" name="collection_name_edit" placeholder="Enter collection name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Collection</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <?php
        // Get all adCollections
        $adCollections = \App\Models\AdCollection::all();

        // Total counts for collections and ads
        $totalCollections = \App\Models\Collection::count();
        $totalProducts = \App\Models\Ad::count();

        // Use collection instance methods
        $totalActivated = $adCollections->where('status', 'Activated')->count();
        $totalNotStarted = $adCollections->where('status', 'Not Started')->count();
        $totalDone = $adCollections->where('status', 'Done')->count();
        $totalStocked = $adCollections->where('stock_status', 'Stocked')->count();
        $totalOut = $adCollections->where('stock_status', 'Out Of Stock')->count();
    ?>


    <div class="row mb-4">
        <div class="col-md-3">
            <span class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title" style="color:white;">Total Collections</h5>
                    <p class="card-text"><?php echo e($totalCollections); ?></p>
                </div>
            </span>
        </div>
        <div class="col-md-3">
            <span class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title" style="color:white;">Total Products</h5>
                    <p class="card-text"><?php echo e($totalProducts); ?></p>
                </div>
            </span>
        </div>
        <div class="col-md-3">
            <span class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title" style="color:white;">Total Activated</h5>
                    <p class="card-text"><?php echo e($totalActivated); ?></p>
                </div>
            </span>
        </div>
        <div class="col-md-3">
            <span class="card text-white bg-secondary mb-3">
                <div class="card-body">
                    <h5 class="card-title" style="color:white;">Total Activated</h5>
                    <p class="card-text"><?php echo e($totalNotStarted); ?></p>
                </div>
            </span>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <span class="card text-white bg-secondary mb-3">
                <div class="card-body">
                    <h5 class="card-title" style="color:white;">Total Done</h5>
                    <p class="card-text"><?php echo e($totalDone); ?></p>
                </div>
            </span>
        </div>
        <div class="col-md-4">
            <span class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title" style="color:white;">Total Stocked</h5>
                    <p class="card-text"><?php echo e($totalStocked); ?></p>
                </div>
            </span>
        </div>
        <div class="col-md-4">
            <span class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title" style="color:white;">Total Out Of Stock</h5>
                    <p class="card-text"><?php echo e($totalOut); ?></p>
                </div>
            </span>
        </div>
    </div>




    <!-- Ads Table Section -->
    <div class="mt-5">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Products</th>
                    <th>Active</th>
                    <th>Not Started</th>
                    <th>Need Stop</th>
                    <th>De-Active</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $collection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        // Raw queries to count products
                        $countProduct = \DB::select("SELECT COUNT(*) as count FROM ads_collections WHERE collection_id = ?", [$collection->id])[0]->count;
                        $countActive = \DB::select("SELECT COUNT(*) as count FROM ads_collections WHERE collection_id = ? AND status = ?", [$collection->id, 'Activated'])[0]->count;
                        $countNotStarted = \DB::select("SELECT COUNT(*) as count FROM ads_collections WHERE collection_id = ? AND status = ?", [$collection->id, 'Not Started'])[0]->count;
                        $countNeedStop = \DB::select("SELECT COUNT(*) as count FROM ads_collections WHERE collection_id = ? AND stock_status = ?", [$collection->id, 'Out Of Stock'])[0]->count;
                        $countDeactivate = \DB::select("SELECT COUNT(*) as count FROM ads_collections WHERE collection_id = ? AND status = ?", [$collection->id, 'Done'])[0]->count;
                    ?>
                    <tr>
                        <td><?php echo e($collection->name); ?></td>
                        <td>
                            <span class="badge badge-active"><?php echo e($countProduct); ?></span>
                        </td>
                        <td>
                            <span class="badge badge-active"><?php echo e($countActive); ?></span>
                        </td>
                        <td>
                            <span class="badge badge-not-started"><?php echo e($countNotStarted); ?></span>
                        </td>
                        <td>
                            <span class="badge badge-out-of-stock"><?php echo e($countNeedStop); ?></span>
                        </td>
                        <td>
                            <span class="badge badge-deactivated"><?php echo e($countDeactivate); ?></span>
                        </td>
                        <td>
                            <div class="icon-btns">
                                <button onclick="openEditModal(<?php echo e($collection->id); ?>, '<?php echo e($collection->name); ?>')" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="<?php echo e(route('collections.show', $collection->id)); ?>" class="btn btn-sm btn-info" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="<?php echo e(route('collections.destroy', $collection->id)); ?>" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this collection?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>    
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    $("ul#Ad").siblings('a').attr('aria-expanded', 'true');
    $("ul#Ad").addClass("show");
    $("#all_Collections").addClass("active");

    function openEditModal(id, name) {
        // Populate the modal input field with the collection name
        $('#collection_name_edit').val(name);
        
        // Set the action attribute for the form to point to the correct edit route
        $('#editCollectionForm').attr('action', '/collections/edit/' + id);
        
        // Show the modal
        $('#editCollectionModal').modal('show');
    }

    // function confirmDelete() {
    //     return confirm('Are you sure you want to delete this collection?');
    // }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/1216098.cloudwaysapps.com/ycnnbqfzvc/public_html/resources/views/ads/collection.blade.php ENDPATH**/ ?>