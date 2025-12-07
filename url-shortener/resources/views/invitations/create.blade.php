@extends('layouts.app')

@section('content')
<h1>Send Invitation</h1>
<form method="POST" action="{{ route('invitations.store') }}">
    @csrf
    <div>
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>
    </div>
    <div>
        <label>Role</label>
        <select name="role" required>
            <option value="">Select a role</option>
            @foreach($roles as $role)
                <option value="{{ $role }}" @selected(old('role') === $role)>
                    {{ ucfirst(str_replace('_', ' ', $role)) }}
                </option>
            @endforeach
        </select>
    </div>
    @if($user->isSuperAdmin())
        <div>
            <label>Company</label>
            <select name="company_id">
                <option value="">Select a company</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" @selected((string)old('company_id') === (string)$company->id)>{{ $company->name }}</option>
                @endforeach
            </select>
        </div>
        <p>Super admins may leave company empty only when inviting another super admin.</p>
    @else
        <p>Invitations will be created for {{ optional($user->company)->name ?? 'your company' }}.</p>
    @endif
    <button type="submit">Send Invitation</button>
</form>
@endsection
