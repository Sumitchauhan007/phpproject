@extends('layouts.app')

@section('content')
@php
    $props = [
        'action' => route('login.store'),
        'csrfToken' => csrf_token(),
        'email' => old('email', ''),
        'remember' => (bool) old('remember', false),
    ];
@endphp

<div data-component="LoginForm" data-props='@json($props)'></div>
@endsection
