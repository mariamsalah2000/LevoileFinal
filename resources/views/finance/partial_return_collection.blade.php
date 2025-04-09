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
            <div class="flex space-x-4">
                <a href="{{ route('return_collection.export_partial', $trx->id) }}"
                    class="bg-yellow-500 text-white px-5 py-2 rounded-lg hover:bg-yellow-600 transition duration-150">
                    Export
                </a>
            </div>
            <div class="overflow-auto rounded-lg border border-gray-200 shadow" style="max-height: 1000px;">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <!-- Table Headers -->
                            @foreach (['#', 'Order Number','COD','Status','Action'] as $header)
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">
                                    {{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($details->where('status', 'partial') as $key => $partial)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-center">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-center">{{ $partial->order_id }}</td>
                                <td class="px-6 py-4 text-center">{{ $partial->cod }} LE</td>
                                <td class="px-6 py-4 text-center">{{ $partial->status }}</td>
                                <td class="px-6 py-4 text-center" id="options_{{$partial->id}}">
                                    @if($trx->status != "closed" && $trx->status!="close" && $partial->reason != "returned")
                                    <a
                                        class="bg-green-500 text-white px-3 py-2 rounded-lg hover:bg-green-600 transition duration-150"
                                        href="{{ route('partial_return.new',$partial->order_id) }}">
                                        Approve
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <!-- Footer Totals -->
                            <th colspan="2" class="px-6 py-3 text-center font-semibold">Totals</th>
                            <td class="px-6 py-3 text-center">{{ $details->where('status', 'partial')->sum('cod') }} LE
                            </td>
                            
                            <td colspan="2" class="px-6 py-3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
        
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $("ul#returned_orders").siblings('a').attr('aria-expanded', 'true');
        $("ul#returned_orders").addClass("show");
        $("#finance_return_collections").addClass("active");


        document.addEventListener("keydown", function (event) {
            if ((event.key === "F5") || (event.ctrlKey && event.key === "r")) {
                event.preventDefault();
                if ($("#loading").hasClass("show")) {
                    alert("The process is still running. Refreshing is disabled.");
                }
            }
        });
    </script>
@endsection
