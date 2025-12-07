@extends('layouts.app')

@section('content')
@php
    $props = [
        'action' => route('invitations.store'),
        'csrfToken' => csrf_token(),
        'roles' => $roles,
        'companies' => $companies->map(fn ($company) => [
            'id' => $company->id,
            'name' => $company->name,
        ])->values(),
        'isSuperAdmin' => $user->isSuperAdmin(),
        'companyName' => optional($user->company)->name,
        'email' => old('email', ''),
        'selectedRole' => old('role', ''),
        'selectedCompanyId' => old('company_id', ''),
        'indexUrl' => route('invitations.index'),
    ];
@endphp

<div data-component="InvitationsCreate" data-props='@json($props)'></div>
@endsection
