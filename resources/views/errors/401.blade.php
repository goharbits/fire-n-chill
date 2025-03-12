@extends('errors::layout')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message')
    You need to log in to access this page. Please <a class="color-midnight text-decoration-underline fw-400 fs-20" href="{{ route('login') }}">login</a> or <a class="color-midnight text-decoration-underline fw-400 fs-20" href="{{ route('register') }}">register</a> if you don’t have an account yet.
@endsection
