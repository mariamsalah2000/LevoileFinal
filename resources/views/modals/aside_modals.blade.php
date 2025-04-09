<div class="modal fade" id="resync-modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Re-Sync Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body fulfillment_form">
                <form action="{{ route('orders.resync') }}" class="row g-3" method="POST">
                    @csrf

                    <div class="col-md-6">
                        <label for="reason">Edit Reason</label>
                        <select class="form-select aiz-selectpicker" name="reason"
                            data-minimum-results-for-search="Infinity" required>
                            <option value=""selected>Select</option>
                            <option value="Add Item">Add Item</option>
                            <option value="Remove Item">Remove Item</option>
                            <option value="Replace Item">Replace Item</option>
                            <option value="Update Qty">Update Qty</option>
                            <option value="OTHER">Other</option>

                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="order_id">Order ID</label>
                        <input type="text" name="order_id" class="form-control"
                            placeholder="Enter Order id and Hit Enter" required>
                    </div>
                    <div class="col-4 justify-content-center">
                        <button type="submit" class="btn btn-info">Re-Sync</button>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="upload-stock-modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="alert alert-info" id="progressMessage">
        </div>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Branches Stock Sheet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body fulfillment_form">
                <form action="{{ route('stock.upload') }}" class="row g-3" id="get-progress" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="col-md-6 justify-content-center">
                        <label for="file">File</label>
                        <input type="file" name="sheet" class="form-control" placeholder="Upload file" required>
                    </div>
                    <div class="col-6 justify-content-center pt-4">
                        <button type="submit" class="btn btn-info">Upload</button>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="shipping-trx-upload-modal" tabindex="-1">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Shipping Transaction Sheet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body fulfillment_form">
                <form action="{{ route('shipping_trx.upload') }}" class="row g-3" id="get-progress" method="POST"
                    enctype="multipart/form-data" onsubmit="disableButton()">
                    @csrf

                    <div class="col-md-6 justify-content-center">
                        <label for="text">Choose Shipment Date</label>
                        <input type="date" name="shipment_date" class="form-control"
                            required>
                    </div>
                    <div class="col-md-6 justify-content-center">
                        <label for="file">File</label>
                        <input type="file" name="sheet" class="form-control" placeholder="Upload file" required>
                    </div>
                    <div class="col-md-6 justify-content-center">
                        <label for="text">Note</label>
                        <input type="text" name="note" class="form-control" placeholder="Upload Note">
                    </div>
                    <div class="col-6 justify-content-center pt-4">
                        <button id="submit-shipping-btn" type="submit" class="btn btn-info">Upload</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="collection-upload-modal" tabindex="-1">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Shipping Collection Sheet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body fulfillment_form">
                <form action="{{ route('collections.upload') }}" class="row g-3" id="get-progress" method="POST"
                    enctype="multipart/form-data" onsubmit="disableButton()">
                    @csrf

                    <div class="col-md-6 justify-content-center">
                        <label for="text">Choose Collection Date</label>
                        <input type="date" name="shipment_date" class="form-control"
                            required>
                    </div>
                    <div class="col-md-6 justify-content-center">
                        <label for="file">File</label>
                        <input type="file" name="sheet" class="form-control" placeholder="Upload file" required>
                    </div>
                    <div class="col-md-6 justify-content-center">
                        <label for="text">Note</label>
                        <input type="text" name="note" class="form-control" placeholder="Upload Note">
                    </div>
                    <div class="col-6 justify-content-center pt-4">
                        <button id="submit-shipping-btn" type="submit" class="btn btn-info">Upload</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="return-collection-upload-modal" tabindex="-1">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Return Collection Sheet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body fulfillment_form">
                <form action="{{ route('return_collections.upload') }}" class="row g-3" id="get-progress" method="POST"
                    enctype="multipart/form-data" onsubmit="disableButton()">
                    @csrf

                    <div class="col-md-6 justify-content-center">
                        <label for="text">Choose Return Collection Date</label>
                        <input type="date" name="shipment_date" class="form-control"
                            required>
                    </div>
                    <div class="col-md-6 justify-content-center">
                        <label for="file">File</label>
                        <input type="file" name="sheet" class="form-control" placeholder="Upload file" required>
                    </div>
                    <div class="col-md-6 justify-content-center">
                        <label for="text">Note</label>
                        <input type="text" name="note" class="form-control" placeholder="Upload Note">
                    </div>
                    <div class="col-6 justify-content-center pt-4">
                        <button id="submit-shipping-btn" type="submit" class="btn btn-info">Upload</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="inventory-modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Inventory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body fulfillment_form">
                <form action="{{ route('inventory.import') }}" class="row g-3" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="col-md-6">
                        <label for="file">File</label>
                        <input type="file" name="sheet" class="form-control" placeholder="Upload file"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="note">Note</label>
                        <textarea name="note" class="form-control" placeholder="Add Transfer Note"></textarea>
                    </div>
                    <div class="col-4 justify-content-center">
                        <button type="submit" class="btn btn-info">Upload</button>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="upload-shipping-cost" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Shipping Cost</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body fulfillment_form">
                <form action="{{ route('shipping-cost.import') }}" class="row g-3" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="col-md-6 mt-1">
                        <label for="file">File</label>
                        <input type="file" name="sheet" class="form-control" placeholder="Upload file">
                    </div>
                    <div class="col-md-2 mt-4">
                        <button type="submit" name="button" value="upload" class="btn btn-info">Upload</button>
                    </div>
                    <div class="col-md-3 mt-4">
                        <button type="submit" name="button" value="download" class="btn btn-warning">Download
                            Sample</button>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="search-modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Find Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body fulfillment_form">
                <form action="{{ route('orders.search') }}" class="row g-3" method="GET"
                    enctype="multipart/form-data">
                    @csrf

                    <br>
                    <div>
                        <label for="file">Order Number</label>
                        <input type="text" name="search" class="form-control" placeholder="Enter Search Keyword"
                            required>
                    </div>
            </div>
            <br>
            <button type="submit" class="btn btn-info">Search</button>



            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="confirm-modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Return</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body fulfillment_form">
                <form action="{{ route('returns.confirm') }}" class="row g-3" method="get">

                    <div class="col-md-6">
                        <label for="order_id">Order Number</label>
                        <input type="text" name="order_number" class="form-control"
                            placeholder="Enter Order Number and Hit Enter" required>
                    </div>
                    <div class="col-4 justify-content-center">
                        <button type="submit" class="btn btn-info">Submit</button>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>
