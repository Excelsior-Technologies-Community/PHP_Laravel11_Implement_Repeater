@extends('layouts.admin')

@section('content')

<style>
    :root {
        --primary: #4f46e5;
        --primary-dark: #4338ca;
        --success: #16a34a;
        --warning: #d97706;
        --danger: #dc2626;
        --dark: #111827;
        --muted: #6b7280;
        --border: #e5e7eb;
        --bg: #f8fafc;
    }

    body {
        background: var(--bg);
    }

    .products-page {
        min-height: 100vh;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .page-header {
        background: linear-gradient(135deg,
                #111827 0%,
                #1f2937 55%,
                #312e81 100%);
        border-radius: 20px;
        padding: 28px;
        color: white;
        box-shadow: 0 12px 35px rgba(15, 23, 42, 0.12);
    }

    .page-header h2 {
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .page-header p {
        color: #cbd5e1 !important;
    }

    .header-icon {
        width: 52px;
        height: 52px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, .12);
        font-size: 25px;
        flex-shrink: 0;
    }

    .header-actions .btn {
        border-radius: 10px;
        font-weight: 600;
        padding: 10px 17px;
    }

    /* =========================================================
       STAT CARDS
    ========================================================= */

    .stat-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 20px;
        height: 100%;
        transition: .25s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(15, 23, 42, .08);
    }

    .stat-card::after {
        content: "";
        position: absolute;
        right: -25px;
        bottom: -35px;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(79, 70, 229, .06);
    }

    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        margin-bottom: 14px;
    }

    .stat-icon.primary {
        background: #eef2ff;
        color: #4f46e5;
    }

    .stat-icon.success {
        background: #ecfdf5;
        color: #16a34a;
    }

    .stat-icon.warning {
        background: #fffbeb;
        color: #d97706;
    }

    .stat-icon.danger {
        background: #fef2f2;
        color: #dc2626;
    }

    .stat-label {
        font-size: 13px;
        color: var(--muted);
        font-weight: 600;
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--dark);
    }

    /* =========================================================
       COMMON CARD
    ========================================================= */

    .modern-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 18px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .04);
        overflow: hidden;
    }

    .modern-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid var(--border);
        background: #fff;
    }

    .modern-card-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--dark);
    }

    .modern-card-subtitle {
        font-size: 13px;
        color: var(--muted);
    }

    /* =========================================================
       FILTERS
    ========================================================= */

    .filter-label {
        font-size: 12px;
        font-weight: 700;
        color: #374151;
        margin-bottom: 7px;
    }

    .modern-input,
    .modern-select {
        min-height: 43px;
        border: 1px solid #dbe1e8;
        border-radius: 10px;
        font-size: 14px;
        transition: .2s;
    }

    .modern-input:focus,
    .modern-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, .10);
    }

    .search-wrapper {
        position: relative;
    }

    .search-wrapper .search-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        z-index: 2;
    }

    .search-wrapper input {
        padding-left: 40px;
    }

    .btn-modern-primary {
        background: linear-gradient(135deg,
                var(--primary),
                var(--primary-dark));
        border: none;
        color: white;
        border-radius: 10px;
        font-weight: 700;
        min-height: 43px;
        padding: 0 18px;
    }

    .btn-modern-primary:hover {
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(79, 70, 229, .22);
    }

    .btn-reset {
        border: 1px solid #d1d5db;
        background: white;
        border-radius: 10px;
        font-weight: 600;
        min-height: 43px;
    }

    /* =========================================================
       BULK BAR
    ========================================================= */

    .bulk-toolbar {
        background: #f8fafc;
        border: 1px solid var(--border);
        border-radius: 13px;
        padding: 10px 12px;
    }

    .bulk-toolbar .form-select {
        border-radius: 9px;
        min-width: 160px;
        font-size: 13px;
    }

    .bulk-btn {
        border-radius: 9px;
        font-weight: 700;
    }

    /* =========================================================
       TABLE
    ========================================================= */

    .products-table {
        margin-bottom: 0;
    }

    .products-table thead th {
        background: #111827;
        color: #e5e7eb;
        border: none;
        padding: 14px 12px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .5px;
        white-space: nowrap;
    }

    .products-table tbody td {
        padding: 15px 12px;
        border-bottom: 1px solid #f0f2f5;
        vertical-align: middle;
        font-size: 13px;
        color: #374151;
    }

    .products-table tbody tr {
        transition: .2s ease;
    }

    .products-table tbody tr:hover {
        background: #fafbff;
    }

    .product-name {
        font-weight: 750;
        color: #111827;
    }

    .product-id {
        font-size: 11px;
        color: #9ca3af;
    }

    .product-details {
        max-width: 220px;
        line-height: 1.5;
    }

    /* =========================================================
       IMAGE
    ========================================================= */

    .product-image {
        width: 52px;
        height: 52px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
    }

    .image-stack {
        display: flex;
        align-items: center;
    }

    .image-stack .product-image {
        margin-right: -10px;
        position: relative;
    }

    .image-stack .product-image:last-child {
        margin-right: 0;
    }

    .more-images {
        width: 52px;
        height: 52px;
        border-radius: 10px;
        background: #111827;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        margin-left: 3px;
    }

    /* =========================================================
       BADGES
    ========================================================= */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 30px;
        padding: 6px 10px;
        font-size: 11px;
        font-weight: 800;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .status-active {
        color: #15803d;
        background: #dcfce7;
    }

    .status-inactive {
        color: #b91c1c;
        background: #fee2e2;
    }

    .status-draft {
        color: #a16207;
        background: #fef3c7;
    }

    .stock-badge {
        display: inline-block;
        margin-top: 5px;
        border-radius: 6px;
        padding: 4px 7px;
        font-size: 10px;
        font-weight: 700;
    }

    .stock-success {
        color: #166534;
        background: #dcfce7;
    }

    .stock-warning {
        color: #92400e;
        background: #fef3c7;
    }

    .stock-danger {
        color: #991b1b;
        background: #fee2e2;
    }

    /* =========================================================
       ACTION BUTTONS
    ========================================================= */

    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        font-size: 14px;
    }

    .action-edit {
        background: #fef3c7;
        color: #92400e;
    }

    .action-delete {
        background: #fee2e2;
        color: #b91c1c;
    }

    .action-btn:hover {
        transform: translateY(-1px);
    }

    /* =========================================================
       VARIANT BUTTON
    ========================================================= */

    .variant-btn {
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
    }

    /* =========================================================
       PAGINATION
    ========================================================= */

    .pagination-wrapper {
        padding: 20px;
        border-top: 1px solid var(--border);
        background: #fff;
    }

    .modern-pagination {
        display: flex;
        gap: 6px;
        justify-content: center;
        flex-wrap: wrap;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .modern-pagination .page-link {
        width: 38px;
        height: 38px;
        border-radius: 9px !important;
        border: 1px solid #e5e7eb;
        color: #374151;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        background: white;
    }

    .modern-pagination .page-item.active .page-link {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-state {
        padding: 70px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 75px;
        height: 75px;
        margin: 0 auto 18px;
        border-radius: 20px;
        background: #eef2ff;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
    }

    /* =========================================================
       MODAL
    ========================================================= */

    .modern-modal .modal-content {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(15, 23, 42, .2);
    }

    .modern-modal .modal-header {
        background: #111827;
        color: white;
        border: none;
        padding: 18px 22px;
    }

    .modern-modal .modal-title {
        font-weight: 800;
    }

    .modern-modal .btn-close {
        filter: invert(1);
    }

    .variant-table thead th {
        background: #f8fafc;
        font-size: 11px;
        text-transform: uppercase;
        color: #6b7280;
        border-bottom: 1px solid #e5e7eb;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 768px) {

        .page-header {
            padding: 20px;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            flex: 1;
        }

        .stat-value {
            font-size: 24px;
        }

        .bulk-toolbar {
            align-items: stretch !important;
        }

        .bulk-toolbar .form-select,
        .bulk-toolbar button {
            width: 100%;
        }
    }
</style>

<div class="container-fluid py-4 products-page">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="page-header mb-4">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-4">

            <div class="d-flex align-items-center gap-3">

                <div class="header-icon">
                    📦
                </div>

                <div>
                    <h2 class="mb-1">
                        Products Management
                    </h2>

                    <p class="mb-0">
                        Manage products, variants, inventory and status.
                    </p>
                </div>

            </div>

            <div class="d-flex gap-2 header-actions">

                <a href="{{ route('products.create') }}"
                    class="btn btn-light">

                    ➕ Add Product

                </a>

                <a href="{{ route('products.export.csv', request()->query()) }}"
                    class="btn btn-success">

                    📥 Export CSV

                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
        ALERTS
    ========================================================== --}}
    @if(session('success'))

    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center gap-2"
        role="alert">

        <span style="font-size: 20px;">
            ✅
        </span>

        <div class="flex-grow-1">
            <strong>Success!</strong>
            {{ session('success') }}
        </div>

        <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

    @endif


    @if($errors->any())

    <div class="alert alert-danger border-0 shadow-sm">

        <strong>Please fix the following:</strong>

        <ul class="mb-0 mt-2">

            @foreach($errors->all() as $error)

            <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

    @endif


    {{-- =========================================================
        STATISTICS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="stat-card">

                <div class="stat-icon primary">
                    📦
                </div>

                <div class="stat-label">
                    Total Products
                </div>

                <div class="stat-value">
                    {{ $totalProducts }}
                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="stat-card">

                <div class="stat-icon success">
                    ✓
                </div>

                <div class="stat-label">
                    Active Products
                </div>

                <div class="stat-value">
                    {{ $activeProducts }}
                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="stat-card">

                <div class="stat-icon warning">
                    ⚠
                </div>

                <div class="stat-label">
                    Low Stock
                </div>

                <div class="stat-value">
                    {{ $lowStockProducts }}
                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="stat-card">

                <div class="stat-icon danger">
                    !
                </div>

                <div class="stat-label">
                    Out of Stock
                </div>

                <div class="stat-value">
                    {{ $outOfStockProducts }}
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        ADVANCED FILTERS
    ========================================================== --}}

    <div class="modern-card mb-4">

        <div class="modern-card-header">

            <div class="d-flex align-items-center gap-2">

                <span style="font-size:20px;">
                    🔎
                </span>

                <div>

                    <div class="modern-card-title">
                        Advanced Product Search
                    </div>

                    <div class="modern-card-subtitle">
                        Filter products by status, category, stock and price.
                    </div>

                </div>

            </div>

        </div>


        <div class="p-4">

            <form method="GET"
                action="{{ route('products.index') }}">

                <div class="row g-3">

                    {{-- SEARCH --}}

                    <div class="col-xl-4 col-md-6">

                        <label class="filter-label">
                            Search Products
                        </label>

                        <div class="search-wrapper">

                            <span class="search-icon">
                                🔍
                            </span>

                            <input type="text"
                                name="search"
                                class="form-control modern-input"
                                value="{{ request('search') }}"
                                placeholder="Name, category, color, size...">

                        </div>

                    </div>


                    {{-- STATUS --}}

                    <div class="col-xl-2 col-md-3">

                        <label class="filter-label">
                            Status
                        </label>

                        <select name="status"
                            class="form-select modern-select">

                            <option value="">
                                All Status
                            </option>

                            @foreach($statuses as $status)

                            <option value="{{ $status }}"
                                {{ request('status') === $status ? 'selected' : '' }}>

                                {{ ucfirst($status) }}

                            </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- CATEGORY --}}

                    <div class="col-xl-2 col-md-3">

                        <label class="filter-label">
                            Category
                        </label>

                        <select name="category"
                            class="form-select modern-select">

                            <option value="">
                                All Categories
                            </option>

                            @foreach($categories as $category)

                            <option value="{{ $category }}"
                                {{ request('category') === $category ? 'selected' : '' }}>

                                {{ $category }}

                            </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- STOCK --}}

                    <div class="col-xl-2 col-md-3">

                        <label class="filter-label">
                            Stock
                        </label>

                        <select name="stock_filter"
                            class="form-select modern-select">

                            <option value="">
                                All Stock
                            </option>

                            <option value="in_stock"
                                {{ request('stock_filter') === 'in_stock' ? 'selected' : '' }}>

                                In Stock

                            </option>

                            <option value="out_of_stock"
                                {{ request('stock_filter') === 'out_of_stock' ? 'selected' : '' }}>

                                Out of Stock

                            </option>

                            <option value="low_stock"
                                {{ request('stock_filter') === 'low_stock' ? 'selected' : '' }}>

                                Low Stock

                            </option>

                        </select>

                    </div>


                    {{-- LOW STOCK --}}

                    <div class="col-xl-2 col-md-3">

                        <label class="filter-label">
                            Low Stock ≤
                        </label>

                        <input type="number"
                            name="low_stock"
                            class="form-control modern-input"
                            min="1"
                            value="{{ request('low_stock', 5) }}">

                    </div>


                    {{-- MIN PRICE --}}

                    <div class="col-xl-2 col-md-3">

                        <label class="filter-label">
                            Minimum Price
                        </label>

                        <input type="number"
                            name="min_price"
                            class="form-control modern-input"
                            min="0"
                            step="0.01"
                            value="{{ request('min_price') }}"
                            placeholder="₹ Min">

                    </div>


                    {{-- MAX PRICE --}}

                    <div class="col-xl-2 col-md-3">

                        <label class="filter-label">
                            Maximum Price
                        </label>

                        <input type="number"
                            name="max_price"
                            class="form-control modern-input"
                            min="0"
                            step="0.01"
                            value="{{ request('max_price') }}"
                            placeholder="₹ Max">

                    </div>


                    {{-- SORT --}}

                    <div class="col-xl-2 col-md-3">

                        <label class="filter-label">
                            Sort By
                        </label>

                        <select name="sort"
                            class="form-select modern-select">

                            <option value="created_at"
                                {{ request('sort', 'created_at') === 'created_at' ? 'selected' : '' }}>

                                Date

                            </option>

                            <option value="name"
                                {{ request('sort') === 'name' ? 'selected' : '' }}>

                                Product Name

                            </option>

                            <option value="price"
                                {{ request('sort') === 'price' ? 'selected' : '' }}>

                                Price

                            </option>

                        </select>

                    </div>


                    {{-- DIRECTION --}}

                    <div class="col-xl-2 col-md-3">

                        <label class="filter-label">
                            Direction
                        </label>

                        <select name="direction"
                            class="form-select modern-select">

                            <option value="desc"
                                {{ request('direction', 'desc') === 'desc' ? 'selected' : '' }}>

                                Descending

                            </option>

                            <option value="asc"
                                {{ request('direction') === 'asc' ? 'selected' : '' }}>

                                Ascending

                            </option>

                        </select>

                    </div>


                    {{-- BUTTONS --}}

                    <div class="col-xl-3 col-md-6 d-flex align-items-end gap-2">

                        <button type="submit"
                            class="btn btn-modern-primary">

                            🔎 Apply Filters

                        </button>

                        <a href="{{ route('products.index') }}"
                            class="btn btn-reset">

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        PRODUCT LIST
    ========================================================== --}}

    <form method="POST"
        action="{{ route('products.bulk-status') }}"
        id="bulkStatusForm">

        @csrf

        <div class="modern-card">

            {{-- TABLE HEADER --}}

            <div class="modern-card-header">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <div>

                        <div class="modern-card-title">
                            Product List
                        </div>

                        <div class="modern-card-subtitle">
                            Select products and update their status in bulk.
                        </div>

                    </div>


                    <div class="bulk-toolbar d-flex align-items-center gap-2">

                        <span class="small text-muted fw-semibold">
                            Bulk Action:
                        </span>

                        <select name="bulk_status"
                            class="form-select form-select-sm"
                            required>

                            <option value="">
                                Change Status
                            </option>

                            <option value="active">
                                Active
                            </option>

                            <option value="inactive">
                                Inactive
                            </option>

                            <option value="draft">
                                Draft
                            </option>

                        </select>

                        <button type="submit"
                            class="btn btn-dark btn-sm bulk-btn"
                            onclick="return confirmBulkUpdate()">

                            ⚡ Update

                        </button>

                    </div>

                </div>

            </div>


            {{-- TABLE --}}

            <div class="table-responsive">

                <table class="table products-table align-middle">

                    <thead>

                        <tr>

                            <th class="text-center">
                                <input type="checkbox"
                                    id="selectAll"
                                    class="form-check-input">
                            </th>

                            <th>
                                Product
                            </th>

                            <th>
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
                                Price
                            </th>

                            <th>
                                Variants / Stock
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

                        $totalStock = $product->variants->sum('stock');

                        @endphp


                        <tr>

                            {{-- CHECKBOX --}}

                            <td class="text-center">

                                <input type="checkbox"
                                    name="product_ids[]"
                                    value="{{ $product->id }}"
                                    class="product-checkbox form-check-input">

                            </td>


                            {{-- PRODUCT --}}

                            <td>

                                <div class="product-name">
                                    {{ $product->name }}
                                </div>

                                <div class="product-id">
                                    #{{ $product->id }}
                                </div>

                            </td>


                            {{-- DETAILS --}}

                            <td>

                                <div class="product-details">

                                    {{ Str::limit(
                                            $product->details,
                                            60
                                        ) }}

                                </div>

                            </td>


                            {{-- IMAGES --}}

                            <td>

                                @if(!empty($images))

                                <div class="image-stack">

                                    @foreach(array_slice($images, 0, 3) as $img)

                                    <img src="{{ asset($img) }}"
                                        class="product-image"
                                        alt="{{ $product->name }}">

                                    @endforeach


                                    @if(count($images) > 3)

                                    <div class="more-images">

                                        +{{ count($images) - 3 }}

                                    </div>

                                    @endif

                                </div>

                                @else

                                <span class="text-muted small">
                                    No images
                                </span>

                                @endif

                            </td>


                            {{-- SIZE --}}

                            <td>

                                <span class="badge text-bg-light border">
                                    {{ $product->size ?: '—' }}
                                </span>

                            </td>


                            {{-- COLOR --}}

                            <td>

                                {{ $product->color ?: '—' }}

                            </td>


                            {{-- CATEGORY --}}

                            <td>

                                <span class="fw-semibold">
                                    {{ $product->category ?: '—' }}
                                </span>

                            </td>


                            {{-- PRICE --}}

                            <td>

                                <div class="fw-bold text-success">

                                    ₹{{ number_format(
                                            $product->price,
                                            2
                                        ) }}

                                </div>

                            </td>


                            {{-- VARIANTS / STOCK --}}

                            <td>

                                @if($product->variants->count())

                                <button type="button"
                                    class="btn btn-outline-primary btn-sm variant-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#variantsModal{{ $product->id }}">

                                    {{ $product->variants->count() }}
                                    Variants

                                </button>


                                @if($totalStock <= 0)

                                    <div>
                                    <span class="stock-badge stock-danger">
                                        Out of Stock
                                    </span>
            </div>

            @elseif($totalStock <= 5)

                <div>
                <span class="stock-badge stock-warning">
                    Low: {{ $totalStock }}
                </span>
        </div>

        @else

        <div>
            <span class="stock-badge stock-success">
                Stock: {{ $totalStock }}
            </span>
        </div>

        @endif

        @else

        <span class="text-muted small">
            No variants
        </span>

        @endif

        </td>


        {{-- STATUS --}}

        <td>

            @if($product->status === 'active')

            <span class="status-badge status-active">
                <span class="status-dot"></span>
                Active
            </span>

            @elseif($product->status === 'inactive')

            <span class="status-badge status-inactive">
                <span class="status-dot"></span>
                Inactive
            </span>

            @elseif($product->status === 'draft')

            <span class="status-badge status-draft">
                <span class="status-dot"></span>
                Draft
            </span>

            @else

            <span class="status-badge bg-secondary text-white">
                {{ ucfirst($product->status) }}
            </span>

            @endif

        </td>


        {{-- ACTIONS --}}

        <td class="text-center">

            <div class="d-flex justify-content-center gap-2">

                <a href="{{ route(
                                            'products.edit',
                                            $product
                                        ) }}"
                    class="action-btn action-edit"
                    title="Edit Product">

                    ✏️

                </a>


                <form action="{{ route(
                                            'products.destroy',
                                            $product
                                        ) }}"
                    method="POST"
                    class="d-inline">

                    @csrf

                    @method('DELETE')

                    <button type="submit"
                        class="action-btn action-delete"
                        title="Delete Product"
                        onclick="return confirm('Move this product to trash?')">

                        🗑️

                    </button>

                </form>

            </div>

        </td>

        </tr>


        @empty

        <tr>

            <td colspan="11">

                <div class="empty-state">

                    <div class="empty-icon">
                        📦
                    </div>

                    <h5 class="fw-bold mb-2">
                        No Products Found
                    </h5>

                    <p class="text-muted mb-3">
                        Try changing your search or filters.
                    </p>

                    <a href="{{ route('products.create') }}"
                        class="btn btn-modern-primary">

                        ➕ Add First Product

                    </a>

                </div>

            </td>

        </tr>

        @endforelse

        </tbody>

        </table>

