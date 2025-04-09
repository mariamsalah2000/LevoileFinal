@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">{{ $productName }}'s Variants</h1>
        <!-- Card for displaying tickets -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5>List of Variants</h5>
            </div>
            <div class="card-body">
                @if ($variants->isEmpty())
                    <div class="alert alert-warning" role="alert">
                        No Variants found for {{ $productName }}.
                    </div>
                @else
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Title</th>
                                <th>Image</th>
                                <th>price</th>
                                <th>Quantity</th>
                                <th>Color</th>
                                <th>Sizes</th>
                                <th>Materials</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($variants as $variant)
                                @php
                                    $imageSrc = 'N/A';
                                    $product = $variant->product;
                                    if ($product) {
                                        $images = collect(json_decode($product->images));

                                        $product_img2 = $images->where('id', $variant->image_id)->first();
                                        if ($product_img2 && $product_img2->src != null && $product_img2->src != '') {
                                            $imageSrc = $product_img2->src;
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $variant->title }}</td>
                                    <td>
                                        @if ($imageSrc !== 'N/A')
                                            <img src="{{ $imageSrc }}" alt="Product Image"
                                                style="max-width: 100px; max-height: 100px;">
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $variant->price }}</td>
                                    <td>{{ $variant->inventory_quantity }}</td>
                                    <td>{{ $variant->option1 }}</td>
                                    <td>{{ $variant->option2 }}</td>
                                    <td>{{ $variant->option3 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('ads.index') }}" class="btn btn-secondary">Back to All Ads</a>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Any additional JavaScript if needed
    </script>
@endsection
