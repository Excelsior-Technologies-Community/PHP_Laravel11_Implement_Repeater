@extends('layouts.customer')

@section('content')

<style>

    .product-card {
        height: 600px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .product-img {
        height: 300px;
        width: 100%;
        object-fit: cover;
        border-bottom: 1px solid #ddd;
    }

    .product-details {
        height: 280px;
        overflow: hidden;
    }

</style>


<div class="container">

    <div class="row">

        @forelse($products as $product)

            @php

                $images = is_array($product->images)
                    ? $product->images
                    : [];

                $primaryImage = $product->primary_image;

            @endphp


            <div class="col-md-3 mb-4">

                <div class="card shadow-sm border-0 product-card">


                    {{-- PRIMARY IMAGE --}}

                    @if($primaryImage)

                        <img src="{{ asset($primaryImage) }}"
                             class="product-img"
                             alt="{{ $product->name }}">

                    @else

                        <div class="product-img d-flex
                                    align-items-center
                                    justify-content-center
                                    bg-light">

                            <span class="text-muted">
                                No Image
                            </span>

                        </div>

                    @endif


                    <div class="card-body product-details">


                        {{-- PRODUCT NAME --}}

                        <h5 class="card-title fw-bold">

                            {{ $product->name }}

                        </h5>


                        {{-- DETAILS --}}

                        <p class="text-muted small">

                            {{ Str::limit($product->details, 70) }}

                        </p>


                        {{-- PRODUCT INFORMATION --}}

                        <ul class="list-unstyled mb-2">

                            <li>
                                <strong>Category:</strong>
                                {{ $product->category }}
                            </li>

                            <li>
                                <strong>Size:</strong>
                                {{ $product->size }}
                            </li>

                            <li>
                                <strong>Color:</strong>
                                {{ $product->color }}
                            </li>

                            <li>
                                <strong>Price:</strong>
                                ₹{{ number_format($product->price, 2) }}
                            </li>

                        </ul>


                        {{-- VARIANTS --}}

                        @if($product->variants->count())

                            <div class="mt-2">

                                <strong>
                                    Available Variants:
                                </strong>

                                <div class="mt-1">

                                    @foreach($product->variants as $variant)

                                        <div class="border rounded p-1 mb-1 small">

                                            <strong>
                                                {{ $variant->size }}
                                            </strong>

                                            -
                                            {{ $variant->color }}

                                            -
                                            ₹{{ number_format($variant->price, 2) }}

                                            @if($variant->stock > 0)

                                                <span class="badge bg-success float-end">
                                                    Stock: {{ $variant->stock }}
                                                </span>

                                            @else

                                                <span class="badge bg-danger float-end">
                                                    Out of Stock
                                                </span>

                                            @endif

                                        </div>

                                    @endforeach

                                </div>

                            </div>

                        @endif


                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">

                <div class="alert alert-info text-center">

                    No active products available.

                </div>

            </div>

        @endforelse

    </div>

</div>

@endsection