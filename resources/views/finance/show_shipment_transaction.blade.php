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
    <div class="container mx-auto py-8 px-4" style="width: 99%;">
        <!-- Page Title Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Shipping Transaction Details</h1>
            <nav>
                <ol class="flex space-x-2 text-sm text-gray-600">
                    <li><a href="{{ route('home') }}" class="text-blue-600 hover:underline">Home</a></li>
                    <li>/</li>
                    <li>Transaction Details</li>
                </ol>
            </nav>
        </div>

        <section class="bg-white shadow-lg rounded-lg p-6 mb-8">
            <!-- Success Products Section -->
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-xl font-semibold text-gray-800">Success Orders</h4>
                <a href="{{ route('shipping_trx.export_success', $trx->id) }}"
                    class="bg-yellow-500 text-white px-5 py-2 rounded-lg hover:bg-yellow-600 transition duration-150">
                    Export
                </a>
            </div>
            <div class="overflow-auto rounded-lg border border-gray-200 shadow" style="max-height: 1000px;">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <!-- Table Headers -->
                            @foreach (['#', 'Order Number', 'Website Price', 'COD', 'Shipping', 'Website Shipping', 'Net', 'COD Difference', 'Shipping Difference', 'Reason', 'Status'] as $header)
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">
                                    {{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($details->where('status', 'success') as $key => $success)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-center">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-center">{{ $success->order_id }}</td>
                                <td class="px-6 py-4 text-center">{{ $success->order_price }} LE</td>
                                <td class="px-6 py-4 text-center">{{ $success->cod }} LE</td>
                                <td class="px-6 py-4 text-center">{{ $success->shipping }} LE</td>
                                <td class="px-6 py-4 text-center">{{ $success->order_shipping }} LE</td>
                                <td class="px-6 py-4 text-center">{{ $success->net }} LE</td>
                                <td class="px-6 py-4 text-center">{{ $success->order_price - $success->cod }} LE</td>
                                <td class="px-6 py-4 text-center">{{ $success->order_shipping - $success->shipping }} LE
                                </td>
                                <td class="px-6 py-4 text-center">{{ $success->reason }}</td>
                                <td class="px-6 py-4 text-center">{{ $success->order_status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <!-- Footer Totals -->
                            <th colspan="2" class="px-6 py-3 text-center font-semibold">Totals</th>
                            <td class="px-6 py-3 text-center">
                                {{ $details->where('status', 'success')->sum('order_price') }} LE</td>
                            <td class="px-6 py-3 text-center">{{ $details->where('status', 'success')->sum('cod') }} LE
                            </td>
                            <td class="px-6 py-3 text-center">{{ $details->where('status', 'success')->sum('shipping') }}
                                LE</td>
                            <td class="px-6 py-3 text-center">
                                {{ $details->where('status', 'success')->sum('order_shipping') }} LE</td>
                            <td class="px-6 py-3 text-center">{{ $details->where('status', 'success')->sum('net') }} LE
                            </td>
                            <td class="px-6 py-3 text-center">
                                {{ $details->where('status', 'success')->sum('order_price') - $details->where('status', 'success')->sum('cod') }}
                                LE</td>
                            <td class="px-6 py-3 text-center">
                                {{ $details->where('status', 'success')->sum('order_shipping') - $details->where('status', 'success')->sum('shipping') }}
                                LE</td>
                            <td colspan="2" class="px-6 py-3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>

        <!-- Failed Products Section -->
        <!-- <section class="bg-white shadow-lg rounded-lg p-6 mb-8">
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
            <div class="overflow-auto rounded-lg border border-gray-200 shadow">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach (['#', 'Order Number', 'Website Price', 'COD', 'Shipping', 'Website Shipping', 'Net', 'COD Difference', 'Shipping Difference', 'Reason', 'Action'] as $header)
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">
                                    {{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
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
                                <td class="px-6 py-4 text-center" id="options">
                                    @if ($trx->status == 'open')
                                        <button
                                            class="bg-green-500 text-white px-3 py-2 rounded-lg hover:bg-green-600 transition duration-150"
                                            onclick="approveTrxItem2({{ $failed->id }})">
                                            Approve
                                        </button>
                                    @endif
                                </td>
                                <td colspan="4" class="px-2 py-4 text-center align-top"
                                    id="approve_item_{{ $failed->id }}" style="display: none">
                                    <form action="{{ route('shipping_trx.approve') }}" method="GET"
                                        class="items-center space-x-2">
                                        <input type="hidden" name="id" value="{{ $failed->id }}">
                                        <div>
                                            <label for="reason">Reason *</label>
                                            <input type="text" name="reason" class="form-control"
                                                placeholder="Enter Reason" required>
                                        </div>
                                        <div>
                                            <label for="status">Desired Status *</label>
                                            <select name="status" class="form-select" required>
                                                <option value="returned">Returned</option>
                                                <option value="delivered">Delivered</option>
                                            </select>
                                        </div>
                                        <div class="flex items-center pt-4 pl-8">
                                            <button type="submit" class="btn btn-danger">Submit</button>
                                        </div>
                                    </form>
                                </td>


                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section> -->

        <!-- Submit Form for Open Transactions -->
        @if ($trx->status == 'open' && count($details->where('status', 'success')) > 0)
            <div class="flex justify-end">
                <button type="submit" onclick="showPopup({{ $trx->id }})" id="submit_shipping_trx"
                    class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition duration-150">
                    Submit
                </button>
            </div>
            <!-- <form action="{{ route('shipping_trx.submit') }}" method="post" class="flex justify-end">
                @csrf
                <input type="hidden" name="trx_id" value="">
                
            </form> -->
        @endif
    </div>

    <div class="modal fade" id="loading" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body fulfillment_form">
                    <div id="loading-text">Processing... <span id="loading-percentage">0%</span></div>
                    <div class="progress">
                        <div class="progress-bar" id="progress-bar" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
        </div>
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

        document.addEventListener('DOMContentLoaded', function () {
            if ($("#loading").hasClass("show")) {
                const modalElement = document.getElementById('loading');
                const modal = new bootstrap.Modal(modalElement, {
                    backdrop: 'static', // Prevent clicking outside
                    keyboard: false     // Disable Esc key
                });

                // Show the modal when needed
                modal.show();
            }
        });

        document.addEventListener("keydown", function (event) {
            if ((event.key === "F5") || (event.ctrlKey && event.key === "r")) {
                event.preventDefault();
                if ($("#loading").hasClass("show")) {
                    alert("The process is still running. Refreshing is disabled.");
                }
            }
        });

        // Prevent user from closing the tab or reloading the page
        // window.addEventListener("beforeunload", function (event) {
        //     if ($("#loading").hasClass("show")) {
        //         event.preventDefault();
        //         event.returnValue = ""; // This is required for most browsers to show a warning
        //     }
        // });

        function showPopup(trx) {
            let progress = 0;
            $("#loading").modal("show");
            $("#submit_shipping_trx").prop('disabled',true);
            document.getElementById('submit_shipping_trx').innerHTML = "Uploading";

            const interval = setInterval(() => {
                if (progress >= 100) {
                    clearInterval(interval);
                } else {
                    progress += 1; // Increase progress
                    $("#loading-percentage").text(progress + "%"); // Update percentage text
                    $("#progress-bar").css("width", progress + "%"); // Update progress bar width
                }
            }, 1500); // Adjust speed as needed

            $.ajax({
                url: "{{ route('shipping_trx.submit') }}",
                type: 'POST',
                data: {
                    trx_id: trx,
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    alert('Transaction Submitted Successfully');
                    window.location.reload();
                },
                error: function() {
                    
                    alert('Error submitting trx. Please try again.');
                    window.location.reload();
                }
            });
        }


        function approveTrxItem2(id) {
            var item = "approve_item_" + id;
            
            document.getElementById(item).style.display = "block";
            document.getElementById("options").style.display = "none";
        }
    </script>
@endsection
