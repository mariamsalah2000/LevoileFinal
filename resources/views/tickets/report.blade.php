@extends('layouts.app')

@section('content')
    <div class="container">

        <!-- Date Range Filter -->
        <div class="mb-5">
            <form action="{{ route('tickets.report') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control"
                            value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label>&nbsp;</label> <!-- Empty label for spacing -->
                        <button type="submit" class="btn btn-primary form-control">Filter</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Scarf Sale Section -->
        <div class="mb-5">
            <h1 class="text-primary">Levoile</h1>
            <table class="table table-bordered table-striped" id="levTable">
                <thead class="thead-dark">
                    <tr>
                        <th>User Name</th>
                        <th>Number of Tickets Added</th>
                        <th>Open Tickets</th>
                        <th>In Progress Tickets</th>
                        <th>Done Tickets</th>
                        <th>Success Tickets</th>
                        <th>Failed Tickets</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $scarfSaleUsers = $tickets->whereIn('user.role_id', [7, 6])->groupBy('user.id');
                    @endphp
                    @forelse($scarfSaleUsers as $userTickets)
                        @php
                            $openTickets = $userTickets
                                ->filter(function ($userTicket) {
                                    return $userTicket->status === 'open';
                                })
                                ->count();

                            $inProgressTickets = $userTickets
                                ->filter(function ($userTicket) {
                                    return $userTicket->status === 'in progress';
                                })
                                ->count();

                            $doneTickets = $userTickets
                                ->filter(function ($userTicket) {
                                    return $userTicket->status === 'done';
                                })
                                ->count();
                            $successTickets = $userTickets
                                ->filter(function ($userTicket) {
                                    return $userTicket->is_returned === 0 &&
                                        $userTicket->is_asked_to_return === 0 &&
                                        $userTicket->status === 'done';
                                })
                                ->count();
                            $failedTickets = $userTickets
                                ->filter(function ($userTicket) {
                                    return $userTicket->is_returned === 1 &&
                                        $userTicket->is_asked_to_return === 0 &&
                                        $userTicket->status === 'done';
                                })
                                ->count();
                        @endphp

                        <tr>
                            <td>{{ $userTickets->first()->user->name }}</td>
                            <td>{{ $userTickets->count() }}</td>
                            <td>{{ $openTickets }}</td>
                            <td>{{ $inProgressTickets }}</td>
                            <td>{{ $doneTickets }}</td>
                            <td>{{ $successTickets }}</td>
                            <td>{{ $failedTickets }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No tickets found for Scarf Sale.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-secondary">
                    <tr>
                        <td id="levtotalCol0">Total</td>
                        <td id="levtotalCol2"></td>
                        <td id="levtotalCol3"></td>
                        <td id="levtotalCol4"></td>
                        <td id="levtotalCol5"></td>
                        <td id="levtotalCol6"></td>
                        <td id="levtotalCol7"></td>
                    </tr>

                </tfoot>
            </table>
        </div>


        <!-- Best Shipping Company Section -->
        <div class="mb-5">
            <h1 class="text-success">Best Shipping Company</h1>
            <table class="table table-bordered table-striped" id="shipTable">
                <thead class="thead-dark">
                    <tr>
                        <th>User Name</th>
                        <th>Number of Tickets Added</th>
                        <th>Open Tickets</th>
                        <th>In Progress Tickets</th>
                        <th>Done Tickets</th>
                        <th>Success Tickets</th>
                        <th>Failed Tickets</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $shippingCompanyUsers = $tickets->where('user.role_id', 8)->groupBy('user.id');
                    @endphp
                    @forelse($shippingCompanyUsers as $userTickets)
                        @php
                            $openTickets = $userTickets
                                ->filter(function ($userTicket) {
                                    return $userTicket->status === 'open';
                                })
                                ->count();

                            $inProgressTickets = $userTickets
                                ->filter(function ($userTicket) {
                                    return $userTicket->status === 'in progress';
                                })
                                ->count();

                            $doneTickets = $userTickets
                                ->filter(function ($userTicket) {
                                    return $userTicket->status === 'done';
                                })
                                ->count();
                            $successTickets = $userTickets
                                ->filter(function ($userTicket) {
                                    return $userTicket->is_returned === 0 &&
                                        $userTicket->is_asked_to_return === 0 &&
                                        $userTicket->status === 'done';
                                })
                                ->count();
                            $failedTickets = $userTickets
                                ->filter(function ($userTicket) {
                                    return $userTicket->is_returned === 1 &&
                                        $userTicket->is_asked_to_return === 0 &&
                                        $userTicket->status === 'done';
                                })
                                ->count();
                        @endphp

                        <tr>
                            <td>{{ $userTickets->first()->user->name }}</td>
                            <td>{{ $userTickets->count() }}</td>
                            <td>{{ $openTickets }}</td>
                            <td>{{ $inProgressTickets }}</td>
                            <td>{{ $doneTickets }}</td>
                            <td>{{ $successTickets }}</td>
                            <td>{{ $failedTickets }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No tickets found for Best Shipping Company.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-secondary">
                    <tr>
                        <td id="shiptotalCol0">Total</td>
                        <td id="shiptotalCol2"></td>
                        <td id="shiptotalCol3"></td>
                        <td id="shiptotalCol4"></td>
                        <td id="shiptotalCol5"></td>
                        <td id="shiptotalCol6"></td>
                        <td id="shiptotalCol7"></td>
                    </tr>

                </tfoot>
            </table>
        </div>

    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $("ul#ticket").siblings('a').attr('aria-expanded', 'true');
        $("ul#ticket").addClass("show");
        $("#report_tickets").addClass("active");

        $(document).ready(function() {
            var columnCount = $('#levTable thead th').length; // Get the number of columns

            for (var i = 1; i <= columnCount; i++) {
                var total = 0;

                $('#levTable tbody tr').each(function() {
                    var value = parseFloat($(this).find('td:nth-child(' + i + ')').text());
                    if (!isNaN(value)) {
                        total += value;
                    }
                });
                $('#levtotalCol' + i).text(total); // Update the footer cell with the total
            }

            var columnCount = $('#shipTable thead th').length; // Get the number of columns

            for (var i = 1; i <= columnCount; i++) {
                var total = 0;

                $('#shipTable tbody tr').each(function() {
                    var value = parseFloat($(this).find('td:nth-child(' + i + ')').text());
                    if (!isNaN(value)) {
                        total += value;
                    }
                });

                $('#shiptotalCol' + i).text(total); // Update the footer cell with the total
            }
        });
    </script>
@endsection
