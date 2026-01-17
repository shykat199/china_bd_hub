@extends('frontend.layouts.front')

@section('title', config('app.name', '') )

@section('content')

    @include('frontend._banner')

    @include('frontend._notice')

    {{-- @include('frontend._discount') --}}

    {{-- @include('frontend._categories') --}}

    {{-- @include('frontend._ad-poster') --}}

    {{-- @include('frontend._product-tab') --}}

    @include('frontend._offer-count')

    @include('frontend._products')

{{--    @include('frontend._service')--}}

{{--    @include('frontend._brand-logo')--}}

    @if (isset($pop_up->is_active) && $pop_up->is_active)

        @include('frontend._popup', ['pop_up' => $pop_up])

    @endif

@stop