</div>


{{-- =================================================
                PAGINATION
            ================================================== --}}

@if($products->hasPages())

<div class="pagination-wrapper">

    <ul class="modern-pagination">

        @for(
        $page = 1;
        $page <= $products->lastPage();
            $page++
            )

            <li class="page-item
                                {{ $page == $products->currentPage()
                                    ? 'active'
                                    : '' }}">

                <a class="page-link"
                    href="{{ $products->url($page) }}">

                    {{ $page }}

                </a>

            </li>

            @endfor

    </ul>

</div>

@endif

</div>

</form>


{{-- =========================================================
        VARIANT MODALS
    ========================================================== --}}

@foreach($products as $product)

@if($product->variants->count())

<div class="modal fade modern-modal"
    id="variantsModal{{ $product->id }}"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    📦 {{ $product->name }}
                    <span class="fw-normal opacity-75">
                        — Product Variants
                    </span>

                </h5>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <div class="modal-body p-4">

                <div class="table-responsive">

                    <table class="table variant-table align-middle">

                        <thead>

                            <tr>

                                <th>#</th>
                                <th>Size</th>
                                <th>Color</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Availability</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($product->variants as $variant)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td class="fw-bold">
                                    {{ $variant->size }}
                                </td>

                                <td>
                                    {{ $variant->color }}
                                </td>

                                <td class="text-success fw-bold">

                                    ₹{{ number_format(
                                                        $variant->price,
                                                        2
                                                    ) }}

                                </td>

                                <td>
                                    {{ $variant->stock }}
                                </td>

                                <td>

                                    @if($variant->stock > 0)

                                    <span class="status-badge status-active">

                                        <span class="status-dot"></span>
                                        In Stock

                                    </span>

                                    @else

                                    <span class="status-badge status-inactive">

                                        <span class="status-dot"></span>
                                        Out of Stock

                                    </span>

                                    @endif

                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                <div class="d-flex justify-content-between align-items-center mt-3 p-3 rounded-3 bg-light">

                    <span class="fw-semibold">
                        Total Stock
                    </span>

                    <span class="badge bg-primary rounded-pill px-3 py-2">

                        {{ $product->variants->sum('stock') }}

                    </span>

                </div>

            </div>


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

