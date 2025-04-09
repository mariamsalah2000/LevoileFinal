@extends('layouts.app')

@section('css')
    <style>
        
        .badge-success {
            background-color: #28a745;
        }

        .badge-danger {
            background-color: #dc3545;
        }

    </style>
@endsection

@section('content')
<div class="container">
    <h1>{{$collection->name}}'s Collection Details</h1>




    <!-- Ads Table Section -->
    <div class="mt-5">
    <div class="card-header bg-primary text-white">
        <h5>{{$collection->name}}'s Collection Details</h5>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Created</th>
                <th>Product</th>
                <th>Image</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Availability</th>
                <th>Status</th>
                <th>Activated</th>
                <th>De-Activated</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($allCollectionDetails as $adCollection)
                <tr>
                <td>{{ \Carbon\Carbon::parse($adCollection->collection->created_at)->format('F j, Y h:i A') }}</td>
                <td>{{$adCollection->ad->product->title}}</td>
                    @php
                        $images = json_decode($adCollection->ad->product->images, true); // Assuming images are stored as JSON
                        $imageSrc = !empty($images) && isset($images[0]['src']) ? $images[0]['src'] : 'N/A';
                    @endphp
                    <td>
                        @if ($imageSrc !== 'N/A')
                            <img src="{{ $imageSrc }}" alt="Product Image" style="max-width: 100px; max-height: 100px; object-fit: cover;">
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @php
                            $variants = json_decode($adCollection->ad->product->variants, true);
                            $price = !empty($variants) && isset($variants[0]['price']) ? $variants[0]['price'] : 'N/A';
                            $inventory_quantity = !empty($variants) && isset($variants[0]['inventory_quantity']) ? $variants[0]['inventory_quantity'] : 'N/A';
                        @endphp
                        {{ $price }}
                    </td>
                    <td>{{ $inventory_quantity }}</td>
                    
                    <td>
                        <span class="badge {{ $adCollection->stock_status === 'Stocked' ? 'badge-success' : 'badge-danger' }}">
                            {{$adCollection->stock_status}}
                        </span>
                    </td>

                    <td>
                        <span class="badge {{ $adCollection->status === 'activated' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($adCollection->status) }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($adCollection->activated_at)->format('F j, Y h:i A') }}</td>
                    <td>{{ \Carbon\Carbon::parse($adCollection->deactivated_at)->format('F j, Y h:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


</div>


@endsection

@section('scripts')
<script>
    $("ul#Ad").siblings('a').attr('aria-expanded', 'true');
    $("ul#Ad").addClass("show");
    $("#all_Collections").addClass("active");

</script>

@endsection