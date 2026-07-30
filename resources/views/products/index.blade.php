@extends('layouts.admin')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📦 Products List</h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary">➕ Add New Product</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('products.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Filter by Status</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th width="20%">Details</th>
                            <th>Images</th>
                            <th>Size</th>
                            <th>Color</th>
                            <th>Category</th>
                            <th>Price (₹)</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($products as $product)
                            @php
                                $images = is_array($product->images) ? $product->images : [];
                            @endphp

                            <tr>
                                <td class="fw-semibold">{{ $product->name }}</td>
                                <td>{{ Str::limit($product->details, 60) }}</td>

                                <td>
                                    @if(!empty($images))
                                        <div class="d-flex flex-wrap">
                                            @foreach($images as $index => $img)
                                                @if($index < 3)
                                                    <img src="{{ asset($img) }}" width="60"
                                                         class="rounded shadow-sm border me-1 mb-1">
                                                @endif
                                            @endforeach

                                            @if(count($images) > 3)
                                                <span class="badge bg-secondary">
                                                    +{{ count($images) - 3 }} more
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">No Images</span>
                                    @endif
                                </td>

                                <td>{{ $product->size }}</td>
                                <td>{{ $product->color }}</td>
                                <td>{{ $product->category }}</td>

                                <td class="fw-bold text-success">₹{{ number_format($product->price) }}</td>

                                <td>
                                    @php
                                        $badgeClass = match($product->status) {
                                            'active' => 'bg-success',
                                            'inactive' => 'bg-danger',
                                            'draft' => 'bg-warning text-dark',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($product->status) }}</span>
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('products.edit', $product) }}"
                                       class="btn btn-warning btn-sm me-1">✏ Edit</a>

                                    <form action="{{ route('products.destroy', $product) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('Delete this product?')">
                                            🗑 Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    No products found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($products->hasPages())
            <div class="mt-3">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
