@extends('layouts.tag')

@section('content')
<div class="container">
    <h1>Create Tag</h1>

    <form action="{{ route('tags.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-bold">Tag Name</label>
            <input type="text" name="tag_name" class="form-control" value="{{ old('tag_name') }}" required>
            @error('tag_name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Create Tag</button>
        <a href="{{ route('tags.index') }}" class="btn btn-secondary mt-2">Back</a>
    </form>
</div>
@endsection
