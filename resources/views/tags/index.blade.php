@extends('layouts.tag')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">🏷 Tags</h2>
    <a href="{{ route('tags.create') }}" class="btn btn-primary">➕ Add New Tag</a>
</div>

@if(session('success'))
    <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Tag Name</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($tags as $tag)
                        <tr>
                            <td>{{ $tag->id }}</td>
                            <td class="fw-semibold">{{ $tag->tag_name }}</td>
                            <td class="text-center">
                                <a href="{{ route('tags.edit', $tag) }}" class="btn btn-warning btn-sm me-1">✏ Edit</a>

                                <form action="{{ route('tags.destroy', $tag) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this tag?')">
                                        🗑 Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">No tags found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tags->hasPages())
            <div class="mt-3">
                {{ $tags->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
