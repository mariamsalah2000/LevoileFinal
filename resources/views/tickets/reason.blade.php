@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Reason Report</h1>


        <!-- Date Range Filter -->
        <div class="mb-5">
            <form action="{{ route('reason.report') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control"
                            value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="sort_by">Sort By</label>
                        <select class="form-select" name="sort_by">
                            <option value=""></option>
                            <option value="desc" @if (request('sort_by') == 'desc') selected @endif>High to Low</option>
                            <option value="asc" @if (request('sort_by') == 'asc') selected @endif>Low to High</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>&nbsp;</label> <!-- Empty label for spacing -->
                        <button type="submit" class="btn btn-primary form-control">Filter</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table to show reasons and counts -->
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Reason</th>
                    <th>Count</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reasonCounts as $reason => $count)
                    <tr>
                        <td>{{ ucfirst(str_replace('_', ' ', $reason)) }}</td>
                        <td>{{ $count }}</td>
                        <td>
                            <a href="{{ route('tickets.showReason', $reason) }}" class="btn btn-primary">Show</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        $("ul#ticket").siblings('a').attr('aria-expanded', 'true');
        $("ul#ticket").addClass("show");
        $("#reason_report").addClass("active");
    </script>
@endsection
