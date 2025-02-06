<x-layout>
    <div class="container rounded p-3 mb-5">
        <h1 class="text-center mb-3">{{ __('ui.bookingStatus') }}</h1>

        @if (!session('verified'))
            <form method="POST" action="{{ route('booking.status.check') }}">
                @csrf
                <div class="d-flex justify-content-center align-items-center">

                    <div class="row w-md-50">
                        <div class="col-12 mb-3">
                            <label for="email">{{ __('ui.bookingStatusEmailVerification') }}</label>
                            <input type="email" class="form-control form_input_focused" id="email" name="email"
                                placeholder="example@mail.com" value="{{ request()->query('email') }}" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="code">{{ __('ui.bookingStatusIDVerification') }}</label>
                            <input type="text" class="form-control form_input_focused" id="code" name="code"
                                placeholder="Eg: {{ strtoupper(Str::random(6)) }}"
                                value="{{ request()->query('code') }}" required>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary">{{ __('ui.verifyEmail') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        @else
            <div class="container">
                <x-booking-show :booking="$booking" :bookingData="$booking->bookingData" />
            </div>
        @endif


        <div class="row mt-5">
            <div class="col-12">
                <x-contact-link />
            </div>
            <div class="col-12 mt-5">
                <h2 class="text-center">{{ __('ui.title2') }}</h2>
                <x-services />
            </div>
            <div class="col-12 mt-5">
                <h2 class="text-center mb-3">{{ __('ui.title3') }}</h2>
                <x-excursions />
            </div>
        </div>
    </div>
</x-layout>
