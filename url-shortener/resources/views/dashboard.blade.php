@extends('layouts.app')

@section('content')
@php
    $props = [
        'user' => [
            'name' => $user->name,
            'role' => $user->role,
        ],
        'companies' => $companies->map(fn ($company) => [
            'id' => $company->id,
            'name' => $company->name,
            'users_count' => $company->users_count,
        ])->values(),
        'urls' => $urls->map(fn ($url) => [
            'id' => $url->id,
            'slug' => $url->slug,
            'destination' => $url->destination,
            'company' => optional($url->company)->name,
            'creator' => optional($url->creator)->name,
        ])->values(),
    ];
@endphp

<div data-component="Dashboard" data-props='@json($props)'></div>
@endsection
