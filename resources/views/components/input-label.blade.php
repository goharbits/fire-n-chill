@props(['value'])

<label {{ $attributes->merge(['class' => 'fw-300 lh-10 color-midnight text-left']) }}>
    {{ $value ?? $slot }}
</label>
