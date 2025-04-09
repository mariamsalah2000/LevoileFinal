@extends('layouts.app')
@section('css')
    <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="pagetitle">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold">Return Collections</h1>
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
                                <label class="text-sm font-medium">{{ 'Choose Collection Date' }}</label>
                            </div>
                            <div class="col-span-2">
                                <input type="date" class="form-control border-gray-300 rounded-md"
                                    value="{{ $date }}" name="date" placeholder="{{ 'Filter by Date' }}"
                                    autocomplete="off">
                            </div>
                            <div class="col-span-4">
                                <input type="text" class="form-control border-gray-300 rounded-md"
                                    value="{{ $search }}" name="search"
                                    placeholder="{{ 'Enter Collection Reference' }}">
                            </div>
                            <div class="col-span-2">
                                <select class="form-select border-gray-300 rounded-md" name="shipping" id="branch_id">
                                    <option value="">Choose Status</option>
                                    <option value="open" @if ($status == 'open') selected @endif>Open</option>
                                    <option value="closed" @if ($status == 'closed') selected @endif>Closed</option>
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
                                        <th class="text-center py-3 px-4 border border-gray-300">Collection Date</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Collection Reference</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Created By</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Total Orders</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Full Return Orders</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Partial Return Orders</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Failed Orders</th>
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
                                                #{{ $transaction->reference }}</td>
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                                {{ optional($transaction->user)->name ?? '-' }}</td>
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                                {{ $transaction->total_orders }}</td>
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                                {{ $transaction->full_returns }}</td>
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                                {{ $transaction->partial_returns }}</td>
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                                    {{ $transaction->details->where('status','failed')->count() }}</td>
                                            <td class="text-center py-4 px-0 border border-gray-300">
                                                
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
                                            </td>
                                            <td class="text-right py-4 px-4 border border-gray-300">
                                                <div class="flex justify-center space-x-4">
                                                    @if (auth()->user()->role_id != 8)
                                                        <div class="flex flex-col items-center space-y-1">
                                                            @if ($transaction->status == 'open')
                                                                <a class="bg-blue-500 text-white px-2 py-1 rounded" href="{{ route('return_collections.show', $transaction->id) }}" title="Close">
                                                                    <i class="bi bi-door-open-fill"></i>
                                                                </a>
                                                                <span class="text-sm text-gray-600 text-center">Close Full</span>
                                                            @else
                                                                <a class="bg-yellow-500 text-white px-2 py-1 rounded" href="{{ route('return_collections.show', $transaction->id) }}" title="Show">
                                                                    <i class="bi bi-eye-fill"></i>
                                                                </a>
                                                                <span class="text-sm text-gray-600 text-center">Full Returns</span>
                                                            @endif
                                                        </div>
                                                        @if($transaction->partial_returns > 0)
                                                        <div class="flex flex-col items-center space-y-1">
                                                            <a class="bg-red-500 text-white px-2 py-1 rounded" href="{{ route('return_collections.show', ['id' => $transaction->id, 'status' => 'partial']) }}" title="Partial Returns">
                                                                <i class="bi bi-exclamation-octagon"></i>
                                                            </a>
                                                            <span class="text-sm text-gray-600 text-center">@if($transaction->status == "closed" || $transaction->status == "close") Partial Returns @else Close Partial @endif</span>
                                                        </div>
                                                        @endif
                                                        <div class="flex flex-col items-center space-y-1">

                                                        <a class="bg-red-600 text-white px-2 py-1 rounded" href="{{ route('return_collections.show', ['id' => $transaction->id, 'status' => 'failed']) }}" title="Show">
                                                            <i class="bi bi-eye-fill"></i>
                                                        </a>
                                                        <span class="text-sm text-gray-600 text-center">Failed Returns</span>
                                                        </div>
                                                        @if($transaction->status == "In Progress" || $transaction->status == "open")
                                                            <div class="flex flex-col items-center space-y-1">
                                                                <a class="bg-green-500 text-white px-2 py-1 rounded" href="{{ route('return_collections.close', ['id' => $transaction->id]) }}" title="Close Collection"
                                                                onclick="return confirm('Are you sure you want to close this Collection?');">
                                                                    <i class="bi bi-x-circle-fill"></i>
                                                                </a>
                                                                <span class="text-sm text-gray-600 text-center">Mark as Closed</span>
                                                            </div>
                                                        @endif
                                                    @endif

                                                    <div class="flex flex-col items-center space-y-1">
                                                        <a class="bg-yellow-800 text-white px-2 py-1 rounded" download href="{{ $transaction->sheet }}" title="Download">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                        <span class="text-sm text-gray-600 text-center">Download</span>
                                                    </div>

                                                    @if ($transaction->shipping_status == 'new')
                                                        <div class="flex flex-col items-center space-y-1">
                                                            <a class="bg-red-500 text-white px-2 py-1 rounded" title="Delete">
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
        $("ul#returned_orders").siblings('a').attr('aria-expanded', 'true');
        $("ul#returned_orders").addClass("show");
        $("#finance_return_collections").addClass("active");
    </script>
@endsection
