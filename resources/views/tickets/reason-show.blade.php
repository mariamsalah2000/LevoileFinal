@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Tickets for Reason: {{ ucfirst(str_replace('_', ' ', $reason)) }}</h1>

        <!-- Card for displaying tickets -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5>List of Tickets</h5>
            </div>
            <div class="card-body">
                @if ($tickets->isEmpty())
                    <div class="alert alert-warning" role="alert">
                        No tickets found for this reason.
                    </div>
                @else
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Created At</th>
                                <th>Order Number</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->created_at }}</td>
                                    <td>{{ $ticket->order_number }}</td>
                                    @php
                                        $user = \App\Models\User::find($ticket->user_id);
                                    @endphp
                                    <td>{{ $user->name }}</td>

                                    <td>
                                        @php
                                            $class =
                                                $ticket->status === 'done'
                                                    ? 'badge bg-success'
                                                    : ($ticket->status === 'in progress'
                                                        ? 'badge bg-secondary'
                                                        : ($ticket->status === 'open'
                                                            ? 'badge bg-warning'
                                                            : 'badge bg-danger'));
                                        @endphp
                                        <span class="{{ $class }}">
                                            {{ ucfirst($ticket->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('tickets.show', $ticket->id) }}"
                                            class="btn btn-info btn-sm">View</a>
                                        <!-- Add more action buttons as necessary -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('reason.report') }}" class="btn btn-secondary">Back to Reason Report</a>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Any additional JavaScript if needed
    </script>
@endsection
