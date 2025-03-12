<x-app-layout>
    <div class="page-container container">
        <div class="your-booking-section mgb-80">
            <div class="container">
                <div class="row">
                    <div class="col-6">
                        <div class="package-details bg-midnight">
                            <div class="package-info d-flex align-items-center">
                                <div class="package-image">
                                    <img src="{{ @$client_vitality->image_path }}" alt="User">
                                </div>
                                <div class="package-in">
                                    <p class="fs-16 lh-14 color-white fw-400">
                                        Progress towards PEAK VITALITY:
                                        {{ @$client_vitality->total_vitality_achieved }}%
                                    </p>
                                    <a class="btn bg-transparent open-popup-btn border-white text-uppercase fs-16 moreInfo open-popup"
                                        data-target="more-info" href="javascript:void(0)">
                                        <span class="color-white">{{ __('MORE INFO') }}</span>
                                    </a>
                                </div>
                            </div>
                            <div class="sesion-progress">
                                <div class="session-copy d-flex align-items-center justify-content-between">
                                    <p class="color-white fw-400 lh-11">
                                        {{ $client_vitality->total_session_booked }} sessions</p>
                                    <p class="color-white fw-600 lh-11">
                                        {{ Number::percentage($client_vitality->total_vitality_achieved) }}
                                        vitality</p>
                                </div>
                                <div class="progress-bar bg-sky-blue" style="width: 100%">
                                    <span class="bg-orange"
                                        style="width: {{ Number::percentage($client_vitality->total_vitality_achieved) }}"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">

                            <div class="d-flex justify-content-between align-items-center mgb-20">
                                <h2 class="fs-36 fw-700 lh-12 color-midnight font-hornsea-fc text-uppercase mg-0">
                                    {{ __('YOUR bookings') }}</h2>
                                @if (!is_null(@$client_visits->data) && count(@$client_visits->data))
                                    <div class="view-history text-end">
                                        <a class="fs-20 fw-400 color-midnight text-decoration-underline"
                                            href="{{ route('client.booking.history') }}">{{ __('View History') }}</a>
                                    </div>
                                @endif
                            </div>
                            <div class="calender-histotry">
                                @if (count($bookings))
                                    @foreach ($bookings as $booking)
                                        @php
                                            $start = \Carbon\Carbon::parse($booking->start_date_time);
                                            $end = \Carbon\Carbon::parse($booking->end_date_time);
                                        @endphp
                                        <div
                                            class="calener-row d-flex align-items-center justify-content-between gap-4">
                                            <div class="calendar-lt d-flex align-items-center">
                                                <div class="c-day position-relative mgr-10">
                                                    <img src="{{ Vite::image('days-bg.png') }}" alt="User">
                                                    <div
                                                        class="date-view position-absolute top-0 left-0 d-flex align-items-center justify-content-center text-center h-100 w-100">
                                                        <p>{{ $start->format('M') }}
                                                            <span>{{ $start->format('j') }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="c-time">
                                                    <p class="color-midnight fs-20 lh-11 fw-300">
                                                        {{ $start->format('M. j, g:i A') }} to
                                                        {{ $end->format('g:i A') }}</p>
                                                </div>
                                            </div>
                                            <div class="calendar-rt">
                                                {{-- <button disabled type="button" class="btn border-2x-blue color-midnight text-center border-radius-50 lh-50 open-popup edit-appointment"
                                                    data-target="update-appointment"
                                                    data-appointment-id="{{ $booking->id }}"
                                                    data-appointment-datetime="{{ $start->format('Y-m-d\TH:i:s') }}">
                                                    <span class="fs-16 color-midnight fw-600 text-uppercase">{{ __('Edit') }}</span>
                                                </button> --}}
                                                <a href="{{ route('client.edit.appointment', ['id' => $booking->id]) }}"
                                                    class="btn border-2x-blue color-midnight text-center border-radius-50 lh-50 edit-appointment">
                                                    <span
                                                        class="fs-16 color-midnight fw-600 text-uppercase">{{ __('Edit') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="mgb-15 mgt-15">
                                        <p class="text-center">
                                            @if (!is_null(@$client_visits->data) && count(@$client_visits->data))
                                                {{ __('No upcoming sessions! Time to schedule your next session.') }}
                                            @else
                                                {{ __('No bookings yet! Time to schedule your first session.') }}
                                            @endif
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <div class="btn-wrap overflow-hidden d-flex justify-content-center">
                                @if ((!is_null(@$client_visits->data) && count(@$client_visits->data)) || (!is_null($bookings) && count($bookings)))
                                    <a class="btn bg-orange color-white text-center align-items-center justify-content-center d-flex border-radius-50"
                                        href="{{ route('client.slots.available') }}">
                                        <span
                                            class="text-uppercase font-altivo fw-700 lh-1">{{ __('Book Your Next Session') }}</span>
                                    </a>
                                @else
                                    <a class="btn bg-orange color-white text-center align-items-center justify-content-center d-flex border-radius-50"
                                        href="{{ route('client.slots.available') }}">
                                        <span
                                            class="text-uppercase font-altivo fw-700 lh-1">{{ __('Book Your First  Session') }}</span>
                                    </a>
                                @endif
                            </div>

                        </div>
                        <div class="user-profile-section card">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="fs-36 fw-700 lh-12 color-midnight font-hornsea-fc text-uppercase mg-0">
                                    {{ __('My Profile') }}</h2>
                                <div class="view-history text-end">
                                    <a class="fs-20 fw-400 color-midnight text-decoration-underline"
                                        href="{{ route('profile.edit') }}">{{ __('Edit profile') }}</a>
                                </div>
                            </div>
                            <div class="user-details mgt-20">
                                <div class="user-details--inner d-flex gap-5">
                                    <div class="user-avatar">
                                        <svg width="100px" height="100px" xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1"
                                            x="0px" y="0px" viewBox="0 0 32 32" style="enable-background:new 0 0 32 32;"
                                            xml:space="preserve">
                                            <g>
                                                <g>
                                                    <path
                                                        d="M16,31C7.7285156,31,1,24.2709961,1,16S7.7285156,1,16,1s15,6.7290039,15,15S24.2714844,31,16,31z M16,3    C8.8320313,3,3,8.831543,3,16s5.8320313,13,13,13s13-5.831543,13-13S23.1679688,3,16,3z" />
                                                </g>
                                                <g>
                                                    <g>
                                                        <circle cx="16" cy="15.1333332" r="4.2666669" />
                                                    </g>
                                                    <g>
                                                        <path
                                                            d="M16,30c2.401123,0,4.6600342-0.6062012,6.6348877-1.6712646C22.210083,25.1000366,19.4553223,22.5083008,16,22.5083008     s-6.210083,2.5917358-6.6348877,5.8204346C11.3399658,29.3937988,13.598877,30,16,30z" />
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <div class="user-info">
                                        <p class="color-midnight"><span class="fw-600">Name:</span>
                                            {{ $user->first_name }} {{ $user->last_name ?? '' }}</p>
                                        <p class="color-midnight"><span class="fw-600">Email:</span>
                                            {{ $user->email }}
                                            <span class="email-status">
                                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                                    <a href="javascript:void(0)" class="verify-email-link">Verify
                                                        Email</a>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"
                                                        version="1.1" id="Capa_1" width="16" height="16"
                                                        class="verified-icon" viewBox="0 0 536.541 536.541"
                                                        xml:space="preserve">
                                                        <g>
                                                            <g>
                                                                <path
                                                                    d="M496.785,152.779c-3.305-25.085-16.549-51.934-38.826-74.205c-22.264-22.265-49.107-35.508-74.186-38.813    c-11.348-1.499-26.5-7.766-35.582-14.737C328.111,9.626,299.764,0,268.27,0s-59.841,9.626-79.921,25.024    c-9.082,6.965-24.235,13.238-35.582,14.737c-25.08,3.305-51.922,16.549-74.187,38.813c-22.277,22.271-35.521,49.119-38.825,74.205    c-1.493,11.347-7.766,26.494-14.731,35.57C9.621,208.422,0,236.776,0,268.27s9.621,59.847,25.024,79.921    c6.971,9.082,13.238,24.223,14.731,35.568c3.305,25.086,16.548,51.936,38.825,74.205c22.265,22.266,49.107,35.51,74.187,38.814    c11.347,1.498,26.5,7.771,35.582,14.736c20.073,15.398,48.421,25.025,79.921,25.025s59.841-9.627,79.921-25.025    c9.082-6.965,24.234-13.238,35.582-14.736c25.078-3.305,51.922-16.549,74.186-38.814c22.277-22.27,35.521-49.119,38.826-74.205    c1.492-11.346,7.766-26.492,14.73-35.568c15.404-20.074,25.025-48.422,25.025-79.921c0-31.494-9.621-59.848-25.025-79.921    C504.545,179.273,498.277,164.126,496.785,152.779z M439.256,180.43L246.477,373.209l-30.845,30.846    c-8.519,8.52-22.326,8.52-30.845,0l-30.845-30.846l-56.665-56.658c-8.519-8.52-8.519-22.326,0-30.846l30.845-30.844    c8.519-8.519,22.326-8.519,30.845,0l41.237,41.236L377.561,118.74c8.52-8.519,22.326-8.519,30.846,0l30.844,30.845    C447.775,158.104,447.775,171.917,439.256,180.43z" />
                                                            </g>
                                                        </g>
                                                    </svg>
                                                @endif
                                            </span>
                                        </p>
                                        {{--
                                        <p class="color-midnight"><span class="fw-600">Date of birth:</span>
                                        @if (!empty($user->birth_date) && \Carbon\Carbon::hasFormat($user->birth_date, 'Y-m-d'))
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $user->birth_date)->format('jS F, Y') }}
                                        @else
                                            <p class="color-midnight">Birth date not available</p>
                                        @endif
                                        </p>
                                        --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Your Books end -->

        <!-- Loyalty -->
        {{-- <div class="rewards-sction mgb-160">
            <div class="container">
                <div class="slider-heading mgb-25">
                    <h2 class="fs-65 font-hornsea-fc color-midnight text-uppercase">{{ __('Loyalty') }}</h2>
                </div>
            </div>
            <div class="">
                <div class="levels-slider skeleton-slider d-flex mgb-100" id="loyality-levels-slider" data-route-url="{{ route('client.get.loyality') }}">
                    @for ($i = 0; $i < 4; $i++) <!-- 6 skeleton items (adjust as needed) -->
                        <div class="reward">
                            <a href="javascript:void(0)" class="skeleton-box">
                                <div class="reward-img text-center">
                                    <div class="skeleton-img"></div>
                                    <p class="skeleton-button"></p>
                                    <p class="skeleton-text"></p>
                                </div>
                            </a>
                        </div>
                    @endfor
                </div>
            </div>
        </div> --}}
        <!-- Loyalty end -->

        <!-- Rewards -->
        <div class="rewards-sction mgb-160">
            <div class="container">
                <div class="slider-heading mgb-25">
                    <h2 class="fs-65 font-hornsea-fc color-midnight text-uppercase">{{ __('REWARDS') }}</h2>
                </div>
            </div>
            <div class="">
                <div class="rewards-slider skeleton-slider d-flex mgb-100" id="rewards-slider"
                    data-route-url="{{ route('client.get.rewards') }}">
                    @for ($i = 0; $i < 4; $i++)
                        <!-- 6 skeleton items (adjust as needed) -->
                        <div class="reward">
                            <a href="javascript:void(0)">
                                <div class="reward-img skeleton-box">
                                    <div class="skeleton-img"></div>
                                </div>
                                <div class="reward-info">
                                    <p class="skeleton-text"></p>
                                    <div class="btn-wrap">
                                        <span class="skeleton-button"></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endfor
                </div>
                <div class="view-more-rewards text-center">
                    <a class="btn ssm border-2x-blue color-midnight text-center border-radius-50 lh-50"
                        href="{{ route('client.show.rewards') }}"><span
                            class="fs-16 color-midnight fw-600 text-uppercase">{{ __('view all rewards') }}</span></a>
                </div>
            </div>

        </div>
        <!-- Rewards end -->
    </div>

    <div style="display:none;" class="page-popup-section pick-time popup" aria-modal="update-appointment"
        id="update-appointment">
        <div class="popup-container position-relative">
            <div class="closepop position-absolute">
                <img src="{{ Vite::image('icon-close-blue.svg') }}" alt="{{ __('Close') }}">
            </div>
            <div class="popup-title text-center">
                <p class="color-midnight lh-15 fs-20 fw-300 mgb-15 selected-date">{{ __('October, Saturday 6th.') }}
                </p>
                <h2 class="fw-700 font-hornsea-fc color-black fs-65 lh-11 mgb-30">{{ __('Update appointment') }}</h2>
            </div>
            <div class="popup-holder">
                <form id="update-appointment-form" action="{{ route('client.update.appointment') }}" method="POST"
                    data-js-form>
                    @csrf
                    <input type="hidden" name="appointment_id" value="">
                    <input type="hidden" name="start_date_time" value="">
                    @include('partials.time-list')
                    <div class="field-block text-center">
                        <p class="form-message"></p>
                    </div>
                </form>
            </div>
            <div class="popup-footer">
                <div class="btn-wrap text-center time-picked">
                    <button form="update-appointment-form"
                        class="btn ssm bg-orange color-white text-center border-radius-50 lh-50 confirm-time-slot"
                        disabled id="confirm-time-slot">
                        <span class="fs-16 color-midnight fw-600 text-uppercase">{{ __('Update') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div style="display:none;" class="page-popup-section more-info-poup popup" aria-modal="more-info"
        id="more-info">
        <div class="popup-container position-relative">
            <div class="closepop position-absolute">
                <img src="{{ Vite::image('icon-close-blue.svg') }}" alt="{{ __('Close') }}">
            </div>
            <div class="popup-logo text-center mgb-30">
                <img src="{{ Vite::image('fc-logo-orange-blue.svg') }}" alt="{{ __('FC logo') }}">
            </div>
            <div class="popup-title text-center mgb-30">
                <h2 class="fw-700 font-hornsea-fc color-black fs-65 lh-11 mgb-30">{{ __('Peak Vitality') }}</h2>
                <p class="color-midnight lh-15 fs-20 fw-300 mgb-15">{!! $client_vitality->info_message !!}.</p>
                <p class="color-midnight lh-15 fs-20 fw-300">
                    {{ __('Work towards Peak Vitality and Unlock Rewards') }}<br> {{ __('at each stage.') }}</p>
            </div>
            <div class="btn-wrap text-center">
                <a class="btn ssm border-2x-blue color-midnight text-center border-radius-50 lh-50"
                    href="{{ route('client.slots.available') }}">
                    <span
                        class="fs-16 color-midnight fw-600 text-uppercase">{{ __('Book your next session') }}</span></a>
            </div>
        </div>
    </div>

    @include('partials.popups.loyality')

    @section('scripts')
        @parent
        <script type="module">
            document.addEventListener('DOMContentLoaded', () => {
                const updateAppointmentPopup = document.getElementById('update-appointment');
                const updateAppointmentForm = updateAppointmentPopup.querySelector('[data-js-form]');
                const confirmTimeSlotBtn = updateAppointmentPopup.querySelector('#confirm-time-slot');

                async function fetchBookableItems() {
                    try {
                        let routeUrl = "{{ route('client.bookableitems') }}";
                        if (window.location.protocol === 'https:' && routeUrl.startsWith('http://')) {
                            routeUrl = routeUrl.replace('http://', 'https://');
                        }

                        const response = await axios.get(routeUrl);

                        if (response.data.status !== 200) {
                            return [];
                        }

                        attachEventListeners(response.data.data);
                        document.querySelectorAll('.edit-appointment').forEach((editAppointment) => {
                            editAppointment.disabled = false;
                        });
                    } catch (error) {
                        return [];
                    }
                }

                fetchBookableItems();

                function attachEventListeners(bookableItems) {
                    document.addEventListener("popup:opened", (e) => {
                        const popup = e.detail.popup;
                        const trigger = e.detail.trigger;

                        if (popup.id !== 'update-appointment') {
                            return;
                        }

                        const appointmentDateTime = trigger.dataset.appointmentDatetime;
                        const appointmentId = trigger.dataset.appointmentId;
                        const appointmentDate = appointmentDateTime.split('T')[0];
                        const appointmentTime = appointmentDateTime.split('T')[1];
                        const availableSlots = bookableItems[appointmentDate];

                        updateAppointmentForm.querySelector('input[name="appointment_id"]').value =
                            appointmentId;
                        updateAppointmentForm.querySelector('input[name="start_date_time"]').value =
                            appointmentDateTime;

                        const bookSessions = new window.app.BookingSession({
                            bookableItems,
                            confirmBtnText: 'Update to [timeslot]',
                            date: appointmentDate,
                            startDateTimeInput: updateAppointmentForm.querySelector(
                                'input[name="start_date_time"]'),
                        })

                        bookSessions.setupTimePicker(availableSlots, false);
                        bookSessions.updateSelectedDateDisplay();

                    });
                }

                /*
                 * Rewards slider
                 */
                new window.app.Rewards('#rewards-slider')

                /*
                 * Loyalty slider
                 */
                new window.app.Loyality('#loyality-levels-slider')

            });
        </script>
    @endsection
</x-app-layout>
