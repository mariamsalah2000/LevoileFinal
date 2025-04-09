@extends('layouts.app')
@section('css')
    <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="pagetitle">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold">Shipment Collections</h1>
            <nav>
                <ol class="breadcrumb flex space-x-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-blue-600">Home</a></li>
                    <li class="breadcrumb-item">Shipping</li>
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
                                    placeholder="{{ 'Enter Transaction Reference or Collection Number' }}">
                            </div>
                            <div class="col-span-2">
                                <select class="form-select border-gray-300 rounded-md" name="shipping" id="branch_id">
                                    <option value="">Choose Status</option>
                                    <option value="new" @if ($status == 'new') selected @endif>New</option>
                                    <option value="converted" @if ($status == 'converted') selected @endif>Converted</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>

                        <div class="overflow-auto rounded-lg border border-gray-200 shadow">
                            <table class="min-w-full table-auto border-collapse border border-gray-300">
                                <thead class="bg-gray-200 sticky top-0 z-10">
                                    <tr>
                                        <th class="text-center py-3 px-4 border border-gray-300">ID</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Collection Date</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Reference</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Created By</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Total Orders</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Note</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Status</th>
                                        <th class="text-center py-3 px-4 border border-gray-300">Converted By</th>
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
                                            <!-- <td class="text-center py-4 px-4 border border-gray-300">
                                                {{ $transaction->shipment_number }}</td> -->
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                                {{ optional($transaction->user)->name ?? '-' }}</td>
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                                    {{ $transaction->total_orders }}</td>
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                                {{ $transaction->note }}</td>
                                            <td class="text-center py-4 px-0 border border-gray-300">
                                                <span class="bg-green-500 text-white px-2 py-1 rounded">{{ $transaction->status }}</span>
                                            </td>
                                            <td class="text-center py-4 px-4 border border-gray-300">
                                                {{ optional($transaction->converted)->name ?? "-" }}</td>
                                            <td class="text-right py-4 px-4 border border-gray-300">
                                                <div class="flex justify-center space-x-4">
                                                    @if (auth()->user()->role_id != 8)
                                                        <div class="flex flex-col items-center space-y-1">
                                                            @if ($transaction->status == 'new')
                                                                <a class="bg-blue-500 text-white px-2 py-1 rounded" href="{{ route('collections.convert', $transaction->id) }}" title="Convert">
                                                                    <i class="bi bi-arrow-repeat"></i>
                                                                </a>
                                                                <span class="text-sm text-gray-600 text-center">Convert into Transaction</span>
                                                            
                                                            @endif
                                                        </div>
                                                        
                                                    @elseif ($transaction->status == 'new')
                                                        <div class="flex flex-col items-center space-y-1">
                                                            <a class="bg-red-500 text-white px-2 py-1 rounded" href="{{ route('collections.delete',$transaction->id) }}" title="Delete">
                                                                <i class="bi bi-x"></i>
                                                            </a>
                                                            <span class="text-sm text-gray-600 text-center">Delete</span>
                                                        </div>
                                                    @endif

                                                    <div class="flex flex-col items-center space-y-1">
                                                        <a class="bg-yellow-800 text-white px-2 py-1 rounded" download href="{{ asset($transaction->sheet) }}" title="Download">
                                                            <i class="bi bi-download"></i>
                                                        </a>
                                                        <span class="text-sm text-gray-600 text-center">Download</span>
                                                    </div>

                                                    
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
        $("ul#shipping").siblings('a').attr('aria-expanded', 'true');
        $("ul#shipping").addClass("show");
        $("#collections").addClass("active");
    </script>
@endsection
