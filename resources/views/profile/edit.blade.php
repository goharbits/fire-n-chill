<x-profile-layout>
    <h1 class="fs-65 text-left color-midnight fw-700 lh-13 text-uppercase ls-04-minus font-hornsea-fc mgb-15">
        {{ __('Profile') }}
    </h1>
    <div class="pt-5">

        <div class="row">
            <div class="col-12 col-md-3 mb-5 sm:p-0">
                <div class="sticky mgr-20">
                    <h2 class="pl-3 mb-4 color-midnight hide-mob">Settings</h2>
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a href="{{ route('profile.edit') }}" class="nav-link {{ !$setting ? 'active' : '' }}">
                                <span>Profile</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('profile.edit', ['setting' => 'password']) }}"
                                class="nav-link {{ $setting == 'password' ? 'active' : '' }}">
                                <span>Password</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('profile.edit', ['setting' => 'billing']) }}"
                                class="nav-link {{ $setting == 'billing' ? 'active' : '' }}">
                                <span>Billing</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-12 col-md-9 sm:p-0">
                <div class="p-2 md:p-4">
                    <div class="w-full px-6 pb-8 mt-8 sm:max-w-xl sm:rounded-lg">    
                        <div class="grid max-w-2xl mx-auto">
                            @if (!$setting)
                                @include('profile.partials.update-profile-information-form')
                            @elseif ($setting == 'password')
                                @include('profile.partials.update-password-form')
                            @elseif ($setting == 'billing')
                                @include('profile.partials.update-card-information-form')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-profile-layout>
