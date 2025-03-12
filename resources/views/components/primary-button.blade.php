<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn bg-orange color-white text-center align-items-center justify-content-center d-flex border-radius-50 submit-btn mw-250 m-auto']) }}>
    {{ $slot }}
</button>
