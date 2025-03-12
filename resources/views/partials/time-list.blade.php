<div class="time-list mgb-20">
    @php
    $start = strtotime(config('app.opening_time'));
    $end = strtotime(config('app.closing_time'));

    while ($start < $end):
        $from = date('h:i A', $start);
        $next = strtotime('+30 minutes', $start);
        $to = date('h:i A', $next);
        @endphp

        <div class="time-item" data-time="{{ "$from - $to" }}" data-time-text='{{ "$from to $to" }}'>
            <span class="fs-20 fw-300 font-altivo color-midnight time-start">{{ __($from) }}</span>
            <span class="time-devider"></span>
            <span class="fs-20 fw-300 font-altivo color-midnight time-end">{{ __($to) }}</span>
        </div>

        @php
        $start = $next;
    endwhile;
    @endphp
</div>
