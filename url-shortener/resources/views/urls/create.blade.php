@extends('layouts.app')

@section('content')
@php
    $props = [
        'action' => route('urls.store'),
        'csrfToken' => csrf_token(),
        'destination' => old('destination', ''),
        'indexUrl' => route('urls.index'),
    ];
@endphp

<div data-component="UrlsCreate" data-props='@json($props)'></div>
@endsection
