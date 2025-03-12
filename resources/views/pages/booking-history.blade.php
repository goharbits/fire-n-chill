<x-app-layout>
    <!-- Page container -->
    <div class="page-container">
        <div class="container">
            <div class="card mgb-30">
                <h2 class="fs-36 fw-700 lh-12 color-midnight font-hornsea-fc text-uppercase mgb-20">
                    {{ __('YOUR bookings') }}
                </h2>
                <div class="calender-histotry">
                    @if (count($bookings))
                        @foreach ($bookings as $booking)
                            @php
                                $start = \Carbon\Carbon::parse($booking->start_date_time);
                                $end = \Carbon\Carbon::parse($booking->end_date_time);
                            @endphp
                            <div class="calener-row d-flex align-items-center justify-content-between gap-5">
                                <div class="calendar-lt d-flex align-items-center">
                                    <div class="c-day position-relative mgr-10">
                                        <img src="{{ Vite::image('days-bg.png') }}" alt="User">
                                        <div
                                            class="date-view position-absolute top-0 left-0 d-flex align-items-center justify-content-center text-center h-100 w-100">
                                            <p>{{ $start->format('M') }}
                                                <span>{{ $start->format('j') }}</span></p>
                                        </div>
                                    </div>
                                    <div class="c-time">
                                        <p class="color-midnight fs-20 lh-11 fw-300">
                                            {{ $start->format('M. j, g:i A') }} to
                                            {{ $end->format('g:i A') }}</p>
                                    </div>
                                </div>
                                
                            </div>
                        @endforeach
                    @else
                        <div class="mgb-15 mgt-15">
                            <p class="text-center">{{ __('No bookings found') }}</p>
                        </div>
                    @endif
                </div>

                <div class="btn-wrap overflow-hidden d-flex justify-content-left">
                    @if ($has_visits || count($bookings))
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
        </div>
    </div>
    @section('scripts')
        <script type="module"></script>
    @endsection

</x-app-layout>