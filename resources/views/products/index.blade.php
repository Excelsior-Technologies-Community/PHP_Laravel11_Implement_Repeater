@extends('layouts.admin')

@section('content')

<div class="container py-4">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="fw-bold">
            📦 Products List
        </h2>

        <a href="{{ route('products.create') }}"
           class="btn btn-primary">

            ➕ Add New Product

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success shadow-sm">

            {{ session('success') }}

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- STATUS FILTER --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm border-0 mb-3">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('products.index') }}"
                  class="row g-3">

                <div class="col-md-4">

                    <label class="form-label fw-bold">
                        Filter by Status
                    </label>

                    <select name="status"
                            class="form-select"
                            onchange="this.form.submit()">

                        <option value="">
                            All Statuses
                        </option>

                        @foreach($statuses as $status)

                            <option value="{{ $status }}"
                                {{ request('status') === $status ? 'selected' : '' }}>

                                {{ ucfirst($status) }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-2 d-flex align-items-end">

                    <a href="{{ route('products.index') }}"
                       class="btn btn-secondary">

                        Reset

                    </a>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- PRODUCTS TABLE --}}
    {{-- ========================================================= --}}

    <div class="card shadow-sm border-0">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0 align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>Name</th>

                            <th width="20%">
                                Details
                            </th>

                            <th>
                                Images
                            </th>

                            <th>
                                Size
                            </th>

                            <th>
                                Color
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Price (₹)
                            </th>

                            <th>
                                Variants
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-center">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($products as $product)

                            @php

                                $images = is_array($product->images)
                                    ? $product->images
                                    : [];

                            @endphp


                            {{-- ================================================= --}}
                            {{-- PRODUCT ROW --}}
                            {{-- ================================================= --}}

                            <tr>

                                {{-- NAME --}}
                                <td class="fw-semibold">

                                    {{ $product->name }}

                                </td>


                                {{-- DETAILS --}}
                                <td>

                                    {{ Str::limit($product->details, 60) }}

                                </td>


                                {{-- IMAGES --}}
                                <td>

                                    @if(!empty($images))

                                        <div class="d-flex flex-wrap">

                                            @foreach($images as $index => $img)

                                                @if($index < 3)

                                                    <div class="position-relative me-1 mb-1">

                                                        <img src="{{ asset($img) }}"
                                                             width="60"
                                                             height="60"
                                                             style="object-fit: cover;"
                                                             class="rounded shadow-sm border">


                                                        @if($product->primary_image === $img)

                                                            <span class="badge bg-success position-absolute top-0 start-0">

                                                                Primary

                                                            </span>

                                                        @endif

                                                    </div>

                                                @endif

                                            @endforeach


                                            @if(count($images) > 3)

                                                <span class="badge bg-secondary align-self-center">

                                                    +{{ count($images) - 3 }} more

                                                </span>

                                            @endif

                                        </div>

                                    @else

                                        <span class="text-muted">

                                            No Images

                                        </span>

                                    @endif

                                </td>


                                {{-- DEFAULT SIZE --}}
                                <td>

                                    {{ $product->size }}

                                </td>


                                {{-- DEFAULT COLOR --}}
                                <td>

                                    {{ $product->color }}

                                </td>


                                {{-- CATEGORY --}}
                                <td>

                                    {{ $product->category }}

                                </td>


                                {{-- PRICE --}}
                                <td class="fw-bold text-success">

                                    ₹{{ number_format($product->price, 2) }}

                                </td>


                                {{-- ================================================= --}}
                                {{-- VARIANTS BUTTON --}}
                                {{-- ================================================= --}}

                                <td>

                                    @if($product->variants->count() > 0)

                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#variantsModal{{ $product->id }}">

                                            {{ $product->variants->count() }}
                                            Variants

                                        </button>

                                    @else

                                        <span class="text-muted">

                                            No variants

                                        </span>

                                    @endif

                                </td>


                                {{-- STATUS --}}
                                <td>

                                    @php

                                        $badgeClass = match($product->status) {

                                            'active' =>
                                                'bg-success',

                                            'inactive' =>
                                                'bg-danger',

                                            'draft' =>
                                                'bg-warning text-dark',

                                            default =>
                                                'bg-secondary',

                                        };

                                    @endphp


                                    <span class="badge {{ $badgeClass }}">

                                        {{ ucfirst($product->status) }}

                                    </span>

                                </td>


                                {{-- ACTIONS --}}
                                <td class="text-center">

                                    <a href="{{ route('products.edit', $product) }}"
                                       class="btn btn-warning btn-sm me-1">

                                        ✏ Edit

                                    </a>


                                    <form action="{{ route('products.destroy', $product) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Delete this product?')">

                                            🗑 Delete

                                        </button>

                                    </form>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td colspan="10"
                                    class="text-center py-4 text-muted">

                                    No products found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- PAGINATION --}}
    {{-- ========================================================= --}}

    @if($products->hasPages())

        <div class="mt-3">

            {{ $products->appends(request()->query())->links() }}

        </div>

    @endif


