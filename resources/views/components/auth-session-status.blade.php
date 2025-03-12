@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert-success px-2 py-2']) }}>
        {{ $status }}
    </div>
@endif
