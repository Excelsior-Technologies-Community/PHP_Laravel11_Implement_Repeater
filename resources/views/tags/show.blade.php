@extends('layouts.tag')

@section('content')
<div class="container">
    <h1>{{ $tag->tag_name }}</h1>
    <a href="{{ route('tags.index') }}" class="btn btn-secondary mt-2">Back</a>
</div>
@endsection
