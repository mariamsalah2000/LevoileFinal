@extends('layouts.app')

@section('css')
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
@endsection

@section('content')
<div class="container">
    <h1>All Collections</h1>

    <!-- Add Collection Button -->
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCollectionModal">Add Collection</button>
    </div>

    <!-- Modal for Adding Collection -->
    <div class="modal fade" id="addCollectionModal" tabindex="-1" aria-labelledby="addCollectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" action="{{ route('collections.store') }}">
                @csrf
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
                @csrf
                @method('PUT')
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


    @php
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
    @endphp


    <div class="row mb-4">
        <div class="col-md-3">
            <span class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title" style="color:white;">Total Collections</h5>
                    <p class="card-text">{{ $totalCollections }}</p>
                </div>
            </span>
        </div>
        <div class="col-md-3">
            <span class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title" style="color:white;">Total Products</h5>
                    <p class="card-text">{{ $totalProducts }}</p>
                </div>
            </span>
        </div>
        <div class="col-md-3">
            <span class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title" style="color:white;">Total Activated</h5>
                    <p class="card-text">{{ $totalActivated }}</p>
                </div>
            </span>
        </div>
        <div class="col-md-3">
            <span class="card text-white bg-secondary mb-3">
                <div class="card-body">
                    <h5 class="card-title" style="color:white;">Total Activated</h5>
                    <p class="card-text">{{ $totalNotStarted }}</p>
                </div>
            </span>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <span class="card text-white bg-secondary mb-3">
                <div class="card-body">
                    <h5 class="card-title" style="color:white;">Total Done</h5>
                    <p class="card-text">{{ $totalDone }}</p>
                </div>
            </span>
        </div>
        <div class="col-md-4">
            <span class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5 class="card-title" style="color:white;">Total Stocked</h5>
                    <p class="card-text">{{ $totalStocked }}</p>
                </div>
            </span>
        </div>
        <div class="col-md-4">
            <span class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title" style="color:white;">Total Out Of Stock</h5>
                    <p class="card-text">{{ $totalOut }}</p>
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
                @foreach ($collections as $collection)
                    @php
                        // Raw queries to count products
                        $countProduct = \DB::select("SELECT COUNT(*) as count FROM ads_collections WHERE collection_id = ?", [$collection->id])[0]->count;
                        $countActive = \DB::select("SELECT COUNT(*) as count FROM ads_collections WHERE collection_id = ? AND status = ?", [$collection->id, 'Activated'])[0]->count;
                        $countNotStarted = \DB::select("SELECT COUNT(*) as count FROM ads_collections WHERE collection_id = ? AND status = ?", [$collection->id, 'Not Started'])[0]->count;
                        $countNeedStop = \DB::select("SELECT COUNT(*) as count FROM ads_collections WHERE collection_id = ? AND stock_status = ?", [$collection->id, 'Out Of Stock'])[0]->count;
                        $countDeactivate = \DB::select("SELECT COUNT(*) as count FROM ads_collections WHERE collection_id = ? AND status = ?", [$collection->id, 'Done'])[0]->count;
                    @endphp
                    <tr>
                        <td>{{ $collection->name }}</td>
                        <td>
                            <span class="badge badge-active">{{ $countProduct }}</span>
                        </td>
                        <td>
                            <span class="badge badge-active">{{ $countActive }}</span>
                        </td>
                        <td>
                            <span class="badge badge-not-started">{{ $countNotStarted }}</span>
                        </td>
                        <td>
                            <span class="badge badge-out-of-stock">{{ $countNeedStop }}</span>
                        </td>
                        <td>
                            <span class="badge badge-deactivated">{{ $countDeactivate }}</span>
                        </td>
                        <td>
                            <div class="icon-btns">
                                <button onclick="openEditModal({{ $collection->id }}, '{{ $collection->name }}')" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="{{ route('collections.show', $collection->id) }}" class="btn btn-sm btn-info" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('collections.destroy', $collection->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this collection?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>    
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
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
@endsection
