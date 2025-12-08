@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Create Product</h1>

    <!-- Main form with multipart for multiple image uploads -->
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf  <!-- CSRF protection token -->

        <!-- PRODUCT NAME INPUT -->
        <div class="mb-3">
            <label class="form-label fw-bold">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <!-- PRODUCT DETAILS TEXTAREA -->
        <div class="mb-3">
            <label class="form-label fw-bold">Details</label>
            <textarea name="details" class="form-control" required></textarea>
        </div>

        <!-- DYNAMIC MULTIPLE IMAGE UPLOAD WITH PREVIEW -->
        <div class="mb-3">
            <label class="form-label fw-bold">Product Images</label>

            <!-- Image repeater container -->
            <div id="imageRepeater">
                <!-- Initial image input row with preview & remove button -->
                <div class="row mb-2 repeater-item">
                    <div class="col-md-5">
                        <!-- File input with array name for multiple uploads -->
                        <input type="file" name="images[]" class="form-control image-input" accept="image/*">
                    </div>

                    <div class="col-md-5">
                        <!-- Image preview (hidden initially) -->
                        <img src="" width="80" class="img-preview rounded border" style="display:none;">
                    </div>

                    <div class="col-md-2">
                        <!-- Remove button for this image row -->
                        <button type="button" class="btn btn-danger removeRow">Remove</button>
                    </div>
                </div>
            </div>

            <!-- Add more image inputs button -->
            <button type="button" id="addImage" class="btn btn-secondary mt-2">+ Add More Images</button>
        </div>

        <!-- PRODUCT SIZE INPUT -->
        <div class="mb-3">
            <label class="form-label fw-bold">Size</label>
            <input type="text" name="size" class="form-control" required>
        </div>

        <!-- PRODUCT COLOR INPUT -->
        <div class="mb-3">
            <label class="form-label fw-bold">Color</label>
            <input type="text" name="color" class="form-control" required>
        </div>

        <!-- PRODUCT CATEGORY INPUT -->
        <div class="mb-3">
            <label class="form-label fw-bold">Category</label>
            <input type="text" name="category" class="form-control" required>
        </div>

        <!-- PRODUCT PRICE INPUT -->
        <div class="mb-3">
            <label class="form-label fw-bold">Price</label>
            <input type="number" name="price" class="form-control" required>
        </div>

        <!-- FORM ACTION BUTTONS -->
        <button type="submit" class="btn btn-primary">Create Product</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary mt-2">Back</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // ADD NEW IMAGE INPUT ROW
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

    // REMOVE IMAGE ROW
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeRow')) {
            // Remove entire row when Remove button clicked
            e.target.closest('.repeater-item').remove();
        }
    });

    // IMAGE PREVIEW ON FILE SELECT
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('image-input')) {
            let file = e.target.files[0];
            let preview = e.target.closest('.repeater-item').querySelector('.img-preview');
            let reader = new FileReader();
            
            reader.onload = e => {
                // Show preview image when file selected
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);  // Convert file to base64 for preview
        }
    });
</script>
@endpush
