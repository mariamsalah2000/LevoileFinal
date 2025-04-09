@extends('layouts.app')
@section('content')
    <div class="pagetitle">
        <div class="row">
            <div class="col-8">
                <h1>Review Transfer</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">Transfer Review</li>
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
                        <h1 class="card-title text-capitalize">Please Review Success and Failed Items Before Submission</h1>
                        {{-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> --}}
                        <!-- Table with stripped rows -->
                        <form action="{{ route('inventory.review') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <h4 class="text-center text-danger text-bold">Success Products</h4>
                                <table class="table-bordered">
                                    <thead class="alert-dark">
                                        <th>#</th>
                                        <th>Product Title</th>
                                        <th>Product SKU</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($success as $key => $sku)
                                            <tr class="alert-success">
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $success_titles[$key] }}</td>
                                                <td>{{ $sku }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>

                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-10">
                                    <h4 class="text-center text-danger text-bold">Failed Products</h4>
                                </div>
                                <div class="col-2">
                                    <a class="btn btn-warning justify-content-end" download href="{{ $publicPath }}">Download Failed Sheet</a>

                                </div>
                                
                                <table class="table-bordered">
                                    <thead class="alert-dark">
                                        <th>#</th>
                                        <th>Product Title</th>
                                        <th>Product SKU</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($failed as $key => $sku)
                                            <tr class="alert-danger">
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $failed_titles[$key] }}</td>
                                                <td>{{ $sku }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>

                            </div>
                            <input type="hidden" name="success" value="{{ json_encode($success) }}">
                            <input type="hidden" name="note" value="{{ $note }}">
                            <input type="hidden" name="failed" value="{{ json_encode($failed) }}">
                            <input type="hidden" name="qty" value="{{ json_encode($qty) }}">
                            <input type="hidden" name="success_titles" value="{{ json_encode($success_titles) }}">
                            <input type="hidden" name="failed_titles" value="{{ json_encode($failed_titles) }}">
                            <input type="hidden" name="inventory_items" value="{{ json_encode($inventory_items) }}">
                            <input type="hidden" name="inventory_items" value="{{ json_encode($inventory_items) }}">
                            <input type="hidden" name="file" value="{{ $publicPath }}">
                            <br>
                            <input type="submit" class="btn btn-danger justify-content-end" value="Submit">
                        </form>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
