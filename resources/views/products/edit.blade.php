@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Edit Product</h1>

    <!-- Update form with PUT method spoofing for multiple images -->
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf          <!-- CSRF protection token -->
        @method('PUT') <!-- Laravel method spoofing for PUT request -->

        <!-- PRODUCT NAME - pre-filled with current value -->
        <div class="mb-3">
            <label class="form-label fw-bold">Name</label>
            <input type="text" name="name" class="form-control"
                   value="{{ $product->name }}" required>
        </div>

        <!-- PRODUCT DETAILS TEXTAREA - pre-filled -->
        <div class="mb-3">
            <label class="form-label fw-bold">Details</label>
            <textarea name="details" class="form-control" required>{{ $product->details }}</textarea>
        </div>

        <!-- EXISTING IMAGES WITH DELETE CHECKBOXES -->
        <div class="mb-3">
            <label class="form-label fw-bold">Existing Images</label>
            <div class="d-flex flex-wrap">
                <!-- Loop through current product images -->
                @foreach($product->images as $img)
                    <div class="m-2 text-center">
                        <!-- Show current image thumbnail -->
                        <img src="{{ asset($img) }}" width="80" class="rounded border mb-1"><br>
                        <!-- Checkbox to mark for deletion (name="delete_images[]" creates array) -->
                        <input type="checkbox" name="delete_images[]" value="{{ $img }}">
                        <label class="text-danger small">Delete</label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- ADD NEW IMAGES - DYNAMIC REPEATER -->
        <div class="mb-3">
            <label class="form-label fw-bold">Add New Images</label>

            <!-- Dynamic image input repeater -->
            <div id="imageRepeater">
                <!-- Initial empty row for new images -->
                <div class="row mb-2 repeater-item">
                    <div class="col-md-5">
                        <!-- File input with array name for multiple uploads -->
                        <input type="file" name="images[]" class="form-control image-input" accept="image/*">
                    </div>

                    <div class="col-md-5">
                        <!-- Live image preview (hidden initially) -->
                        <img src="" width="80" class="img-preview rounded border" style="display:none;">
                    </div>

                    <div class="col-md-2">
                        <!-- Remove button for this row -->
                        <button type="button" class="btn btn-danger removeRow">Remove</button>
                    </div>
                </div>
            </div>

            <!-- Add more image rows button -->
            <button type="button" id="addImage" class="btn btn-secondary mt-2">+ Add More Images</button>
        </div>

        <!-- PRODUCT ATTRIBUTES - all pre-filled -->
        <div class="mb-3">
            <label class="form-label fw-bold">Size</label>
            <input type="text" name="size" class="form-control"
                   value="{{ $product->size }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Color</label>
            <input type="text" name="color" class="form-control"
                   value="{{ $product->color }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Category</label>
            <input type="text" name="category" class="form-control"
                   value="{{ $product->category }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Price</label>
            <input type="number" name="price" class="form-control"
                   value="{{ $product->price }}" required>
        </div>

        <!-- FORM ACTION BUTTONS -->
        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">Back</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // ADD NEW IMAGE INPUT ROW DYNAMICALLY
    document.getElementById('addImage').onclick = function () {
        let html = `
            <div class="row mb-2 repeater-item">
                <div class="col-md-5">
                    <input type="file" name="images[]" class="form-control image-input" accept="image/*">
                </div>
                <div class="col-md-5">
                    <img src="" width="80" class="img-preview rounded border" style="display:none;">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger removeRow">Remove</button>
                </div>
            </div>`;
        // Append new row to repeater container
        document.getElementById('imageRepeater').insertAdjacentHTML('beforeend', html);
    };

    // REMOVE DYNAMIC IMAGE ROW
    document.addEventListener('click', e => {
        if (e.target.classList.contains('removeRow')) {
            // Remove entire row/container
            e.target.closest('.repeater-item').remove();
        }
    });

    // LIVE IMAGE PREVIEW FOR NEW UPLOADS
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('image-input')) {
            let file = e.target.files[0];
            let preview = e.target.closest('.repeater-item').querySelector('.img-preview');
            let reader = new FileReader();
            
            reader.onload = event => {
                // Display base64 preview immediately
                preview.src = event.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);  // Convert to base64 for preview
        }
    });
</script>
@endpush
