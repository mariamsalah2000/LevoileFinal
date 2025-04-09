@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Dashboard</h1>
    </div><!-- End Page Title -->

    @if(auth()->user()->role_id != 8 && auth()->user()->role_id != 7)
    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">

                    <!-- Preparation Reports -->
                    <div class="col-12">
                        <div class="card">

                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>

                                    <li><a class="dropdown-item" href="{{ route('home', ['filter=today']) }}">Today</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('home', ['filter=month']) }}">This
                                            Month</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('home', ['filter=year']) }}">This Year</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Preparation Reports</h5>

                                <table class="table table-borderless datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Staff Name</th>
                                            <th scope="col" class="text-center">Total Orders</th>
                                            <th scope="col" class="text-center">Hold Orders</th>
                                            <th scope="col" class="text-center">Fulfilled Orders</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @isset($reports['name'])
                                            @foreach ($reports['name'] as $key => $report)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $reports['name'][$key] }}
                                                    </td>
                                                    <td class="text-center">

                                                        {{ $reports['all'][$key] }}

                                                    </td>
                                                    <td class="text-center">

                                                        {{ $reports['hold'][$key] }}

                                                    </td>
                                                    <td class="text-center">

                                                        {{ $reports['fulfilled'][$key] }}

                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endisset

                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Preparation Reports -->

                    <!-- Moderation Reports -->
                    <div class="col-12">
                        <div class="card">

                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>

                                    <li><a class="dropdown-item" href="{{ route('home', ['filter=today']) }}">Today</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('home', ['filter=month']) }}">This
                                            Month</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('home', ['filter=year']) }}">This Year</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Moderation Reports</h5>

                                <table class="table table-borderless datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Moderator Name</th>
                                            <th scope="col" class="text-center">Total Orders</th>
                                            <th scope="col" class="text-center">Facebook Orders</th>
                                            <th scope="col" class="text-center">Instagram Orders</th>
                                            <th scope="col" class="text-center">Whatsapp Orders</th>
                                            <th scope="col" class="text-center">Cancelled Orders</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @isset($reports2['name'])
                                            @foreach ($reports2['name'] as $key => $report)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $reports2['name'][$key] }}
                                                    </td>
                                                    <td class="text-center">

                                                        {{ $reports2['all'][$key] }}

                                                    </td>
                                                    <td class="text-center">

                                                        {{ $reports2['facebook'][$key] }}

                                                    </td>
                                                    <td class="text-center">

                                                        {{ $reports2['instagram'][$key] }}

                                                    </td>
                                                    <td class="text-center">

                                                        {{ $reports2['whatsapp'][$key] }}

                                                    </td>
                                                    <td class="text-center">

                                                        {{ $reports2['cancelled'][$key] }}

                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endisset

                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Moderation Reports -->

                </div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">

                <!-- Recent Activity -->
                <div class="card">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>

                            <li><a class="dropdown-item" href="{{ route('home', ['filter=today']) }}">Today</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('home', ['filter=month']) }}">This
                                    Month</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('home', ['filter=year']) }}">This
                                    Year</a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Recent Activity</h5>

                        <div class="activity">

                            @foreach ($activities as $key => $activity)
                                @php
                                    $start = Carbon\Carbon::parse($activity->created_at);
                                    $end = now();
                                    $options = [
                                        'join' => ', ',
                                        'parts' => 1,
                                        'syntax' => Carbon\CarbonInterface::DIFF_ABSOLUTE,
                                    ];

                                    $duration = $end->diffForHumans($start, $options);
                                    //
                                @endphp
                                <div class="activity-item d-flex">
                                    <div class="activite-label">{{ $duration }}</div>
                                    <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                    <div class="activity-content">
                                        {!! $activity->note !!}
                                    </div>
                                </div><!-- End activity item-->
                            @endforeach

                        </div>

                    </div>
                </div><!-- End Recent Activity -->
            </div><!-- End Right side columns -->

        </div>
    </section>
    @endif
@endsection
