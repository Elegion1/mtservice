<x-layout>
    <div class="container my-5">
        <h1>{{ __('ui.createReview') }}</h1>

        <!-- Informazioni sulla Prenotazione -->
        <div class="alert alert-info">
            <strong>{{ __('ui.bookingID') }}:</strong> {{ $booking->code }}<br>
            <strong>{{ __('ui.bookingType') }}:</strong> {{ ucfirst($booking->bookingData['type']) }}<br>
        </div>

        <!-- Form per Creare la Recensione -->
        <form action="{{ route('customer.reviews.store') }}" method="POST">
            @csrf <!-- Token CSRF per sicurezza -->
            <input type="hidden" name="booking" value="{{ $booking->code }}">

            <!-- Nome -->
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('ui.yourName') }} <span><small
                            class="text-secondary">{{ __('ui.nameMessage') }}</small></span></label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Es. Mario Rossi"
                    required>
            </div>

            <!-- Titolo della Recensione -->
            <div class="mb-3">
                <label for="title" class="form-label">{{ __('ui.reviewTitle') }}</label>
                <input type="text" class="form-control" id="title" name="title"
                    placeholder="{{__('ui.reviewTitlePlaceholder')}}" required>
            </div>

            <!-- Corpo della Recensione -->
            <div class="mb-3">
                <label for="body" class="form-label">{{ __('ui.reviewBody') }}</label>
                <textarea class="form-control" id="body" name="body" rows="5"
                    placeholder="{{ __('ui.reviewBodyPlaceholder') }}" required></textarea>
            </div>

            <!-- Valutazione -->
            <div class="mb-3">
                <label for="rating" class="form-label">{{ __('ui.rating') }}</label>
                <div class="star-rating">
                    <input type="radio" name="rating" id="star5" value="5">
                    <label for="star5" class="star">&#9733;</label>

                    <input type="radio" name="rating" id="star4" value="4">
                    <label for="star4" class="star">&#9733;</label>

                    <input type="radio" name="rating" id="star3" value="3">
                    <label for="star3" class="star">&#9733;</label>

                    <input type="radio" name="rating" id="star2" value="2">
                    <label for="star2" class="star">&#9733;</label>

                    <input type="radio" name="rating" id="star1" value="1">
                    <label for="star1" class="star">&#9733;</label>
                </div>
            </div>

            <!-- Pulsante di Invio -->
            <button type="submit" class="btn btn-primary">{{ __('ui.sendReview') }}</button>
        </form>
    </div>
    <div class="mb-5">
        <x-contact-link />
    </div>
</x-layout>
