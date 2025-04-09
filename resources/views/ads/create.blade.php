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

        <div class="mb-3">
            <label for="product_search" class="form-label">Search by Product Title</label>
            <div class="d-flex">
                <input type="text" class="form-control" id="product_search_value" placeholder="Enter Product Title">
                <button class="btn btn-primary" id="product_search">Search</button>
            </div>
        </div>

        <!-- Loading spinner -->
        <div id="loading-spinner" class="loading-spinner"></div>

        <!-- Product Preview Table (Hidden initially) -->
        <div id="product_preview" class="mt-3" style="display: none;">
            <div class="card-header bg-primary text-white">
                <h5>Matching Products</h5>
            </div>
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Product</th>
                        <th>Variant Count</th>
                    </tr>
                </thead>
                <tbody id="product_preview_list">
                    <!-- Rows of products will be appended here dynamically -->
                </tbody>
            </table>
        </div>

        <!-- Product Information Preview (Initially hidden) -->
        <div id="product-info" class="mt-5" style="display: none;">
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
                        <td id="product_id_info"></td>
                        <td><img id="product_image_info" src="" alt="Product Image"
                                style="width: 100px; height: auto;"></td>
                        <td id="product_name_info"></td>
                        <td id="product_price_info"></td>
                        <td id="product_quantity_info"></td>
                    </tr>
                </tbody>
            </table>
        </div>


        <!-- Form for Ad Creation -->
        <form id="adForm" style="display: none;">
            <!-- Hidden input to store the selected product_id -->
            <input type="hidden" id="product_id" name="product_id">

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
                <textarea class="form-control" id="drive_links" name="drive_links" placeholder="Enter Drive links, separated by commas"></textarea>
            </div>

            <!-- IG Links -->
            <div class="mb-3">
                <label for="ig_links" class="form-label">IG Links</label>
                <textarea class="form-control" id="ig_links" name="ig_links" placeholder="Enter IG links, separated by commas"></textarea>
            </div>

            <!-- Content -->
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" placeholder="Enter Content, separated by commas"></textarea>
            </div>

            <!-- Submit Button -->
            <button type="button" id="submitAd" class="btn btn-primary">Submit Ad</button>
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

            //let searchTimeout;

            // Search Product by Title (Debounced Input)
            $('#product_search').on('click', function() {

                //clearTimeout(searchTimeout);
                const query = $("#product_search_value").val();


                //     if (query.length < 2) {
                //         $('#product_preview').hide();
                //         return;  // Don't search for short queries
                //     }

                //     // Debounce search to avoid excessive AJAX requests
                //     searchTimeout = setTimeout(function() {
                //         searchProduct(query);
                //     }, 300);

                searchProduct(query);
            });

            // AJAX Request for Product Search
            function searchProduct(query) {
                $('#loading-spinner').show(); // Show spinner while searching

                $.ajax({
                    url: '/search-product',
                    type: 'GET',
                    data: {
                        query: query
                    },
                    success: function(response) {
                        $('#loading-spinner').hide(); // Hide spinner after success
                        if (response.length > 0) {
                            displayProducts(response);
                        } else {
                            $('#product_preview_list').empty();
                            $('#product_preview').hide();
                        }
                    },
                    error: function() {
                        $('#loading-spinner').hide();
                        alert('Error searching for products. Please try again.');
                    }
                });
            }

            // Display Products in Table
            function displayProducts(products) {
                $('#product_preview_list').empty(); // Clear old results

                products.forEach(product => {
                    $.ajax({
                        url: '/get-product-variant-count',
                        type: 'GET',
                        data: {
                            product_id: product.id
                        },
                        success: function(count) {
                            const productRow = `
                            <tr class="product-row" data-id="${product.id}" data-title="${product.title}">
                                <td><img src="${product.images.length > 0 ? product.images[0].src : 'default_image_url.jpg'}" style="width: 50px;"> ${product.title}</td>
                                <td>${count}</td>
                            </tr>`;
                            $('#product_preview_list').append(productRow);
                        }
                    });
                });

                $('#product_preview').show();
            }

            // Handle Product Selection
            $(document).on('click', '.product-row', function() {
                const productId = $(this).data('id');
                const productTitle = $(this).data('title');

                $('#product_search_value').val(productTitle);
                $('#product_id').val(productId);

                getProductDetails(productId);
            });

            // Fetch Product Details by ID
            function getProductDetails(productId) {
                $.ajax({
                    url: `/product-details/${productId}`,
                    type: 'GET',
                    success: function(response) {
                        if (response) {
                            console.log(response.variants[0]);

                            // Update product information UI
                            $('#product_id_info').text(response.id);
                            $('#product_name_info').text(response.title);
                            $('#product_price_info').text(response.variants[0].price || 'N/A');
                            $('#product_quantity_info').text(response.variants[0].inventory_quantity ||
                                'N/A');

                            const productImage = response.images.length > 0 ? response.images[0].src :
                                'default_image_url.jpg';
                            $('#product_image_info').attr('src', productImage);

                            $('#product-info').show();
                            $('#adForm').show();
                            $('#product_preview').hide();
                        }
                    },
                    error: function() {
                        alert('Error fetching product details.');
                    }
                });
            }

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

                $.ajax({
                    url: '/ads',
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
                        alert('Ad created successfully');
                        window.location.reload();
                    },
                    error: function() {
                        document.getElementById('submitAd').disabled = false;
                        alert('Error creating ad. Please try again.');
                    }
                });
            });



            // Load collections on page load
            loadCollections();

            // Load collections
            function loadCollections() {
                $.ajax({
                    url: '/get-collections',
                    type: 'GET',
                    success: function(response) {
                        const collections = response.collections;
                        $('#collections-list').empty();
                        collections.forEach(function(collection) {
                            $('#collections-list').append(`
                            <div class="form-check col-md-2">
                                <input class="form-check-input" type="checkbox" value="${collection.id}" id="collection_${collection.id}" name="collections[]">
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
