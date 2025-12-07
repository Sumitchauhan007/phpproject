@extends('layouts.app')

@section('content')
<h1>Short URLs</h1>
@if(auth()->user()->canCreateUrls())
    <a href="{{ route('urls.create') }}">Create Short URL</a>
@endif
<table border="1" cellpadding="4">
    <thead>
        <tr>
            <th>Slug</th>
            <th>Destination</th>
            <th>Company</th>
            <th>Creator</th>
        </tr>
    </thead>
    <tbody>
    @forelse($urls as $url)
        <tr>
            <td>{{ $url->slug }}</td>
            <td>{{ $url->destination }}</td>
            <td>{{ optional($url->company)->name }}</td>
            <td>{{ optional($url->creator)->name }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="4">No short URLs found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
@endsection
