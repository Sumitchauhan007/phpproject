@extends('layouts.app')

@section('content')
@php
    $props = [
        'canInvite' => $user->isSuperAdmin() || $user->isAdmin(),
        'createUrl' => route('invitations.create'),
        'invitations' => $invitations->map(fn ($invitation) => [
            'id' => $invitation->id,
            'email' => $invitation->email,
            'role' => $invitation->role,
            'company' => optional($invitation->company)->name,
            'invited_by' => optional($invitation->inviter)->name,
            'accepted_at' => optional($invitation->accepted_at)?->toDateTimeString(),
        ])->values(),
    ];
@endphp

<div data-component="InvitationsIndex" data-props='@json($props)'></div>
@endsection
