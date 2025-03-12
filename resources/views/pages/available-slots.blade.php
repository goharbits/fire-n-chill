<x-app-layout>
    <div class="page-container">
        <div class="session-booking-section overflow-hidden mgb-100">
            <div class="container">
                <div class="page-title text-center mgb-35">
                    <h1 class="fs-65 color-midnight fw-700 lh-13 text-uppercase ls-04-minus font-hornsea-fc mgb-15">BOOK
                        YOUR SESSION</h1>
                    <p class="fs-20 fw-300 lh-13 color-midnight">Pick date and time for your next visit to Fire & Chill.
                    </p>
                </div>
                @include('partials.response')
                <div class="booking-session-form">
                    <form method="GET"
                        action="{{ route($guest ? 'guest.service.status' : 'client.service.status') }}">
                        @if ($guest)
                            <input type="hidden" name="guest" value="true">
                        @endif
                        <div class="frm-field date-picker d-flex align-items-center">
                            <img class="mgr-10" src="{{ Vite::image('icon-date-picker.svg') }}" alt="Date">
                            <input class="date-pick-overly" type="text" id="datepicker" placeholder="Pick a date" autocomplete="off"
                                required>
                        </div>
                        <div class="frm-field time-picker d-flex align-items-center disabled">
                            <input type="hidden" name="StartDateTime" value="">
                            <img class="mgr-10" src="{{ Vite::image('icon-time-picker.svg') }}" alt="Date">
                            <p class="time-pick-overly open-popup disabled" data-target="pick-time" id="timepicker">Pick
                                a time</p>
                        </div>
                        <div class="field-block">
                            <p class="form-message"></p>
                        </div>
                        <div class="frm-field-btn text-center pdt-20">
                            <button
                                class="btn ssm bg-orange color-white text-center border-radius-50 lh-50"
                                id="book-appoinment" disabled type="submit">
                                <span class="fs-20 color-white fw-600 text-uppercase">NEXT</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('partials.popups.pick-time')
    <script type="application/json" id="bookableItems">@json($response->data)</script>
    @section('scripts')
        @parent
    @endsection
</x-app-layout>
