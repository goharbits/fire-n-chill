<div class="credit-card-form mgb-60">
    <div class="card-form" id="creditCardForm">
        <div class="card-list"></div>
        <div class="card-form__inner">
            @include('partials.include.user_detail_form')
            <div class="field-group">
                <div class="terms-container">
                    <input type="checkbox" id="terms-checkbox" required>
                    <label for="terms-checkbox">I have read and understand the <a
                            href="{{ route('page.termsofservice') }}" class="terms-checkbox-color" target="_blank">Terms
                            & Conditions</a>.</label>
                </div>
            </div>
            @include('partials.include.error_with_button')
        </div>
    </div>
</div>
