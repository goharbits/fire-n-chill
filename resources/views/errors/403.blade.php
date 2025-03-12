@extends('errors::layout')

@section('title', __('Access Denied!'))
@section('code', '403')
@section('message')
    You don’t have permission to enter this area.<br>
    If you think this is a mistake, please contact us at <a class="color-midnight text-decoration-underline fw-400 fs-20"
        href="mailto:{{ config('app.support_email') }}">{{ config('app.support_email') }}</a>.
@endsection
