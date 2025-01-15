@foreach ($dayBookings as $booking)
    <div class="booking-item border p-3">
        <div class="row">
            <div class="col-3 d-flex flex-wrap justify-content-between align-items-center">
                <!-- Ora di partenza o di noleggio -->
                <span>
                    {{ \Carbon\Carbon::parse($booking->start_date ?? $booking->end_date)->format('H:i') }}
                </span>
                <span class="text-primary">
                    Codice: {{ $booking->code }}
                </span>
                <!-- Button trigger modal -->
            </div>
            <div class="col-9">
                <button class="btn open-details-modal p-0" data-bs-toggle="modal" data-bs-target="#bookingDetailsModal"
                    data-booking-data="{{ json_encode($booking->bookingData) }}" data-booking-id="{{ $booking->id }}"
                    data-booking-start="{{ $booking->start_date }}" data-booking-end="{{ $booking->end_date }}"
                    data-booking-name="{{ $booking->name }}" data-booking-surname="{{ $booking->surname }}"
                    data-booking-phone="{{ $booking->phone }}" data-booking-email="{{ $booking->email }}">
                    <p class="text-primary text-decoration-underline text-start mb-0 text-wrap">
                        {{ $booking->name }} {{ $booking->surname }} >>
                        {{ ucfirst($booking->bookingData['type']) }}
                        @if ($booking->bookingData['type'] == 'noleggio')
                            <strong>{{ $booking->bookingData['car_name'] }}</strong>
                            @if ($booking->start_date)
                                >> Ritiro
                            @elseif ($booking->end_date)
                                >> Consegna
                            @endif
                        @elseif ($booking->bookingData['type'] == 'escursione')
                            >> <strong>{{ $booking->bookingData['departure_name'] }}</strong> >>
                        @endif
                        @if ($booking->start_date && $booking->bookingData['type'] == 'transfer')
                            >> <strong>{{ $booking->bookingData['departure_name'] }}</strong>
                            >>
                            <strong>{{ $booking->bookingData['arrival_name'] }}</strong> >>
                        @endif
                        @if ($booking->end_date && $booking->bookingData['type'] == 'transfer')
                            >> <strong>{{ $booking->bookingData['arrival_name'] }}</strong>
                            >>
                            <strong>{{ $booking->bookingData['departure_name'] }}</strong> >>
                        @endif
                        @if ($booking->bookingData['type'] == 'transfer' || $booking->bookingData['type'] == 'escursione')
                            {{ $booking->bookingData['passengers'] }} <strong>PAX</strong>
                        @endif
                        >> {{ $booking->bookingData['price'] }} €
                    </p>
                </button>
            </div>
        </div>
    </div>
@endforeach
