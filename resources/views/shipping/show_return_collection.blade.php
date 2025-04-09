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
            <h1 class="text-3xl font-bold text-gray-800">Return Collection Details</h1>
            <nav>
                <ol class="flex space-x-2 text-sm text-gray-600">
                    <li><a href="{{ route('home') }}" class="text-blue-600 hover:underline">Home</a></li>
                    <li>/</li>
                    <li>Collection Details</li>
                </ol>
            </nav>
        </div>

        <section class="bg-white shadow-lg rounded-lg p-6 mb-8">
            <!-- Success Products Section -->
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-xl font-semibold text-gray-800">Full Return Orders</h4>
                <a href="{{ route('return_collection.export_full', $trx->id) }}"
                    class="bg-yellow-500 text-white px-5 py-2 rounded-lg hover:bg-yellow-600 transition duration-150">
                    Export
                </a>
            </div>
            <div class="overflow-auto rounded-lg border border-gray-200 shadow" style="max-height: 1000px;">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <!-- Table Headers -->
                            @foreach (['#', 'Order Number','COD','Status'] as $header)
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">
                                    {{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($details->where('status', 'full') as $key => $success)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-center">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-center">{{ $success->order_id }}</td>
                                <td class="px-6 py-4 text-center">{{ $success->cod }} LE</td>
                                <td class="px-6 py-4 text-center">{{ $success->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <!-- Footer Totals -->
                            <th colspan="2" class="px-6 py-3 text-center font-semibold">Totals</th>
                            <td class="px-6 py-3 text-center">{{ $details->where('status', 'full')->sum('cod') }} LE
                            </td>
                            
                            <td colspan="2" class="px-6 py-3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
        <!-- Submit Form for Open Transactions -->
        @if ($trx->status == 'open' && count($details->where('status', 'full')) > 0)
            <div class="flex justify-end">
                <button type="submit" onclick="showPopup({{ $trx->id }})" id="submit_return_collection"
                    class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition duration-150">
                    Submit
                </button>
            </div>
            <!-- <form action="{{ route('return_collection.submit') }}" method="post" class="flex justify-end">
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
        $("ul#shipping").siblings('a').attr('aria-expanded', 'true');
        $("ul#shipping").addClass("show");
        $("#returnCollection").addClass("active");

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
            $("#submit_return_collection").prop('disabled',true);
            document.getElementById('submit_return_collection').innerHTML = "Uploading";

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
                url: "{{ route('return_collection.submit') }}",
                type: 'POST',
                data: {
                    trx_id: trx,
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    alert('Collection Submitted Successfully');
                    window.location.reload();
                },
                error: function() {
                    
                    alert('Error submitting collection. Please try again.');
                    window.location.reload();
                }
            });
        }

    </script>
@endsection
