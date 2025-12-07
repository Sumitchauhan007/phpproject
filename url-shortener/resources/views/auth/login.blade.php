@extends('layouts.app')

@section('content')
<h1>Login</h1>
<form method="POST" action="{{ route('login.store') }}">
    @csrf
    <div>
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus>
    </div>
    <div>
        <label>Password</label>
        <input type="password" name="password" required>
    </div>
    <div>
        <label>
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            Remember me
        </label>
    </div>
    <button type="submit">Login</button>
</form>
@endsection
