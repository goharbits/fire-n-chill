<div class="form-field text-center ">
    <p class="form-message"></p>
</div>
<div class="check-out-btn text-center mgt-10">
    <button type="submit"
        class="btn fw-700 ssm bg-orange hover-border color-white text-center border-radius-50 lh-50 text-uppercase mgb-40 "><span>
            Book Appointment</span>
    </button>
    <div class="mgt-5">
        <?php
        $route = $guest ? 'guest.service.status' : 'client.service.status';
        $params = $guest ? ['guest' => 'true', 'StartDateTime' => request()->StartDateTime] : ['StartDateTime' => request()->StartDateTime];
        ?>
        <a class="back-link fw-700 fs-20 lh-12 color-midnight text-decoration-underline text-uppercase"
            href="{{ route($route, $params) }}">Back to plan selection</a>
    </div>
</div>
