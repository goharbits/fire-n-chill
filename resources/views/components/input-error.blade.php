@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'form-message error']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