</div>


{{-- ============================================================= --}}
{{-- VARIANT MODALS --}}
{{-- IMPORTANT: THESE ARE OUTSIDE THE TABLE --}}
{{-- ============================================================= --}}

@foreach($products as $product)

    @if($product->variants->count() > 0)

        <div class="modal fade"
             id="variantsModal{{ $product->id }}"
             tabindex="-1"
             aria-labelledby="variantsModalLabel{{ $product->id }}"
             aria-hidden="true">

            <div class="modal-dialog modal-lg modal-dialog-centered">

                <div class="modal-content">


                    {{-- ================================================= --}}
                    {{-- MODAL HEADER --}}
                    {{-- ================================================= --}}

                    <div class="modal-header">

                        <h5 class="modal-title"
                            id="variantsModalLabel{{ $product->id }}">

                            📦 {{ $product->name }}
                            - Product Variants

                        </h5>


                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close">

                        </button>

                    </div>


                    {{-- ================================================= --}}
                    {{-- MODAL BODY --}}
                    {{-- ================================================= --}}

                    <div class="modal-body">

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover align-middle">

                                <thead class="table-dark">

                                    <tr>

                                        <th>
                                            #
                                        </th>

                                        <th>
                                            Size
                                        </th>

                                        <th>
                                            Color
                                        </th>

                                        <th>
                                            Price
                                        </th>

                                        <th>
                                            Stock
                                        </th>

                                        <th>
                                            Availability
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($product->variants as $variant)

                                        <tr>

                                            {{-- NUMBER --}}
                                            <td>

                                                {{ $loop->iteration }}

                                            </td>


                                            {{-- SIZE --}}
                                            <td>

                                                <strong>
                                                    {{ $variant->size }}
                                                </strong>

                                            </td>


                                            {{-- COLOR --}}
                                            <td>

                                                {{ $variant->color }}

                                            </td>


                                            {{-- PRICE --}}
                                            <td class="fw-bold text-success">

                                                ₹{{ number_format($variant->price, 2) }}

                                            </td>


                                            {{-- STOCK --}}
                                            <td>

                                                {{ $variant->stock }}

                                            </td>


                                            {{-- AVAILABILITY --}}
                                            <td>

                                                @if($variant->stock > 0)

                                                    <span class="badge bg-success">

                                                        In Stock

                                                    </span>

                                                @else

                                                    <span class="badge bg-danger">

                                                        Out of Stock

                                                    </span>

                                                @endif

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>


                        {{-- ================================================= --}}
                        {{-- TOTAL STOCK --}}
                        {{-- ================================================= --}}

                        <div class="mt-3">

                            <strong>
                                Total Stock:
                            </strong>

                            <span class="badge bg-primary">

                                {{ $product->variants->sum('stock') }}

                            </span>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- MODAL FOOTER --}}
                    {{-- ================================================= --}}

                    <div class="modal-footer">

                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">

                            Close

                        </button>

                    </div>

                </div>

            </div>

        </div>

    @endif

@endforeach

@endsection