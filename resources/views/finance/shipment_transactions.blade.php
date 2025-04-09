@extends('layouts.app')
@section('css')
    <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="pagetitle">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold">Shipment Transactions</h1>
            <nav>
                <ol class="breadcrumb flex space-x-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-blue-600">Home</a></li>
                    <li class="breadcrumb-item">Finance</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <section class="section mt-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <form action="" id="sort_orders" method="GET" class="p-5">
                        <div class="grid grid-cols-12 gap-4 mb-5">
                            <div class="col-span-1 flex items-center">
                                <label class="text-sm font-medium">{{ 'Choose Transaction Date' }}</label>
                            </div>
                            <div class="col-span-2">
                                <input type="date" class="form-control border-gray-300 rounded-md"
                                    value="{{ $date }}" name="date" placeholder="{{ 'Filter by Date' }}"
                                    autocomplete="off">
                            </div>
                            <div class="col-span-4">
                                <input type="text" class="form-control border-gray-300 rounded-md"
                                    value="{{ $search }}" name="search"
                                    placeholder="{{ 'Enter Transaction Reference or Shipment Number' }}">
                            </div>
                            <div class="col-span-2">
                                <select class="form-select border-gray-300 rounded-md" name="shipping" id="branch_id">
                                    <option value="">Choose Status</option>
                                    <option value="open" @if ($status == 'open') selected @endif>Open</option>
                                    <option value="closed" @if ($status == 'closed') selected @endif>Closed</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <select class="form-select border-gray-300 rounded-md" name="sort_by" id="sort_by">
                                    <option value="">Sort By</option>
                                    <option value="success1" @if ($sort_by == 'success1') selected @endif>Success (Low
                                        to High)</option>
                                    <option value="failed1" @if ($sort_by == 'failed1') selected @endif>Failed (Low to
                                        High)</option>
                                    <option value="success2" @if ($sort_by == 'success2') selected @endif>Success (High
                                        to Low)</option>
                                    <option value="failed2" @if ($sort_by == 'failed2') selected @endif>Failed (High
                                        to Low)</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>

                        <div class="overflow-auto rounded-lg border border-gray-200 shadow" style="max-height: 1000px;">
                            <table class="min-w-full table-auto border-collapse border border-gray-300">
                                <thead class="bg-gray-200 sticky top-0 z-10">
                                    <tr>
                                        <th class="text-center py-3 px-4 border border-gray-300">ID</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Transaction Date</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Transaction Reference</th>
                                        <!-- <th class="text-center py-3 px-4 border border-gray-300">Shipment Number</th> -->
                                        <th class="text-center py-3 px-4 border border-gray-300">Created By</th>
                                        <!-- <th class="text-center py-3 px-4 border border-gray-300">Shipping Company</th> -->
                                        <th class="text-center py-3 px-4 border border-gray-300">Total Orders</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Total COD</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Total Website Price</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Total COD Difference</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Total Shipping</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Total Website Shipping</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Total Shipping Difference
                                        </th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Total Net</th>
                                        
                                        
                                        <th class="text-center py-3 px-4 border border-gray-300">Successful Orders</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Failed Orders</th>
                                        <!-- <th class="text-center py-3 px-4 border border-gray-300">Note</th> -->
                                        <th class="text-center py-3 px-4 border border-gray-300">Status</th>
                                        <th class="text-right py-3 px-4 border border-gray-300" width="15%">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $key => $transaction)
                                        <tr class="border-b">
                                        <td class="text-center py-4 px-4 border border-gray-300">
                                        {{ $loop->iteration }}</td>
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                                {{ date('d/m/Y', strtotime($transaction->created_at)) }}</td>
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                                #{{ $transaction->transaction_number }}</td>
                                            <!-- <td class="text-center py-4 px-4 border border-gray-300">
                                                {{ $transaction->shipment_number }}</td> -->
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                                {{ optional($transaction->user)->name ?? '-' }}</td>
                                            <!-- <td class="text-center py-4 px-4 border border-gray-300">BestExpress</td> -->
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                                {{ $transaction->total_orders }}</td>
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                                {{ $transaction->total_cod }}</td>
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                            {{ $transaction->details->sum('order_price') }}
                                            </td>
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                            {{ $transaction->details->sum('order_price') - $transaction->total_cod }}
                                                </td>
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                                {{ $transaction->total_shipping }}</td>
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                                {{ $transaction->details->sum('order_shipping') }}</td>
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                                {{ $transaction->details->sum('order_shipping') - $transaction->total_shipping }}
                                            </td>
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                                {{ $transaction->total_net }}</td>
                                            
                                            
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                                {{ $transaction->success_orders }}</td>
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                                {{ $transaction->failed_orders }}</td>
                                            <!-- <td class="text-center py-4 px-4 border border-gray-300">
                                                {{ $transaction->note }}</td> -->
                                            <td class="text-center py-4 px-0 border border-gray-300">
                                                @if (auth()->user()->role_id == 8)
                                                    @if ($transaction->shipping_status == 'new')
                                                        <span
                                                            class="badge badge-primary text-white px-2 py-1 rounded">{{ 'New' }}</span>
                                                    @elseif($transaction->shipping_status == 'in_progress')
                                                        <span
                                                            class="badge badge-warning text-white px-2 py-1 rounded">{{ 'In Progress' }}</span>
                                                    @else
                                                        <span
                                                            class="badge badge-success text-white px-2 py-1 rounded">{{ 'Done' }}</span>
                                                    @endif
                                                @else
                                                    @if ($transaction->status == 'open')
                                                        <span
                                                            class="badge badge-primary text-white px-2 py-1 rounded">{{ $transaction->status }}</span>
                                                    @elseif($transaction->status == 'In Progress')
                                                    <span
                                                            class="badge badge-warning text-white px-2 py-1 rounded">{{ $transaction->status }}</span>
                                                    @else
                                                        <span
                                                            class="badge badge-secondary text-white px-2 py-1 rounded">{{ $transaction->status }}</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-right py-4 px-4 border border-gray-300">
                                                <div class="flex justify-center space-x-4">
                                                    @if (auth()->user()->role_id != 8)
                                                        <div class="flex flex-col items-center space-y-1">
                                                            @if ($transaction->status == 'open')
                                                                <a class="bg-blue-500 text-white px-2 py-1 rounded" href="{{ route('shipping_trx.show', $transaction->id) }}" title="Close">
                                                                    <i class="bi bi-door-open-fill"></i>
                                                                </a>
                                                                <span class="text-sm text-gray-600 text-center">Close Success</span>
                                                            @else
                                                                <a class="bg-yellow-500 text-white px-2 py-1 rounded" href="{{ route('shipping_trx.show', $transaction->id) }}" title="Show">
                                                                    <i class="bi bi-eye-fill"></i>
                                                                </a>
                                                                <span class="text-sm text-gray-600 text-center">Success Orders</span>
                                                            @endif
                                                        </div>
                                                        @if($transaction->failed_orders > 0)
                                                        <div class="flex flex-col items-center space-y-1">
                                                            <a class="bg-red-500 text-white px-2 py-1 rounded" href="{{ route('shipping_trx.show', ['id' => $transaction->id, 'status' => 'failed']) }}" title="Failed Orders">
                                                                <i class="bi bi-exclamation-octagon"></i>
                                                            </a>
                                                            <span class="text-sm text-gray-600 text-center">@if($transaction->status == "closed" || $transaction->status == "close") Failed Orders @else Close Failed @endif</span>
                                                        </div>
                                                        @endif

                                                        @if($transaction->status == "In Progress" || $transaction->status == "open")
                                                            <div class="flex flex-col items-center space-y-1">
                                                                <a class="bg-green-500 text-white px-2 py-1 rounded" href="{{ route('shipping_trx.close', ['id' => $transaction->id]) }}" title="Close Transaction"
                                                                onclick="return confirm('Are you sure you want to close this transaction?');">
                                                                    <i class="bi bi-x-circle-fill"></i>
                                                                </a>
                                                                <span class="text-sm text-gray-600 text-center">Mark as Closed</span>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    {{-- <div class="flex flex-col items-center space-y-1">
                                                        <a class="bg-yellow-800 text-white px-2 py-1 rounded" download href="{{ $transaction->sheet }}" title="Download">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                        <span class="text-sm text-gray-600 text-center">Download</span>
                                                    </div> --}}

                                                    @if ($transaction->shipping_status == 'new')
                                                        <div class="flex flex-col items-center space-y-1">
                                                            <a class="bg-red-500 text-white px-2 py-1 rounded" href="{{ $transaction->sheet }}" title="Delete">
                                                                <i class="bi bi-x"></i>
                                                            </a>
                                                            <span class="text-sm text-gray-600 text-center">Delete</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-5">
                            {{ $transactions->appends(request()->input())->links() }}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        $("ul#finance").siblings('a').attr('aria-expanded', 'true');
        $("ul#finance").addClass("show");
        $("#transactions").addClass("active");
    </script>
@endsection
