@extends('layouts.app')
@section('css')
    <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .progress {
            width: 100%;
            height: 10px;
            background-color: #f1f1f1;
            border-radius: 5px;
            overflow: hidden;
            margin-top: 10px;
        }

        .progress-bar {
            height: 100%;
            background-color: #4caf50;
            transition: width 0.1s ease-in-out;
        }
    </style>
@endsection
@section('content')
    <div class="container mx-auto py-8 px-1" style="width: 99%;">
        <!-- Page Title Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Shipping Transaction Details</h1>
            <nav>
                <ol class="flex space-x-2 text-sm text-gray-600">
                    <li><a href="{{ route('home') }}" class="text-blue-600 hover:underline">Home</a></li>
                    <li>/</li>
                    <li>Failed Transaction Details</li>
                </ol>
            </nav>
        </div>

        <!-- Failed Products Section -->
        <section class="bg-white shadow-lg rounded-lg p-6 mb-8">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-xl font-semibold text-gray-800">Failed Orders</h4>
                <div class="flex space-x-4">
                    <a href="{{ route('shipping_trx.export_failed', $trx->id) }}"
                        class="bg-yellow-500 text-white px-5 py-2 rounded-lg hover:bg-yellow-600 transition duration-150">
                        Export
                    </a>
                    <a href="{{ route('shipping_trx.check_failed', $trx->id) }}"
                        class="bg-gray-200 text-gray-800 px-5 py-2 rounded-lg hover:bg-gray-300 transition duration-150">
                        Check All
                    </a>
                </div>
            </div>
            <div class="overflow-auto rounded-lg border border-gray-200 shadow" style="max-height: 1000px;">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0 z-10">
                        <tr>
                            @foreach (['#', 'Order Number', 'Website Price', 'COD', 'Shipping', 'Website Shipping', 'Net', 'COD Difference', 'Shipping Difference', 'Reason', 'Action'] as $header)
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase bg-gray-50">
                                    {{ $header }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($details->where('status', 'failed') as $key => $failed)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-center">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-center">{{ $failed->order_id }}</td>
                                <td class="px-6 py-4 text-center">{{ $failed->order_price }} LE</td>
                                <td class="px-6 py-4 text-center">{{ $failed->cod }} LE</td>
                                <td class="px-6 py-4 text-center">{{ $failed->shipping }} LE</td>
                                <td class="px-6 py-4 text-center">{{ $failed->order_shipping }} LE</td>
                                <td class="px-6 py-4 text-center">{{ $failed->net }} LE</td>
                                <td class="px-6 py-4 text-center">{{ $failed->order_price - $failed->cod }} LE</td>
                                <td class="px-6 py-4 text-center">{{ $failed->order_shipping - $failed->shipping }} LE</td>
                                <td class="px-6 py-4 text-center">{{ $failed->reason ?? '-' }}</td>
                                <td class="px-6 py-4 text-center" id="options_{{$failed->id}}">
                                    @if($trx->status != "closed" && $trx->status!="close" && $failed->reason != "Order not Found" && $failed->reason != "Order Not Found")
                                    <button
                                        class="bg-green-500 text-white px-3 py-2 rounded-lg hover:bg-green-600 transition duration-150"
                                        onclick="approveTrxItem2({{ $failed->id }})">
                                        Approve
                                    </button>
                                    @endif
                                </td>
                                
                            </tr>
                            <!-- Approval Modal -->
                            <div class="modal fade" id="approve_item_{{$failed->id}}" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="approveModalLabel">Approve Shipping Transaction</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('shipping_trx.approve') }}" method="GET">
                                            <input type="hidden" name="id" value="{{ $failed->id }}">

                                                <div class="mb-3">
                                                    <label for="reason">Reason *</label>
                                                    <input type="text" name="reason" id="reason" class="form-control" placeholder="Enter Reason" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="status">Desired Status *</label>
                                                    <select name="status" id="status" class="form-select" required>
                                                        <option value="returned">Returned</option>
                                                        <option value="delivered">Delivered</option>
                                                    </select>
                                                </div>

                                                <div class="d-flex justify-content-end">
                                                    <button type="submit" class="btn btn-danger">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>

@endsection
@section('scripts')
    <script type="text/javascript">
        $("ul#finance").siblings('a').attr('aria-expanded', 'true');
        $("ul#finance").addClass("show");
        $("#transactions").addClass("active");


        function approveTrxItem(id) {
            $.ajax({

                url: "{{ route('shipping_trx.approve') }}",
                type: 'GET',
                data: {
                    id: id
                },
                success: function(response) {
                    if (response.message == '') {
                        location.reload();
                    } else {
                        location.reload();
                    }
                }
            });
        }

        function approveTrxItem2(id) {
            var item = "approve_item_" + id;
            $("#"+item).modal("show");
            // document.getElementById("options_"+id).style.display = "none";
        }
    </script>
@endsection
