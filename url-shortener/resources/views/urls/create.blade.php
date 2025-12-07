@extends('layouts.app')

@section('content')
<h1>Create Short URL</h1>
<form method="POST" action="{{ route('urls.store') }}">
    @csrf
    <div>
        <label>Destination URL</label>
        <input type="url" name="destination" value="{{ old('destination') }}" required>
    </div>
    <button type="submit">Generate</button>
</form>
@endsection
