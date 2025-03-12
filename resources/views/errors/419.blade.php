@extends('errors::layout')

@section('title', __('Session Timeout!'))
@section('code', '419')
@section('message')
    Your session has expired, but don’t worry—it happens to the best of us! <br>Please <a class="color-midnight text-decoration-underline fw-400 fs-20" href="{{ route('login') }}">login</a> again.
@endsection
