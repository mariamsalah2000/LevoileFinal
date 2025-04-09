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

        .icon-btns {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .icon-btns .btn {
            padding: 5px;
        }

        .filter-section {
            border: 1px solid #ddd;
            padding: 20px;
            margin-top: 20px;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .action-btns .btn {
            margin-right: 5px;
        }

        .wide-column {
            min-width: 300px;
            /* Adjust width as needed */
            white-space: nowrap;
            /* Prevents line breaks */
        }

        .product-info {
            position: relative;
            padding-left: 20px;
        }

        .product-title {
            font-weight: bold;
        }

        .nested-details {
            position: relative;
            padding-left: 20px;
            border-left: 2px solid #ddd;
            /* Main vertical line */
            margin-top: 8px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            padding: 4px 0;
            margin-right: 15px;
            position: relative;
        }

        .detail-line {
            width: 20px;
            /* Length of the pointer line */
            height: 1px;
            background-color: #ddd;
            margin-right: 10px;
            position: relative;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <h1>All Ads</h1>

        <!-- Add Collection Button -->
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCollectionModal">Add Collection</button>
        </div>

        <!-- Modal for Adding Collection -->
        <div class="modal fade" id="addCollectionModal" tabindex="-1" aria-labelledby="addCollectionModalLabel"
            aria-hidden="true">
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
                                <input type="text" class="form-control" id="collection_name" name="name"
                                    placeholder="Enter collection name" required>
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

        <!-- Cards for Each Collection -->
        <div class="row">
            @foreach ($collections as $collection)
                <div class="col-md-2">
                    <a href="{{ route('collections.show', $collection->id) }}">

                        <div class="card card-collection" style="width:180px;">
                            <small>
                                <h5 class="card-title">{{ $collection->name }}</h5>
                            </small>
                            <!-- <div class="icon-btns">
                                                                <button onclick="openEditModal({{ $collection->id }}, '{{ $collection->name }}')" class="btn btn-sm btn-warning">
                                                                    <i class="bi bi-pencil"></i>
                                                                </button>
                                                                <form action="{{ route('collections.destroy', $collection->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirmDelete();">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div> -->
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Modal for Editing Collection -->
        <div class="modal fade" id="editCollectionModal" tabindex="-1" aria-labelledby="editCollectionModalLabel"
            aria-hidden="true">
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
                                <input type="text" class="form-control" id="collection_name_edit"
                                    name="collection_name_edit" placeholder="Enter collection name" required>
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




        <!-- Filter Section -->
        <div class="filter-section">
            <h5>Filter</h5>
            <form method="GET" action="{{ route('ads.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="name" class="form-control" placeholder="Search by Name"
                            value="{{ request('name') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Choose status</option>
                            <option value="Not Started" {{ request('status') == 'Not Started' ? 'selected' : '' }}>Not
                                Started</option>
                            <option value="Activated" {{ request('status') == 'Activated' ? 'selected' : '' }}>Activated
                            </option>
                            <option value="Done" {{ request('status') == 'Done' ? 'selected' : '' }}>Done</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="stock_status" class="form-select">
                            <option value="">Choose Stock Status</option>
                            <option value="Stocked" {{ request('stock_status') == 'Stocked' ? 'selected' : '' }}>Stocked
                            </option>
                            <option value="Out Of Stock"
                                {{ request('stock_status') == 'Out Of Stock' ? 'selected' : '' }}>Out Of Stock</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="collection_id" class="form-select">
                            <option value="">Collection</option>
                            @foreach ($collections as $collection)
                                <option value="{{ $collection->id }}"
                                    {{ request('collection_id') == $collection->id ? 'selected' : '' }}>
                                    {{ $collection->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <input type="date" name="from_date" class="form-control"
                                value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-6 mt-3">
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-secondary">Filter</button>
                        <a href="{{ route('ads.index') }}" class="btn btn-success">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <button
            onclick="exportTableToExcel('adsTable', new Date().toISOString().slice(0, 10) + '_' + new Date().toTimeString().slice(0, 8).replace(/:/g, '-')+'_ads_data')"
            class="btn btn-success mt-1 mb-2" style="float:right;">Export</button>

        <!-- Ads Table Section -->
        <div class="mt-5">
            <table id="adsTable" class="table table-bordered table-striped table-hover">
                <thead style="background-color:#4154f1;color:white;">
                    <tr>
                        <th>Created</th>
                        <th>Image</th>
                        <th class="wide-column">Name & Details</th>
                        <th>Collection</th>
                        <th>Materials</th>
                        <th>Status</th>
                        <th>Availability</th>
                        <th>Activated</th>
                        <th>De-Activated</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($adsCollections as $adCollection)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($adCollection->created_at)->format('F j, Y h:i A') }}</td>

                            <!-- Image Column -->
                            @php
                                $images = json_decode($adCollection->ad->product->images, true);
                                $imageSrc = !empty($images) && isset($images[0]['src']) ? $images[0]['src'] : 'N/A';
                            @endphp
                            <td>
                                @if ($imageSrc !== 'N/A')
                                    <img src="{{ $imageSrc }}" alt="Product Image" class="img-thumbnail"
                                        style="max-width: 100px;">
                                @else
                                    N/A
                                @endif
                            </td>

                            <!-- Combined Name & Details Column -->
                            <td>
                                <div class="product-info">
                                    <strong class="product-title">{{ $adCollection->ad->product->title }}</strong>
                                    <div class="nested-details mt-2">
                                        @php
                                            $product_id = $adCollection->ad->product_id;
                                            $variants = \App\Models\ProductVariant::where('product_id', $product_id);
                                            $variationCount = $variants->count();
                                            //$variants = json_decode($adCollection->ad->product->variants, true);
                                            $price = $variants ? $variants->sum('price') : 'N/A';
                                            $inventory_quantity = $variants
                                                ? $variants->sum('inventory_quantity')
                                                : 'N/A';
                                        @endphp
                                        <div class="detail-item">
                                            <span class="detail-line"></span>
                                            <strong>Variations:</strong> <a
                                                href="{{ route('ads.product.variant', $product_id) }}">{{ $variationCount == 0 ? 'N/A' : $variationCount }}</a>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-line"></span>
                                            <strong>Price:</strong> {{ $price }} LE
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-line"></span>
                                            <strong>Quantity:</strong> {{ $inventory_quantity }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Collection Column -->
                            <td>{{ $adCollection->collection->name ?? 'N/A' }}</td>

                            <!-- Links & Contents Column -->
                            <td>
                                <a href="#" class="link" data-bs-toggle="modal" data-bs-target="#linkModal"
                                    data-drive-links="{{ $adCollection->ad->drive_links }}"
                                    data-ig-links="{{ $adCollection->ad->ig_links }}"
                                    data-content="{{ $adCollection->ad->content }}">
                                    View All
                                </a>
                            </td>

                            <!-- Status Column -->
                            <td>
                                <span
                                    class="badge {{ $adCollection->status === 'activated' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($adCollection->status) }}
                                </span>
                            </td>

                            <!-- Stock Status Column -->
                            <td>
                                <span
                                    class="badge {{ $adCollection->stock_status === 'Stocked' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $adCollection->stock_status }}
                                </span>
                            </td>

                            <!-- Activated & Deactivated Dates -->
                            <td>{{ $adCollection->activated_at ? \Carbon\Carbon::parse($adCollection->activated_at)->format('F j, Y h:i A') : '' }}
                            </td>
                            <td>{{ $adCollection->deactivated_at ? \Carbon\Carbon::parse($adCollection->deactivated_at)->format('F j, Y h:i A') : '' }}
                            </td>

                            <!-- Action Column -->
                            <td>
                                @if (auth()->user()->role_id == 13 || auth()->user()->role_id == 1)
                                    @if ($adCollection->stock_status === 'Stocked')
                                        <form
                                            action="{{ route('ads.ask.to.close', ['adId' => $adCollection->ad_id, 'collectionId' => $adCollection->collection_id]) }}"
                                            method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Ask To Close</button>
                                        </form>
                                    @elseif($adCollection->stock_status === 'Out Of Stock' && $adCollection->status !== 'Done')
                                        <form
                                            action="{{ route('ads.cancel.close', ['adId' => $adCollection->ad_id, 'collectionId' => $adCollection->collection_id]) }}"
                                            method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success">Cancel Close</button>
                                        </form>
                                    @elseif($adCollection->stock_status === 'Out Of Stock' && $adCollection->status === 'Done')
                                        <form
                                            action="{{ route('ads.withdraw.ask.to.close', ['adId' => $adCollection->ad_id, 'collectionId' => $adCollection->collection_id]) }}"
                                            method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary">Publish Again</button>
                                        </form>
                                    @endif
                                    <br>
                                    <form
                                        action="{{ route('ads.edit', ['adId' => $adCollection->ad_id, 'collectionId' => $adCollection->collection_id]) }}"
                                        method="get" style="display: inline;">

                                        <button type="submit" class="btn btn-secondary m-2">Edit</button>
                                    </form>
                                    <br>
                                @endif
                                @if (auth()->user()->role_id == 9 || auth()->user()->role_id == 1)
                                    @if ($adCollection->status === 'Not Started')
                                        <form
                                            action="{{ route('ads.start', ['adId' => $adCollection->ad_id, 'collectionId' => $adCollection->collection_id]) }}"
                                            method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary">Activate</button>
                                        </form>
                                    @elseif($adCollection->status === 'Activated')
                                        <form
                                            action="{{ route('ads.stop', ['adId' => $adCollection->ad_id, 'collectionId' => $adCollection->collection_id]) }}"
                                            method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success">De-Activate</button>
                                        </form>
                                    @else
                                        <form
                                            action="{{ route('ads.destroy', ['adId' => $adCollection->ad_id, 'collectionId' => $adCollection->collection_id]) }}"
                                            method="POST" style="display: inline;"
                                            onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        <!-- Modal -->
        <div class="modal fade" id="linkModal" tabindex="-1" aria-labelledby="linkModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="linkModalLabel">Links and Content</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3" id="driveLinksSection">
                            <strong>Drive Links:</strong>
                            <ul id="modalDriveLinks" class="list-unstyled"></ul>
                        </div>
                        <div class="mb-3" id="igLinksSection">
                            <strong>Instagram Links:</strong>
                            <ul id="modalIgLinks" class="list-unstyled"></ul>
                        </div>
                        <div class="mb-3" id="contentSection">
                            <strong>Content:</strong>
                            <div id="modalContent"></div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>



    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>


    <script>
        $("ul#Ad").siblings('a').attr('aria-expanded', 'true');
        $("ul#Ad").addClass("show");
        $("#all_Ads").addClass("active");

        function openEditModal(id, name) {
            // Populate the modal input field with the collection name
            $('#collection_name_edit').val(name);

            // Set the action attribute for the form to point to the correct edit route
            $('#editCollectionForm').attr('action', '/collections/edit/' + id);

            // Show the modal
            $('#editCollectionModal').modal('show');
        }

        function confirmDelete() {
            return confirm('Are you sure you want to delete this collection?');
        }
    </script>
    <script>
        $(document).ready(function() {
            // When a link is clicked, show the corresponding links or content in the modal
            $('.link').on('click', function() {
                var driveLinks = $(this).data('drive-links');
                var igLinks = $(this).data('ig-links');
                var content = $(this).data('content');

                // Clear modal content
                $('#modalDriveLinks').empty();
                $('#modalIgLinks').empty();
                $('#modalContent').empty();

                // Check if Drive links are available
                if (driveLinks) {
                    var driveLinkArray = driveLinks.split(','); // Split links by comma
                    driveLinkArray.forEach(function(link) {
                        $('#modalDriveLinks').append('<li><a href="' + link.trim() +
                            '" target="_blank">' + link.trim() + '</a></li>');
                    });
                } else {
                    $('#modalDriveLinks').html('<li>No Drive links available.</li>');
                }

                // Check if Instagram links are available
                if (igLinks) {
                    var igLinkArray = igLinks.split(','); // Split links by comma
                    igLinkArray.forEach(function(link) {
                        $('#modalIgLinks').append('<li><a href="' + link.trim() +
                            '" target="_blank">' + link.trim() + '</a></li>');
                    });
                } else {
                    $('#modalIgLinks').html('<li>No Instagram links available.</li>');
                }

                // Display content if available
                if (content) {
                    $('#modalContent').html(content.replace(/,/g,
                        '<br>')); // Replace commas with line breaks for better formatting
                } else {
                    $('#modalContent').html('No content available.');
                }
            });
        });
    </script>

    <script>
        function exportTableToExcel(tableID, filename = '') {
            // Clone the original table
            const originalTable = document.getElementById(tableID);
            const clonedTable = originalTable.cloneNode(true);

            // Locate the index of the "Action" column dynamically
            let actionColumnIndex = -1;
            clonedTable.querySelectorAll('thead tr th').forEach((header, index) => {
                if (header.textContent.trim() === 'Action') {
                    actionColumnIndex = index;
                }
            });

            // Ensure "Action" column was found before attempting to remove it
            if (actionColumnIndex !== -1) {
                // Remove the "Action" header cell
                clonedTable.querySelectorAll('thead tr').forEach(row => {
                    row.deleteCell(actionColumnIndex);
                });

                // Remove the "Action" cell in each body row
                clonedTable.querySelectorAll('tbody tr').forEach(row => {
                    row.deleteCell(actionColumnIndex);
                });
            }

            // Replace image elements with their 'src' URLs in the cloned table
            clonedTable.querySelectorAll('tbody tr').forEach(row => {
                row.querySelectorAll('td').forEach(cell => {
                    const img = cell.querySelector('img');
                    if (img) {
                        // Replace cell content with image src
                        cell.textContent = img.src;
                    }
                });
            });

            // Convert the modified cloned table to a worksheet
            const worksheet = XLSX.utils.table_to_sheet(clonedTable);

            // Create a new workbook and append the worksheet
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

            // Use the provided filename or default
            filename = filename ? filename + '.xlsx' : 'exported_data.xlsx';

            // Export the file
            XLSX.writeFile(workbook, filename);
        }
    </script>
@endsection
