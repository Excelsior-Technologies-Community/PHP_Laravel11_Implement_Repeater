@extends('layouts.tag')

@section('content')
<div class="container">
    <h1>Edit Tag</h1>

    <form action="{{ route('tags.update', $tag) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label fw-bold">Tag Name</label>
            <input type="text" name="tag_name" class="form-control" value="{{ old('tag_name', $tag->tag_name) }}" required>
            @error('tag_name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update Tag</button>
        <a href="{{ route('tags.index') }}" class="btn btn-secondary mt-2">Back</a>
    </form>
</div>
@endsection
