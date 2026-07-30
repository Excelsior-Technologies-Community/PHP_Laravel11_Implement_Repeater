@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Create Product</h1>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-bold">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Details</label>
            <textarea name="details" class="form-control" required>{{ old('details') }}</textarea>
            @error('details')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Product Images</label>
            <div id="imageRepeater">
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
                </div>
            </div>
            <button type="button" id="addImage" class="btn btn-secondary mt-2">+ Add More Images</button>
            @error('images.*')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Size</label>
            <input type="text" name="size" class="form-control" value="{{ old('size') }}" required>
            @error('size')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Color</label>
            <input type="text" name="color" class="form-control" value="{{ old('color') }}" required>
            @error('color')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Category</label>
            <input type="text" name="category" class="form-control" value="{{ old('category') }}" required>
            @error('category')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Price</label>
            <input type="number" name="price" class="form-control" value="{{ old('price') }}" required step="0.01">
            @error('price')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Status</label>
            <select name="status" class="form-select" required>
                <option value="">Select Status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ old('status') === $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Tags</label>
            <select name="tags[]" class="form-select select2" multiple>
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
                        {{ $tag->tag_name }}
                    </option>
                @endforeach
            </select>
            @error('tags.*')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Create Product</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary mt-2">Back</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
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
        document.getElementById('imageRepeater').insertAdjacentHTML('beforeend', html);
    };

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeRow')) {
            e.target.closest('.repeater-item').remove();
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('image-input')) {
            let file = e.target.files[0];
            let preview = e.target.closest('.repeater-item').querySelector('.img-preview');
            let reader = new FileReader();

            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
