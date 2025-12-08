@extends('layouts.admin')

@section('content')
<div class="container py-4">

    <!-- Header with title and Add New Product button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📦 Products List</h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary">➕ Add New Product</a>
    </div>

    <!-- Success message from session flash data -->
    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <!-- Main products table card with shadow styling -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <!-- Responsive hover table for products -->
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th width="20%">Details</th>
                            <th>Images</th>        <!-- Multiple images column -->
                            <th>Size</th>
                            <th>Color</th>
                            <th>Category</th>
                            <th>Price (₹)</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <!-- Loop through products collection -->
                        @forelse($products as $product)
                            <!-- Safely handle images array from database -->
                            @php
                                // Ensure images is always array (handles JSON or direct array storage)
                                $images = is_array($product->images) ? $product->images : [];
                            @endphp

                            <tr>
                                <!-- Product name (bold text) -->
                                <td class="fw-semibold">{{ $product->name }}</td>

                                <!-- Truncated product details (max 60 chars) -->
                                <td>{{ Str::limit($product->details, 60) }}</td>

                                <!-- MULTIPLE IMAGES DISPLAY (SHOW FIRST 3 + COUNTER) -->
                                <td>
                                    @if(!empty($images))
                                        <!-- Flex container for image gallery -->
                                        <div class="d-flex flex-wrap">
                                            @foreach($images as $index => $img)
                                                @if($index < 3)
                                                    <!-- Display first 3 images (60px thumbnails) -->
                                                    <img src="{{ asset($img) }}" width="60"
                                                         class="rounded shadow-sm border me-1 mb-1">
                                                @endif
                                            @endforeach

                                            @if(count($images) > 3)
                                                <!-- Badge showing additional images count -->
                                                <span class="badge bg-secondary">
                                                    +{{ count($images) - 3 }} more
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <!-- No images placeholder -->
                                        <span class="text-muted">No Images</span>
                                    @endif
                                </td>

                                <!-- Product attributes (simple display) -->
                                <td>{{ $product->size }}</td>
                                <td>{{ $product->color }}</td>
                                <td>{{ $product->category }}</td>

                                <!-- Formatted price with Indian Rupee symbol -->
                                <td class="fw-bold text-success">₹{{ number_format($product->price) }}</td>

                                <!-- Action buttons (centered) -->
                                <td class="text-center">
                                    <!-- Edit button with route model binding -->
                                    <a href="{{ route('products.edit', $product) }}"
                                       class="btn btn-warning btn-sm me-1">✏ Edit</a>

                                    <!-- Delete form using method spoofing -->
                                    <form action="{{ route('products.destroy', $product) }}"
                                          method="POST" class="d-inline">
                                        @csrf              <!-- CSRF token -->
                                        @method('DELETE')  <!-- Spoof DELETE method -->
                                        <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('Delete this product?')">
                                            🗑 Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>

                        @empty
                            <!-- Empty state when no products exist -->
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    No products found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
