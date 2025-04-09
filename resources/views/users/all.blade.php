@extends('layouts.app')
@section('css')
    <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
@endsection
@section('content')

    <div class="pagetitle">
        <div class="row">
            <div class="col-12">
                <h1>Users</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">Users</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <form class="" action="" id="sort_orders" method="GET">
                    <div class="card">
                        <div class="card-header row gutters-5">
                            <div class="row col-12">
                                <div class="col-4 justify-content-center">

                                    <div class="container">
                                        <label for="search">Search User</label>
                                        <input type="text" class="form-control" id="search"
                                            name="search"@isset($search) value="{{ $search }}" @endisset
                                            placeholder="{{ 'Search for User By Name/Email' }}">
                                    </div>

                                </div>
                                <div class="col-2 justify-content-center">
                                    <div class="container">
                                        <button type="submit" class="btn btn-primary">{{ 'Filter' }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Created Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @isset($users)
                                        @foreach ($users as $user)
                                            <tr>
                                                <th scope="row">{{ $user->id }}</th>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->phone }}</td>
                                                <td>{{ optional($user->role)->name ?? '-' }}</td>
                                                <td>{{ date('F d Y', strtotime($user->created_at)) }}</td>
                                            </tr>
                                        @endforeach
                                    @endisset
                                </tbody>
                            </table>
                            <div class="text-center pb-2">
                                {{ $users->links() }}
                            </div>
                            <!-- End Table with stripped rows -->
                            <!-- End Table with stripped rows -->
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </section>
@endsection


@section('scripts')
    <script type="text/javascript">
        $("ul#users").siblings('a').attr('aria-expanded', 'true');
        $("ul#users").addClass("show");
        $("#all").addClass("active");
    </script>
@endsection