</div>


@endsection


@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {

        /* =====================================================
           SELECT ALL
        ===================================================== */

        const selectAll = document.getElementById('selectAll');

        const checkboxes = document.querySelectorAll(
            '.product-checkbox'
        );

        if (selectAll) {

            selectAll.addEventListener('change', function() {

                checkboxes.forEach(function(checkbox) {

                    checkbox.checked = selectAll.checked;

                });

            });

        }


        /* =====================================================
           INDIVIDUAL CHECKBOX STATE
        ===================================================== */

        checkboxes.forEach(function(checkbox) {

            checkbox.addEventListener('change', function() {

                const total = document.querySelectorAll(
                    '.product-checkbox'
                ).length;

                const checked = document.querySelectorAll(
                    '.product-checkbox:checked'
                ).length;

                if (selectAll) {

                    selectAll.checked =
                        total > 0 && total === checked;

                    selectAll.indeterminate =
                        checked > 0 && checked < total;

                }

            });

        });

    });


    /* =========================================================
       BULK UPDATE CONFIRMATION
    ========================================================= */

    function confirmBulkUpdate() {

        const selected =
            document.querySelectorAll(
                '.product-checkbox:checked'
            );

        const status =
            document.querySelector(
                '[name="bulk_status"]'
            ).value;


        if (selected.length === 0) {

            alert(
                'Please select at least one product.'
            );

            return false;

        }


        if (!status) {

            alert(
                'Please select a status.'
            );

            return false;

        }


        return confirm(
            'Update status of ' +
            selected.length +
            ' selected product(s) to "' +
            status.toUpperCase() +
            '"?'
        );

    }
</script>

@endpush