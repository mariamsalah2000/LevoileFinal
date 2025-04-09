@extends('layouts.app')
@section('content')
    <div class="pagetitle">
        <div class="row">
            <div class="col-8">
                <h1>Stock Transactions</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">Stock Transactions</li>
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
                        <h5 class="card-title">Branch Stock Transactions</h5>
                        {{-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> --}}
                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Ref</th>
                                    <th scope="col">Total Products</th>
                                    <th scope="col">Successful Products</th>
                                    <th scope="col">Successful Qty </th>
                                    <th scope="col">Failed Products </th>
                                    <th scope="col">Created Date</th>
                                    {{-- <th scope="col">Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $transaction->ref }}</td>
                                        <td>{{ $transaction->success_products + $transaction->failed_products }}</td>
                                        <td>{{ $transaction->success_products }}</td>
                                        <td>{{ $transaction->success_qty }}</td>
                                        <td>{{ $transaction->failed_products }}</td>
                                        <td>{{ date('Y-m-d', strtotime($transaction->created_at)) }}</td>
                                        {{-- <td><a href="{{ route('transaction.edit' , $transaction->id) }}"
                                                class="btn btn-primary">Edit</a>
                                                <form action="{{ route('transaction.destroy', $transaction->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are You Sure?')">Delete</button>
                                                </form>
                                        </td> --}}
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script type="text/javascript">
        $("ul#reports").siblings('a').attr('aria-expanded', 'true');
        $("ul#reports").addClass("show");
        $("#trx").addClass("active");
    </script>
@endsection
