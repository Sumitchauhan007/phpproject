@extends('layouts.app')

@section('content')
<h1>Dashboard</h1>
<p>Welcome, {{ $user->name }} ({{ ucfirst(str_replace('_', ' ', $user->role)) }})</p>

@if($user->isSuperAdmin())
    <h2>Companies</h2>
    <table border="1" cellpadding="4">
        <thead>
            <tr>
                <th>Name</th>
                <th>User Count</th>
            </tr>
        </thead>
        <tbody>
        @forelse($companies as $company)
            <tr>
                <td>{{ $company->name }}</td>
                <td>{{ $company->users_count }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2">No companies yet.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@else
    <h2>Recent Short URLs</h2>
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
                <td colspan="4">No URLs available.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endif
@endsection
