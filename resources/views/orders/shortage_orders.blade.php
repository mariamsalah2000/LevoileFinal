@extends('layouts.app')
@section('css')
    <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
    <style>
        .pagination {
            font-size: 14px;
            /* Adjust this size to make the links smaller */
        }

        .page-link {
            padding: 0.25rem 0.75rem;
            /* Control padding for smaller buttons */
            font-size: 0.875rem;
            /* Smaller font size */
        }
    </style>

    <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@endsection
@section('content')

    <div class="pagetitle">
        <div class="row">

            <div class="col-8">
                <h1>Shortage Orders</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item">Orders</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <form class="" action="" id="sort_orders" method="GET">

                        <div class="card-header row gutters-5">
                            <div class="row col-12">

                                <div class="col-sm-2">
                                    <div class="form-group mb-0">
                                        <label for="date">Filter By Order Date</label>
                                        <input type="date" class="form-control" value="{{ $date }}"
                                            name="date" placeholder="{{ 'Filter by order date' }}" data-format="DD-MM-Y"
                                            data-separator=" to " data-advanced-range="true" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group mb-0">
                                        <label for="date">Filter By Hold Date</label>
                                        <input type="date" class="form-control" value="{{ $hold_date }}"
                                            name="hold_date" placeholder="{{ 'Filter by hold date' }}" data-format="DD-MM-Y"
                                            data-separator=" to " data-advanced-range="true" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group mb-0">
                                        <label for="date">Filter By Reschedule Date</label>
                                        <input type="date" class="form-control" value="{{ $reschedule_date }}"
                                            name="reschedule_date" placeholder="{{ 'Filter by Reschedule date' }}"
                                            data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-3 mt-4">
                                    <div class="form-group mb-0">
                                        <select class="form-select" name="response">
                                            <option value="">Filter By Customer Response</option>
                                            <option value="null" @if ($response == 'answered') selected @endif>No Call
                                                Yet</option>
                                            <option value="answered" @if ($response == 'answered') selected @endif>
                                                Answered</option>
                                            <option value="no_answer" @if ($response == 'no_answer') selected @endif>No
                                                Answer</option>
                                            <option value="phone_off" @if ($response == 'phone_off') selected @endif>
                                                Phone Off</option>
                                            <option value="wrong_number" @if ($response == 'wrong_number') selected @endif>
                                                Wrong Number</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-auto mr-2">
                                    <div class="form-group mb-0 mt-4">
                                        <button type="submit" name="button" value="filter"
                                            class="btn btn-primary">{{ 'Filter' }}</button>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group mb-0 mt-4">
                                        <select id="sort-by" class="form-select">
                                            <option value="">Sort By</option>
                                            <option value="date1">Order Date (Newest to Old)</option>
                                            <option value="date2">Order Date (Old to New)</option>
                                            <option value="shortage_items">Shortage Items (Max to Min)</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-header row gutters-5">
                            <div class="row col-12">
                                <div class="col-sm-3">
                                    <h6 class="d-inline-block pt-10px">Total Orders</h6>
                                </div>
                                <div class="col-sm-4">
                                    <h6 class="d-inline-block pt-10px">{{ $orders_count }} Orders</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="flex flex-wrap items-center justify-between mb-4">
                                <h5 class="text-lg font-bold text-gray-700">Shortage Orders</h5>
                                <div class="flex items-center space-x-6 m-2">
                                    <h5 class="text-lg font-large text-gray-600">Assign to Moderator:</h5>
                                    <select
                                        class="block w-60 px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-200"
                                        name="moderator" id="moderator">
                                        <option value="0">{{ 'Choose Moderator' }}</option>
                                        @if (isset($moderators))
                                            @foreach ($moderators as $key => $moderator)
                                                <option value="{{ $moderator->id }}">{{ $moderator->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full table-auto border-collapse border border-gray-200">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="p-2 border border-gray-200">
                                                <div class="flex items-center justify-center">
                                                    <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600"
                                                        id="check-all">
                                                </div>
                                            </th>
                                            <th
                                                class="p-2 border border-gray-200 text-center text-sm font-medium text-gray-700">
                                                Order Number</th>
                                            <th
                                                class="p-2 border border-gray-200 text-center text-sm font-medium text-gray-700">
                                                Order Date</th>
                                            <th
                                                class="p-2 border border-gray-200 text-center text-sm font-medium text-gray-700">
                                                Hold Date</th>
                                            <th
                                                class="p-2 border border-gray-200 text-center text-sm font-medium text-gray-700">
                                                Total Items</th>
                                            <th
                                                class="p-2 border border-gray-200 text-center text-sm font-medium text-gray-700">
                                                Shortage Items</th>
                                            <th
                                                class="p-2 border border-gray-200 text-center text-sm font-medium text-gray-700">
                                                Total Price</th>
                                            <th
                                                class="p-2 border border-gray-200 text-center text-sm font-medium text-gray-700">
                                                Shortage Price</th>
                                            <th
                                                class="p-2 border border-gray-200 text-center text-sm font-medium text-gray-700">
                                                Preparator</th>
                                            <th
                                                class="p-2 border border-gray-200 text-center text-sm font-medium text-gray-700">
                                                Moderator</th>
                                            <th
                                                class="p-2 border border-gray-200 text-center text-sm font-medium text-gray-700">
                                                Response</th>
                                            <th
                                                class="p-2 border border-gray-200 text-center text-sm font-medium text-gray-700">
                                                Reschedule Date</th>
                                            <th
                                                class="p-2 border border-gray-200 text-center text-sm font-medium text-gray-700">
                                                Status</th>
                                            <th
                                                class="p-2 border border-gray-200 text-center text-sm font-medium text-gray-700">
                                                Trials</th>
                                            <th
                                                class="p-2 border border-gray-200 text-center text-sm font-medium text-gray-700">
                                                Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @isset($orders)
                                            @foreach ($orders as $key => $order)
                                                @if ($order)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="p-2 border border-gray-200 text-center">
                                                            <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600"
                                                                name="id[]" value="{{ $order->id }}">
                                                        </td>
                                                        <td
                                                            class="p-2 border border-gray-200 text-center text-sm text-gray-700">
                                                            {{ 'Lvs' . $order->order->order_number }}
                                                        </td>
                                                        <td
                                                            class="p-2 border border-gray-200 text-center text-sm text-gray-700">
                                                            {{ date('Y-m-d h:i:s', strtotime($order->created_at)) }}
                                                        </td>
                                                        <td
                                                            class="p-2 border border-gray-200 text-center text-sm text-gray-700">
                                                            {{ date('Y-m-d h:i:s', strtotime($order->hold_date)) }}
                                                        </td>
                                                        <td
                                                            class="p-2 border border-gray-200 text-center text-sm text-gray-700">
                                                            {{ $order->total_items }}
                                                        </td>
                                                        <td
                                                            class="p-2 border border-gray-200 text-center text-sm text-gray-700">
                                                            {{ $order->shortage_items }}
                                                        </td>
                                                        <td
                                                            class="p-2 border border-gray-200 text-center text-sm text-gray-700">
                                                            {{ $order->total_price }}
                                                        </td>
                                                        <td
                                                            class="p-2 border border-gray-200 text-center text-sm text-gray-700">
                                                            {{ $order->shortage_price }}
                                                        </td>
                                                        <td
                                                            class="p-2 border border-gray-200 text-center text-sm text-gray-700">
                                                            {{ $order->user->name }}
                                                        </td>
                                                        <td
                                                            class="p-2 border border-gray-200 text-center text-sm text-gray-700">
                                                            {{ $order->moderator?->name ?? '-' }}
                                                        </td>
                                                        <td
                                                            class="p-2 border border-gray-200 text-center text-sm text-gray-700">
                                                            {{ $order->response }}
                                                        </td>
                                                        <td
                                                            class="p-2 border border-gray-200 text-center text-sm text-gray-700">
                                                            {{ $order->schedule_date }}
                                                        </td>
                                                        <td
                                                            class="p-2 border border-gray-200 text-center text-sm text-gray-700">
                                                            {{ $order->status }}
                                                        </td>
                                                        <td
                                                            class="p-2 border border-gray-200 text-center text-sm text-gray-700">
                                                            {{ $order->trial }}
                                                        </td>
                                                        <td class="p-2 border border-gray-200 text-center">
                                                            <a class="inline-block px-3 py-1 text-sm text-white bg-blue-500 rounded-lg hover:bg-blue-600"
                                                                href="{{ route('shortage.make_call', $order->id) }}"
                                                                title="Make a Call">
                                                                <i class="bi bi-telephone"></i> Call
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endisset
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </form>
                    <div class="row">
                        <div class="col-12">
                            <nav>
                                <ul class="pagination pagination-sm">
                                    {{ $orders->links() }}
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('scripts')
    <script type="text/javascript" src="<?php echo asset('vendor/jquery/jquery.min.js'); ?>"></script>
    <script type="text/javascript">
        $("ul#operation").siblings('a').attr('aria-expanded', 'true');
        $("ul#operation").addClass("show");
        $("#shortage_orders").addClass("active");


        $(document).on("change", ".check-all", function() {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        $(document).on("change", "#sort-by", function() {
            const sortBy = $(this).val(); // Get the selected sort-by value
            const table = document.querySelector("#orders-table tbody");
            const rows = Array.from(table.querySelectorAll("tr"));

            rows.sort((a, b) => {
                let aValue, bValue;

                switch (sortBy) {
                    case "date1": // Order Date (Newest to Old)
                        aValue = new Date(a.querySelector("td:nth-child(2)").textContent.trim());
                        bValue = new Date(b.querySelector("td:nth-child(2)").textContent.trim());
                        return bValue - aValue; // Newest to oldest
                    case "date2": // Order Date (Old to New)
                        aValue = new Date(a.querySelector("td:nth-child(2)").textContent.trim());
                        bValue = new Date(b.querySelector("td:nth-child(2)").textContent.trim());
                        return aValue - bValue; // Oldest to newest
                    case "shortage_items": // Shortage Items (Max to Min)
                        aValue = parseInt(a.querySelector("td:nth-child(5)").textContent.trim());
                        bValue = parseInt(b.querySelector("td:nth-child(5)").textContent.trim());
                        return bValue - aValue; // Descending
                    default:
                        return 0; // No sorting
                }
            });

            // Clear the table and append sorted rows
            rows.forEach(row => table.appendChild(row));
        });

        $("#moderator").change(function() {
            var data = new FormData($('#sort_orders')[0]);
            var selected_name = $(this).find("option:selected").text();
            var selected_user = this.value;
            data.append('moderator_user', selected_name);

            var total_orders = 0;
            $('table tbody tr').each(function() {
                const checkbox = $(this).find('.check-one');

                if (checkbox.is(':checked')) {
                    total_orders += 1;
                }
            });

            if (selected_user == 0) {

            } else {
                if (confirm('Are You Sure to Assign These ' + total_orders + ' Orders to ' + selected_name)) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('bulk-shortage-assign') }}",
                        type: 'POST',
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response == 0) {
                                location.reload();
                            } else {
                                location.reload();
                            }
                        }
                    });
                } else {}
            }
        });
    </script>
@endsection
