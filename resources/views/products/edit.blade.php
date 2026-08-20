@extends('layouts.admin')

@section('content')

<div class="container">

    <h1 class="mb-4">Edit Product</h1>

    <form action="{{ route('products.update', $product) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')


        {{-- NAME --}}
        <div class="mb-3">

            <label class="form-label fw-bold">
                Name
            </label>

            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ old('name', $product->name) }}"
                   required>

        </div>


        {{-- DETAILS --}}
        <div class="mb-3">

            <label class="form-label fw-bold">
                Details
            </label>

            <textarea name="details"
                      class="form-control"
                      rows="4"
                      required>{{ old('details', $product->details) }}</textarea>

        </div>


        {{-- EXISTING IMAGES --}}
        <div class="mb-4">

            <label class="form-label fw-bold">
                Existing Images
            </label>

            @if(!empty($product->images))

                <div class="row">

                    @foreach($product->images as $img)

                        <div class="col-md-3 mb-3">

                            <div class="card">

                                <div class="card-body text-center">

                                    <img src="{{ asset($img) }}"
                                         width="120"
                                         height="100"
                                         class="rounded border mb-2"
                                         style="object-fit:cover;">

                                    <div>

                                        <div class="form-check">

                                            <input type="radio"
                                                   name="primary_image"
                                                   value="{{ $img }}"
                                                   class="form-check-input"
                                                   {{ $product->primary_image === $img ? 'checked' : '' }}>

                                            <label class="form-check-label">
                                                Primary Image
                                            </label>

                                        </div>

                                    </div>

                                    <div class="mt-2">

                                        <input type="checkbox"
                                               name="delete_images[]"
                                               value="{{ $img }}">

                                        <label class="text-danger small">
                                            Delete Image
                                        </label>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="alert alert-warning">
                    No existing images.
                </div>

            @endif

        </div>


        {{-- ADD NEW IMAGES --}}
        <div class="mb-4">

            <label class="form-label fw-bold">
                Add New Images
            </label>

            <div id="imageRepeater">

                <div class="row mb-2 repeater-item">

                    <div class="col-md-5">

                        <input type="file"
                               name="images[0]"
                               class="form-control image-input"
                               accept="image/*">

                    </div>

                    <div class="col-md-3">

                        <img src=""
                             width="80"
                             class="img-preview rounded border"
                             style="display:none;">

                    </div>

                    <div class="col-md-2">

                        <div class="form-check mt-2">

                            <input type="radio"
                                   name="primary_image"
                                   value="0"
                                   class="form-check-input">

                            <label class="form-check-label">
                                Primary
                            </label>

                        </div>

                    </div>

                    <div class="col-md-2">

                        <button type="button"
                                class="btn btn-danger removeRow">

                            Remove

                        </button>

                    </div>

                </div>

            </div>

            <button type="button"
                    id="addImage"
                    class="btn btn-secondary">

                + Add More Images

            </button>

        </div>


        {{-- DEFAULT SIZE --}}
        <div class="mb-3">

            <label class="form-label fw-bold">
                Default Size
            </label>

            <input type="text"
                   name="size"
                   class="form-control"
                   value="{{ old('size', $product->size) }}"
                   required>

        </div>


        {{-- DEFAULT COLOR --}}
        <div class="mb-3">

            <label class="form-label fw-bold">
                Default Color
            </label>

            <input type="text"
                   name="color"
                   class="form-control"
                   value="{{ old('color', $product->color) }}"
                   required>

        </div>


        {{-- CATEGORY --}}
        <div class="mb-3">

            <label class="form-label fw-bold">
                Category
            </label>

            <input type="text"
                   name="category"
                   class="form-control"
                   value="{{ old('category', $product->category) }}"
                   required>

        </div>


        {{-- PRICE --}}
        <div class="mb-3">

            <label class="form-label fw-bold">
                Default Price
            </label>

            <input type="number"
                   name="price"
                   class="form-control"
                   value="{{ old('price', $product->price) }}"
                   min="0"
                   step="0.01"
                   required>

        </div>


        {{-- STATUS --}}
        <div class="mb-3">

            <label class="form-label fw-bold">
                Status
            </label>

            <select name="status"
                    class="form-select"
                    required>

                @foreach($statuses as $status)

                    <option value="{{ $status }}"
                        {{ old('status', $product->status) === $status ? 'selected' : '' }}>

                        {{ ucfirst($status) }}

                    </option>

                @endforeach

            </select>

        </div>


        {{-- TAGS --}}
        <div class="mb-4">

            <label class="form-label fw-bold">
                Tags
            </label>

            <select name="tags[]"
                    class="form-select select2"
                    multiple>

                @foreach($tags as $tag)

                    <option value="{{ $tag->id }}"
                        {{ in_array($tag->id, old('tags', $product->tag_ids ?? [])) ? 'selected' : '' }}>

                        {{ $tag->tag_name }}

                    </option>

                @endforeach

            </select>

        </div>


        {{-- VARIANTS --}}
        <div class="mb-4">

            <div class="d-flex justify-content-between align-items-center mb-2">

                <label class="form-label fw-bold mb-0">
                    Product Variants
                </label>

                <button type="button"
                        id="addVariant"
                        class="btn btn-success btn-sm">

                    + Add Variant

                </button>

            </div>

            <div id="variantRepeater">

                @forelse($product->variants as $index => $variant)

                    <div class="card mb-2 variant-item">

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Size
                                    </label>

                                    <input type="text"
                                           name="variants[{{ $index }}][size]"
                                           value="{{ old("variants.$index.size", $variant->size) }}"
                                           class="form-control"
                                           required>

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Color
                                    </label>

                                    <input type="text"
                                           name="variants[{{ $index }}][color]"
                                           value="{{ old("variants.$index.color", $variant->color) }}"
                                           class="form-control"
                                           required>

                                </div>

                                <div class="col-md-2">

                                    <label class="form-label">
                                        Price
                                    </label>

                                    <input type="number"
                                           name="variants[{{ $index }}][price]"
                                           value="{{ old("variants.$index.price", $variant->price) }}"
                                           class="form-control"
                                           min="0"
                                           step="0.01"
                                           required>

                                </div>

                                <div class="col-md-2">

                                    <label class="form-label">
                                        Stock
                                    </label>

                                    <input type="number"
                                           name="variants[{{ $index }}][stock]"
                                           value="{{ old("variants.$index.stock", $variant->stock) }}"
                                           class="form-control"
                                           min="0"
                                           required>

                                </div>

                                <div class="col-md-2 d-flex align-items-end">

                                    <button type="button"
                                            class="btn btn-danger removeVariant w-100">

                                        Remove

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="card mb-2 variant-item">

                        <div class="card-body">

                            <div class="row">

                                <div class="col-md-3">
                                    <input type="text"
                                           name="variants[0][size]"
                                           class="form-control"
                                           placeholder="Size"
                                           required>
                                </div>

                                <div class="col-md-3">
                                    <input type="text"
                                           name="variants[0][color]"
                                           class="form-control"
                                           placeholder="Color"
                                           required>
                                </div>

                                <div class="col-md-2">
                                    <input type="number"
                                           name="variants[0][price]"
                                           class="form-control"
                                           placeholder="Price"
                                           min="0"
                                           step="0.01"
                                           required>
                                </div>

                                <div class="col-md-2">
                                    <input type="number"
                                           name="variants[0][stock]"
                                           class="form-control"
                                           placeholder="Stock"
                                           min="0"
                                           required>
                                </div>

                                <div class="col-md-2">
                                    <button type="button"
                                            class="btn btn-danger removeVariant w-100">
                                        Remove
                                    </button>
                                </div>

                            </div>

                        </div>

                    </div>

                @endforelse

            </div>

        </div>


        <button type="submit"
                class="btn btn-primary">

            Update Product

        </button>

        <a href="{{ route('products.index') }}"
           class="btn btn-secondary">

            Back

        </a>

    </form>

