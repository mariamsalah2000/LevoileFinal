@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css">
    <style>
        .bootstrap-tagsinput {
            width: 100%;
        }

        .label-info {
            background-color: #5bc0de;
        }

        /* Loading spinner styles */
        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-radius: 50%;
            border-top: 4px solid #3498db;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            display: none;
            margin: 0 auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <h2 class="my-4">Create New Ad</h2>

        <!-- Product Information Preview (Initially hidden) -->
        <div id="product-info" class="mt-5">
            <h4>Product Information</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td id="product_id_info">{{ $product->id }}</td>
                        <td><img id="product_image_info" src="{{ $product->images[0] ? $product->images[0]['src'] : 'N/A' }}"
                                alt="Product Image" style="width: 100px; height: auto;"></td>
                        <td id="product_name_info">{{ $product->title }}</td>
                        <td id="product_price_info">{{ $product->variants[0]['price'] || 'N/A' }}</td>
                        <td id="product_quantity_info">{{ $product->variants[0]['inventory_quantity'] || 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>


        <!-- Form for Ad Creation -->
        <form id="adForm">
            <!-- Hidden input to store the selected product_id -->
            <input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">

            <!-- Current Collections with Checkboxes -->
            <div class="mb-3">
                <div class="row">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="collections" class="form-label">Assign to Collections</label>
                        <a href="#" id="add-collection-link" data-bs-toggle="modal"
                            data-bs-target="#addCollectionModal" class="d-flex align-items-center">
                            <i class="bi bi-plus-circle"></i> <!-- Bootstrap Icon for plus -->
                            Add New Collection
                        </a>
                    </div>
                </div>
                <div id="collections-list" class="row"></div>
            </div>


            <!-- Drive Links -->
            <div class="mb-3">
                <label for="drive_links" class="form-label">Drive Links</label>
                <textarea class="form-control" id="drive_links" name="drive_links" placeholder="Enter Drive links, separated by commas">{{ $ad->drive_links }}</textarea>
            </div>

            <!-- IG Links -->
            <div class="mb-3">
                <label for="ig_links" class="form-label">IG Links</label>
                <textarea class="form-control" id="ig_links" name="ig_links" placeholder="Enter IG links, separated by commas">{{ $ad->ig_links }}</textarea>
            </div>

            <!-- Content -->
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" placeholder="Enter Content, separated by commas">{{ $ad->content }}</textarea>
            </div>

            <!-- Submit Button -->
            <button type="button" id="submitAd" class="btn btn-primary">Update Ad</button>
        </form>

    </div>

    <!-- Modal for Adding New Collection -->
    <div class="modal fade" id="addCollectionModal" tabindex="-1" aria-labelledby="addCollectionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCollectionModalLabel">Add New Collection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="collection_name" class="form-label">Collection Name</label>
                        <input type="text" class="form-control" id="collection_name" placeholder="Enter Collection Name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveCollection">Save Collection</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("ul#Ad").siblings('a').attr('aria-expanded', 'true');
            $("ul#Ad").addClass("show");
            $("#add_Ad").addClass("active");

            // Submit the Ad form via AJAX
            $('#submitAd').on('click', function() {
                document.getElementById('submitAd').disabled = true;
                const productId = $('#product_id').val();
                const driveLinks = $('#drive_links').val();
                const igLinks = $('#ig_links').val();
                const content = $('#content').val();
                const collections = $('input:checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                const adId = {{ $ad->id }};

                $.ajax({
                    url: '/ads/' + adId + '/update',
                    type: 'POST',
                    data: {
                        product_id: productId,
                        drive_links: driveLinks,
                        ig_links: igLinks,
                        content: content,
                        collections: collections,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        alert('Ad Updated successfully');
                        window.location.reload();
                    },
                    error: function() {
                        document.getElementById('submitAd').disabled = false;
                        alert('Error Updating Ad. Please try again.');
                    }
                });
            });



            // Load collections on page load
            loadCollections();

            // Load collections
            function loadCollections() {
                var collection_id = {{ $collection->id }};
                $.ajax({
                    url: '/get-collections',
                    type: 'GET',
                    success: function(response) {
                        const collections = response.collections;
                        $('#collections-list').empty();
                        collections.forEach(function(collection) {
                            $('#collections-list').append(`
                            <div class="form-check col-md-2">
                                <input class="form-check-input" type="checkbox" ${collection_id == collection.id ? 'checked' : ''} value="${collection.id}" id="collection_${collection.id}" name="collections[]">
                                <label class="form-check-label" for="collection_${collection.id}">${collection.name}</label>
                            </div>
                        `);
                        });
                    }
                });
            }

            // Save new collection via AJAX
            $('#saveCollection').on('click', function() {
                const collectionName = $('#collection_name').val();

                if (collectionName) {
                    $.ajax({
                        url: '/collections',
                        type: 'POST',
                        data: {
                            name: collectionName,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#addCollectionModal').modal('hide');
                                loadCollections
                                    (); // Reload the collection list with the new collection
                            }
                        }
                    });
                } else {
                    alert('Please enter a collection name.');
                }
            });



        });
    </script>
@endsection
