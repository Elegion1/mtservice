<x-mail-layout>
    <p style="Margin:0;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:32px !important;letter-spacing:0;color:#333333;font-size:16px"
        class="es-text-mobile-size-16">Una nuova
        prenotazione è disponibile nella dashboard</p>

    <p style="Margin:0;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:32px !important;letter-spacing:0;color:#333333;font-size:16px"
        class="es-text-mobile-size-16">
    <div style="font-family: Arial, sans-serif; padding: 10px;">

        <x-booking-show :booking="$booking" :bookingData="$booking->bookingData" :admin="$booking->bookingData['sito_favignana'] ?? null" />
    </div>
    ​</p>

    <div style="Margin:5">

        {{-- <!-- Confirmation Button with GET Method -->
        <a href="{{ route('booking.confirm', ['booking' => $booking->id]) }}"
            style="background-color: green; color: white; padding: 10px; border: none; border-radius: 5px;">Conferma</a>

        <!-- Rejection Button with GET Method -->
        <a href="{{ route('booking.reject', ['booking' => $booking->id]) }}"
            style="background-color: red; color: white; padding: 10px; border: none; border-radius: 5px;">Rifiuta</a> --}}

        <a href="{{ route('booking.todo') }}"
            style="background-color: blue; color: white; padding: 10px; border: none; border-radius: 5px;">Vai alle
            prenotazioni</a>
    </div>
</x-mail-layout>
