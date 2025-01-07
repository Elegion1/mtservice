<x-mail-layout>

    <p style="Margin:0;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:32px !important;letter-spacing:0;color:#333333;font-size:16px"
        class="es-text-mobile-size-16">
        {{ __('ui.dearCustomer') }},
    </p>
    <p style="Margin:0;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:32px !important;letter-spacing:0;color:#333333;font-size:16px"
        class="es-text-mobile-size-16">​</p>
    <p style="Margin:0;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:32px !important;letter-spacing:0;color:#333333;font-size:16px"
        class="es-text-mobile-size-16">
        {!! __('ui.bookingStatusUpdate', ['status' => __('ui.' . $booking->status)]) !!}.
    </p>
    <p style="Margin:0;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:32px !important;letter-spacing:0;color:#333333;font-size:16px"
        class="es-text-mobile-size-16">​</p>
    <p style="Margin:0;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:32px !important;letter-spacing:0;color:#333333;font-size:16px"
        class="es-text-mobile-size-16">
        {{ __('ui.thxForChoosing') }}
    </p>
    <p style="Margin:0;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:21px;letter-spacing:0;color:#333333;font-size:14px"
        class="es-text-mobile-size-14">
        {{ __('ui.bookingStatusMessage') }} <a
            href="{{ route('booking.status', ['booking' => $booking, 'locale' => $booking->locale]) }}"
            style="color:#1d68b7;">{{ __('ui.clickHere') }}</a>​
    </p>

</x-mail-layout>
