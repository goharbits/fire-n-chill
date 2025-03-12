@extends('errors::layout')

@section('title', __('Bad Gateway Alert!'))
@section('code', '502')
@section('message', __('Our server encountered an issue while trying to communicate. Please try again later. We’re working on it!'))
