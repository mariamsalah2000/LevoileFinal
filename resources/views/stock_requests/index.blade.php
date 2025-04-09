@extends('layouts.app')
@section('css')
    <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

@endsection
@section('content')
<div class="pagetitle">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Shipment Stock Requests</h1>
        <nav>
            <ol class="flex space-x-2">
                <li><a href="{{ route('home') }}" class="text-blue-600 hover:underline">Home</a></li>
                <li class="text-gray-600">/ Shipments</li>
            </ol>
        </nav>
    </div>
</div><!-- End Page Title -->

<section class="py-6">
    <div class="max-w-7xl mx-auto">

        <div class="bg-white shadow-md rounded-lg">
            <form action="" id="sort_orders" method="GET">
                <div class="p-4 border-b">
                    <div class="flex flex-wrap space-x-4">

                        <div class="flex-1">
                            <label for="daterange" class="block mb-1 text-sm font-medium">Filter By Daterange</label>
                            <input type="text" value="{{ $daterange }}" id="daterange" name="daterange" class="form-input w-full" />
                        </div>

                        <div class="flex-1">
                            <label for="search" class="block mb-1 text-sm font-medium">Search By Reference</label>
                            <input type="text" class="form-input w-full" value="{{ $search }}" name="search" placeholder="{{ 'Enter Stock Request Reference' }}" />
                        </div>

                        <div class="flex-1">
                            <label for="branch_id" class="block mb-1 text-sm font-medium">Filter By Status</label>
                            <select class="form-select w-full" name="shipping" id="branch_id">
                                <option value="">Choose Status</option>
                                <option value="pending" @if ($status == 'pending') selected @endif>Pending</option>
                                <option value="in_progress" @if ($status == 'in_progress') selected @endif>In Progress</option>
                                <option value="closed" @if ($status == 'closed') selected @endif>Closed</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto p-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-center py-3 px-4">Stock Request Date</th>
                                <th class="text-center py-3 px-4">Stock Request Reference</th>
                                <th class="text-center py-3 px-4">Branch Name</th>
                                <th class="text-center py-3 px-4">Created By</th>
                                <th class="text-center py-3 px-4">No of Products</th>
                                <th class="text-center py-3 px-4">Total Quantity</th>
                                <th class="text-center py-3 px-4">Note</th>
                                <th class="text-center py-3 px-4">Downloaded At</th>
                                <th class="text-center py-3 px-4">Status</th>
                                <th class="text-right py-3 px-4" width="25%">Options</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($stockRequests as $key => $stockRequest)
                                <tr>
                                    <td class="text-center py-3 px-4">{{ date('d/m/Y', strtotime($stockRequest->created_at)) }}</td>
                                    <td class="text-center py-3 px-4">{{ $stockRequest->ref }}</td>
                                    <td class="text-center py-3 px-4">{{ $stockRequest->branch->name }}</td>
                                    <td class="text-center py-3 px-4">{{ optional($stockRequest->user)->name ?? '-' }}</td>
                                    <td class="text-center py-3 px-4">{{ $stockRequest->total_products }}</td>
                                    <td class="text-center py-3 px-4">{{ $stockRequest->total_quantity }}</td>
                                    <td class="text-center py-3 px-4">{{ $stockRequest->note }}</td>
                                    <td class="text-center py-3 px-4">{{ $stockRequest->downloaded_at ? date('d/m/Y', strtotime($stockRequest->downloaded_at)) : '-' }}</td>
                                    <td class="text-center py-3 px-4">
                                        @if($stockRequest->status == "pending")
                                        <span class="inline-block bg-yellow-50 text-yellow-600 text-sm font-medium px-4 py-2 rounded-full">
                                            {{ $stockRequest->status }}
                                        </span>
                                        @else
                                        <span class="inline-block bg-green-50 text-green-600 text-sm font-medium px-4 py-2 rounded-full">
                                            {{ $stockRequest->status }}
                                        </span>
                                        @endif
                                    </td>
                                    <td class="text-right py-3 px-4">
                                        <div class="flex flex-col items-center space-y-1">
                                            @if ($stockRequest->status == 'pending' && auth()->user()->role->name == 'Branches')
                                                <a class="btn btn-primary text-xs" href="{{ route('stock_requests.edit', $stockRequest->id) }}" title="Close">
                                                    <i class="bi bi-edit-fill"></i> Edit
                                                </a>
                                            @endif
                                            <a class="btn btn-warning text-xs" href="{{ route('stock_requests.show', $stockRequest->id) }}" title="Show">
                                                <i class="bi bi-eye-fill"></i> View
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $stockRequests->appends(request()->input())->links() }}
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection

@section('scripts')
    <script type="text/javascript">
        $("ul#branches").siblings('a').attr('aria-expanded', 'true');
        $("ul#branches").addClass("show");
        $("#requests").addClass("active");

        $(document).ready(function() {
            $('#daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'MM/DD/YYYY'
                }
            });
        });
    </script>
@endsection
