<x-dashboard-layout>
    {{-- @dd($bookings) --}}
    <h1>Lista prenotazioni</h1>
    @foreach ($bookings as $booking)
        <div class="container text-small border mb-1">
            <p>Cliente: {{ $booking->name }} {{ $booking->surname }}</p>
            <p>Tipo: {{ ucfirst($booking->bookingData['type']) }}</p>
            @if ($booking->bookingData['type'] != 'noleggio')
                <p>Passeggeri: {{ $booking->bookingData['passengers'] }}</p>
            @endif
            @if ($booking->bookingData['type'] == 'transfer' || $booking->bookingData['type'] == 'escursione')
                <p>Data di partenza: {{ $booking->bookingData['date_departure'] }} ore
                    {{ $booking->bookingData['time_departure'] }}
                </p>
            @else
                <p>Data di inizio: {{ $booking->bookingData['date_start'] }}</p>
            @endif

            @if ($booking->bookingData['type'] != 'escursione')
                @if ($booking->bookingData['type'] == 'transfer' && !is_null($booking->bookingData['date_ret']))
                    <p>Data di fine:
                        {{ $booking->bookingData['date_return'] }} ore {{ $booking->bookingData['time_return'] }}
                    @elseif ($booking->bookingData['type'] == 'noleggio')
                    <p>Data di fine:
                        {{ $booking->bookingData['date_end'] }}
                @endif
                </p>
            @endif
            <p>Note: {{ $booking->body }}</p>
            <span>Contatti:</span>
            <a href="mailto:{{ $booking->email }}">{{ $booking->email }}</a>
            <a href="tel:{{ $booking->phone }}">{{ $booking->phone }}</a>
        </div>
    @endforeach
</x-dashboard-layout>
