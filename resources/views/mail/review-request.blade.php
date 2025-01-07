<x-mail-layout>
    <p>
        {{ __('ui.dear') }}, {{ $booking->name ?? 'Cliente' }}
    </p>
    <p>
        {{ __('ui.reviewRequestMailMessage') }}
    </p>
    <a href="{{ route('reviews.create', ['locale' => $booking->locale, 'booking_code' => $booking->code]) }}">
        {{ __('ui.clickHere') }}
    </a>
</x-mail-layout>
