@extends('layouts.app')
@section('content')
    <div class="pagetitle">
        <div class="row">
            <div class="col-8">
                <h1>BlackList</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">BlackList</li>
                    </ol>
                </nav>
            </div>
            <div class="col-4">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td><a href="{{ route('blacklist.create') }}" style="float:right"
                                    class="btn btn-success">Add Blacklist</a></td>
                            
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
                        <h5 class="card-title">BlackList</h5>
                        {{-- <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable</p> --}}
                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">phone</th>
                                    <th scope="col">note</th>
                                    <th scope="col">Created Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($blacklist as $blacklist)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $blacklist->name }}</td>
                                        <td>{{ $blacklist->phone }}</td>
                                        <td>{{ $blacklist->note }}</td>
                                        <td>{{ date('Y-m-d', strtotime($blacklist->created_at)) }}</td>
                                        <td><a href="{{ route('blacklist.edit' , $blacklist->id) }}"
                                                class="btn btn-primary">Edit</a>
                                                <form action="{{ route('blacklist.destroy', $blacklist->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are You Sure?')">Delete</button>
                                                </form>
                                        </td>
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
        $("ul#operation").siblings('a').attr('aria-expanded', 'true');
        $("ul#operation").addClass("show");
        $("#products").addClass("active");
    </script>
@endsection
