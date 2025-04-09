@extends('layouts.app')
@section('css')
    <link href="{{ asset('assets/css/bootstrap-rtl.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

@endsection
@section('content')
<div class="container mx-auto py-8 px-4">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Stock Request Details</h2>
        <div class="flex flex-col md:flex-row items-start md:items-center space-y-2 md:space-y-0 md:space-x-4">
            <span class="text-gray-500">Request Reference: <strong class="text-gray-800">#{{ $stockRequest->ref }}</strong></span>
            <span class="text-gray-500">Branch: <strong class="text-gray-800">{{ $stockRequest->branch->name }}</strong></span>
            <button onclick="exportTableToCSV('stock_request_details.csv',{{ $stockRequest->id }})"
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition duration-150">
                Export to CSV
            </button>
        </div>
    </div>

    <!-- Request Summary Section -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Request Information</h3>
                <p class="text-gray-600">Date: {{ date('d/m/Y', strtotime($stockRequest->created_at)) }}</p>
                <p class="text-gray-600">Downloaded Date: {{ date('d/m/Y', strtotime($stockRequest->downloaded_at)) }}</p>
            </div>
            @if($stockRequest->status == "pending")
            <span class="inline-block bg-yellow-50 text-yellow-600 text-sm font-medium px-4 py-2 rounded-full">
                Status: {{ $stockRequest->status }}
            </span>
            @else
            <span class="inline-block bg-green-50 text-green-600 text-sm font-medium px-4 py-2 rounded-full">
                Status: {{ $stockRequest->status }}
            </span>
            @endif
        </div>
    </div>

    <!-- Stock Request Details Table -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <table class="min-w-full bg-white text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-600 uppercase tracking-wider">Image</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-600 uppercase tracking-wider">Product Name</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-600 uppercase tracking-wider">SKU</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">Needed Qty</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-600 uppercase tracking-wider text-center">Available Qty</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="py-4 px-6 text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($stockRequest->details as $detail)
                    <tr class="hover:bg-gray-50">
                        <td class="py-4 px-6"><img width="60px" height="60px" src="{{ $detail->img }}" class="rounded-md"></td>
                        <td class="py-4 px-6 text-gray-800 font-medium">{{ $detail->product_name }}</td>
                        <td class="py-4 px-6 text-gray-500">{{ $detail->sku }}</td>
                        <td class="py-4 px-6 text-center text-gray-800">{{ $detail->needed_qty }}</td>
                        <td class="py-4 px-6 text-center text-gray-800">{{ $detail->delivered_qty }}</td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $detail->status == 'Fulfilled' ? 'bg-green-50 text-green-600' : 'bg-yellow-50 text-yellow-600' }}">
                                {{ $detail->status }}
                            </span>
                        </td>
                        <td class="py-4 px-6 space-x-2">
                            <button class="text-blue-600 hover:text-blue-700 text-sm"
                                    onclick="handleEdit({{ $detail->id }})">Edit</button>
                            <button class="text-red-600 hover:text-red-700 text-sm"
                                    onclick="handleDelete({{ $detail->id }})">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<!-- JavaScript for CSV Export and Action Handlers -->
<script>
    $("ul#branches").siblings('a').attr('aria-expanded', 'true');
    $("ul#branches").addClass("show");
    $("#requests").addClass("active");

    function exportTableToCSV(filename,id) {

        $.ajax({
            url: '/stock-requests/'+id+'/download',
            type: 'GET',
            success: function(response) {
                
            },
            error: function() {
                alert('Something Went Wrong Please Try Again Later');
            }
        });
        const csv = [];
        // Find the table element by its class name
        const table = document.querySelector(".min-w-full");

        // Select all rows in the table
        const rows = table.querySelectorAll("tr");

        rows.forEach(row => {
        // Get cells within each row, excluding the last cell (Actions column)
            const cols = Array.from(row.querySelectorAll("td, th")).slice(0, -1);
            const rowData = cols.map(col => {
                const img = col.querySelector("img");
                // If an image is found, get the src attribute; otherwise, get cell text
                return img ? `"${img.src}"` : `"${col.innerText.trim()}"`;
            });
            csv.push(rowData.join(","));
        });

        // Create a CSV Blob and download link
        const csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
        const downloadLink = document.createElement("a");
        downloadLink.href = URL.createObjectURL(csvFile);
        downloadLink.download = filename;

        // Append the link, trigger the download, and remove the link
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }
    function handleEdit(id) {
        alert("Edit functionality coming soon for item " + id);
    }

    function handleDelete(id) {
        if (confirm("Are you sure you want to delete this item?")) {
            alert("Deleted item " + id);
        }
    }
</script>
@endsection
