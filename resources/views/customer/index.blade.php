@extends('layouts.customer')

@section('content')

<style>
    .product-card {
        height: 500px;
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
        height: 160px;
        overflow: hidden;
    }
</style>

<div class="row">
    @foreach($products as $product)
        @php
            $images = is_array($product->images) ? $product->images : [];
            $firstImage = $images[0] ?? null;
        @endphp
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0 product-card">

                {{-- PRODUCT IMAGE --}}
                @if($firstImage)
                    <img src="{{ asset($firstImage) }}" class="product-img">
                @else
                    <img src="https://via.placeholder.com/300x220" class="product-img">
                @endif

                <div class="card-body product-details">

                    {{-- NAME --}}
                    <h5 class="card-title fw-bold">
                        <strong>Name:</strong> {{ $product->name }}
                    </h5>

                    {{-- DETAILS SHORT --}}
                    <p class="text-muted small">
                        <strong>Details:</strong>
                        {{ Str::limit($product->details, 70) }}
                    </p>

                    {{-- FULL DETAILS --}}
                    <ul class="list-unstyled mb-1">
                        <li><strong>Category:</strong> {{ $product->category }}</li>
                        <li><strong>Size:</strong> {{ $product->size }}</li>
                        <li><strong>Color:</strong> {{ $product->color }}</li>
                        <li><strong>Price:</strong> ₹{{ number_format($product->price) }}</li>
                    </ul>

                </div>

            </div>
        </div>
    @endforeach
</div>

@endsection
