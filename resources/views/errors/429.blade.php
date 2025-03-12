@extends('errors::layout')

@section('title', __('Whoa, Slow Down!'))
@section('code', '429')
@section('message')
It looks like you’ve been clicking a little too fast!<br>
Our servers need a breather before handling more requests. Please wait a moment and try again.<br>
If you think this is a mistake, please contact us at <a class="color-midnight text-decoration-underline fw-400 fs-20" href="mailto:{{ config('app.support_email') }}">{{ config('app.support_email') }}</a>.
@endsection
