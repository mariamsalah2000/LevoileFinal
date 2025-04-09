@extends('layouts.app')
@section('content')
    <div class="pagetitle">
        <div class="row">
            <div class="col-8">
                <h1>Product Variants</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">Products</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Your Product Variants</h5>
                        {{-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> --}}
                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Variation</th>
                                    <th scope="col">SKU</th>
                                    <th scope="col">Created Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    use Milon\Barcode\DNS1D;
                                    $barcodes = [];
                                @endphp
                                @isset($products)
                                    @if ($products !== null)
                                        @foreach ($products as $key => $product)
                                            @php
                                                $barcode = new DNS1D();
                                                $barcodes[] = $barcode->getBarcodePNG($product->barcode, 'C128');
                                            @endphp
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $product->product['title'] }}</td>
                                                <td>{{ $product['title'] }}</td>
                                                <td>{{ $product['sku'] }}</td>
                                                <td>{{ date('Y-m-d', strtotime($product['created_at'])) }}</td>
                                                <td><a href="{{ route('change.product.addToCart') }}?product_id={{ $product['table_id'] }}"
                                                        class="btn btn-primary">Print Barcode</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endisset
                            </tbody>
                        </table>
                        <div class="text-center pb-2">
                            {{ $products->links() }}
                        </div>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>
        <div id="print-barcode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
            class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal_header" class="modal-title">{{ trans('file.Barcode') }}</h5>&nbsp;&nbsp;
                        <button id="print-btn" type="button" class="btn btn-default btn-sm"><i class="dripicons-print"></i>
                            {{ trans('file.Print') }}</button>
                        <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close" class="close"><span
                                aria-hidden="true"><i class="dripicons-cross"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div id="label-content">
                            <table class="barcodelist" style="width: 100%" cellpadding="5px" cellspacing="10px">
                                <tr>
                                    <td>
                                        Name :<br>
                                        Price:<br>
                                        Code :<br>
                                        <img src="data:image/png;base64," alt="barcode"
                                            style="margin-top:10px;margin-bottom:10px;" /><br>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script type="text/javascript">
        $("ul#operation").siblings('a').attr('aria-expanded', 'true');
        $("ul#operation").addClass("show");
        $("#variants").addClass("active");
    </script>
@endsection