</div>

@endsection


@push('scripts')

<script>

let imageIndex = 1;

document.getElementById('addImage').addEventListener('click', function () {

    const html = `
        <div class="row mb-2 repeater-item">

            <div class="col-md-5">

                <input type="file"
                       name="images[${imageIndex}]"
                       class="form-control image-input"
                       accept="image/*">

            </div>

            <div class="col-md-3">

                <img src=""
                     width="80"
                     class="img-preview rounded border"
                     style="display:none;">

            </div>

            <div class="col-md-2">

                <div class="form-check mt-2">

                    <input type="radio"
                           name="primary_image"
                           value="${imageIndex}"
                           class="form-check-input">

                    <label class="form-check-label">
                        Primary
                    </label>

                </div>

            </div>

            <div class="col-md-2">

                <button type="button"
                        class="btn btn-danger removeRow">

                    Remove

                </button>

            </div>

        </div>
    `;

    document
        .getElementById('imageRepeater')
        .insertAdjacentHTML('beforeend', html);

    imageIndex++;
});


document.addEventListener('click', function (event) {

    if (event.target.classList.contains('removeRow')) {

        event.target
            .closest('.repeater-item')
            .remove();

    }

});


document.addEventListener('change', function (event) {

    if (event.target.classList.contains('image-input')) {

        const file = event.target.files[0];

        if (!file) {
            return;
        }

        const preview = event.target
            .closest('.repeater-item')
            .querySelector('.img-preview');

        const reader = new FileReader();

        reader.onload = function (event) {

            preview.src = event.target.result;

            preview.style.display = 'block';

        };

        reader.readAsDataURL(file);
    }

});


let variantIndex =
    {{ $product->variants->count() > 0
        ? $product->variants->count()
        : 1 }};


document.getElementById('addVariant').addEventListener('click', function () {

    const html = `
        <div class="card mb-2 variant-item">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">

                        <label class="form-label">
                            Size
                        </label>

                        <input type="text"
                               name="variants[${variantIndex}][size]"
                               class="form-control"
                               required>

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">
                            Color
                        </label>

                        <input type="text"
                               name="variants[${variantIndex}][color]"
                               class="form-control"
                               required>

                    </div>

                    <div class="col-md-2">

                        <label class="form-label">
                            Price
                        </label>

                        <input type="number"
                               name="variants[${variantIndex}][price]"
                               class="form-control"
                               min="0"
                               step="0.01"
                               required>

                    </div>

                    <div class="col-md-2">

                        <label class="form-label">
                            Stock
                        </label>

                        <input type="number"
                               name="variants[${variantIndex}][stock]"
                               class="form-control"
                               min="0"
                               required>

                    </div>

                    <div class="col-md-2 d-flex align-items-end">

                        <button type="button"
                                class="btn btn-danger removeVariant w-100">

                            Remove

                        </button>

                    </div>

                </div>

            </div>

        </div>
    `;

    document
        .getElementById('variantRepeater')
        .insertAdjacentHTML('beforeend', html);

    variantIndex++;
});


document.addEventListener('click', function (event) {

    if (event.target.classList.contains('removeVariant')) {

        const variants =
            document.querySelectorAll('.variant-item');

        if (variants.length <= 1) {

            alert('At least one product variant is required.');

            return;
        }

        event.target
            .closest('.variant-item')
            .remove();

    }

});

</script>

@endpush