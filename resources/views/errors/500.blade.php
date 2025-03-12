@extends('errors::layout')

@section('title', __('Internal Server Error'))
@section('code', '500')
@section('message')
    Something went wrong on our end. We’re working to fix it as quickly as possible. Please try again later or contact us at <a class="color-midnight text-decoration-underline fw-400 fs-20" href="mailto:{{ config('app.support_email') }}">{{ config('app.support_email') }}</a>.
@endsection
