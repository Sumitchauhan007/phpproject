@extends('layouts.app')

@section('content')
@php
    $props = [
        'canCreate' => auth()->user()->canCreateUrls(),
        'createUrl' => route('urls.create'),
        'urls' => $urls->map(fn ($url) => [
            'id' => $url->id,
            'slug' => $url->slug,
            'destination' => $url->destination,
            'company' => optional($url->company)->name,
            'creator' => optional($url->creator)->name,
        ])->values(),
    ];
@endphp

<div data-component="UrlsIndex" data-props='@json($props)'></div>
@endsection
