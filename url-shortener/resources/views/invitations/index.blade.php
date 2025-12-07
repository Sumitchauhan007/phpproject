@extends('layouts.app')

@section('content')
<h1>Invitations</h1>
@if($user->isSuperAdmin() || $user->isAdmin())
    <p><a href="{{ route('invitations.create') }}">Invite Someone</a></p>
@endif
<table border="1" cellpadding="4">
    <thead>
        <tr>
            <th>Email</th>
            <th>Role</th>
            <th>Company</th>
            <th>Invited By</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    @forelse($invitations as $invitation)
        <tr>
            <td>{{ $invitation->email }}</td>
            <td>{{ ucfirst(str_replace('_', ' ', $invitation->role)) }}</td>
            <td>{{ optional($invitation->company)->name ?? '—' }}</td>
            <td>{{ optional($invitation->inviter)->name ?? '—' }}</td>
            <td>{{ $invitation->accepted_at ? 'Accepted' : 'Pending' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5">No invitations found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
@endsection
