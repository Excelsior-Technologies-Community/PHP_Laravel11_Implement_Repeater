@extends('layouts.admin')

@section('content')

<div class="container">

    <h1 class="mb-4">Create Product</h1>

    <form action="{{ route('products.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        {{-- PRODUCT NAME --}}
        <div class="mb-3">
            <label class="form-label fw-bold">
                Name
            </label>

            <input type="text"
                   name="name"
                   class="form-control"
                   value="{{ old('name') }}"
                   required>

            @error('name')
                <div class="text-danger small">
                    {{ $message }}
                </div>
            @enderror
        </div>


        {{-- DETAILS --}}
        <div class="mb-3">
            <label class="form-label fw-bold">
                Details
            </label>

            <textarea name="details"
                      class="form-control"
                      rows="4"
                      required>{{ old('details') }}</textarea>

            @error('details')
                <div class="text-danger small">
                    {{ $message }}
                </div>
            @enderror
        </div>


        {{-- PRODUCT IMAGES --}}
        <div class="mb-4">

            <label class="form-label fw-bold">
                Product Images
            </label>

            <div class="alert alert-info">
                Select one image as the <strong>Primary Image</strong>.
                If you do not select one, the first uploaded image will
                automatically become the primary image.
            </div>

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
                                   class="form-check-input primary-image-radio">

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
                    class="btn btn-secondary mt-2">

                + Add More Images

            </button>

            @error('images.*')
                <div class="text-danger small">
                    {{ $message }}
                </div>
            @enderror

        </div>


        {{-- SIZE --}}
        <div class="mb-3">

            <label class="form-label fw-bold">
                Default Size
            </label>

            <input type="text"
                   name="size"
                   class="form-control"
                   value="{{ old('size') }}"
                   required>

        </div>


        {{-- COLOR --}}
        <div class="mb-3">

            <label class="form-label fw-bold">
                Default Color
            </label>

            <input type="text"
                   name="color"
                   class="form-control"
                   value="{{ old('color') }}"
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
                   value="{{ old('category') }}"
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
                   value="{{ old('price') }}"
                   step="0.01"
                   min="0"
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

                <option value="">
                    Select Status
                </option>

                @foreach($statuses as $status)

                    <option value="{{ $status }}"
                        {{ old('status') === $status ? 'selected' : '' }}>

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
                        {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>

                        {{ $tag->tag_name }}

                    </option>

                @endforeach

            </select>

        </div>


        {{-- PRODUCT VARIANTS --}}
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

            <div class="alert alert-secondary">

                Add multiple product variants using the repeater.
                Each variant can have its own size, color, price and stock.

            </div>

            <div id="variantRepeater">

                <div class="card mb-2 variant-item">

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-3">

                                <label class="form-label">
                                    Size
                                </label>

                                <input type="text"
                                       name="variants[0][size]"
                                       class="form-control"
                                       required>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label">
                                    Color
                                </label>

                                <input type="text"
                                       name="variants[0][color]"
                                       class="form-control"
                                       required>

                            </div>

                            <div class="col-md-2">

                                <label class="form-label">
                                    Price
                                </label>

                                <input type="number"
                                       name="variants[0][price]"
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
                                       name="variants[0][stock]"
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

            </div>

        </div>


        <button type="submit"
                class="btn btn-primary">

            Create Product

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
                           class="form-check-input primary-image-radio">

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


let variantIndex = 1;

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

        const variants = document.querySelectorAll('.variant-item');

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