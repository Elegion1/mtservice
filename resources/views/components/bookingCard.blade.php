<div class="card my-2">
    <div class="card-body">
        <button class="btn btn-sm open-details-modal text-start m-0 p-0" data-bs-toggle="modal"
            data-bs-target="#bookingDetailsModal" data-booking-data="{{ json_encode($booking) }}">
            <h5 class="card-title text-capitalize">{{ $booking->bookingData['type'] }}
                <i class="bi bi-info-circle text-primary"></i>
                @if ($booking->status == 'confirmed')
                    <i class="bi bi-check-circle-fill text-success"></i>
                @elseif ($booking->status == 'pending')
                    <i class="bi bi-exclamation-circle-fill text-warning"></i>
                @elseif ($booking->status == 'rejected')
                    <i class="bi bi-x-circle-fill text-danger"></i>
                @endif
                {{ $booking->name }}, {{ $booking->surname }}
            </button>
        </h5>
        <h6 class="card-subtitle mb-2 text-body-secondary"></h6>
        <p class="card-text">
            @if ($booking->bookingData['type'] == 'transfer')
                {{ \Carbon\Carbon::parse($booking->bookingData['date_dep'])->translatedFormat('d/m/Y H:i') }}
                <br>
                {{ $booking->bookingData['departure_name'] }} >>
                {{ $booking->bookingData['arrival_name'] }} <br>
                PAX: {{ $booking->bookingData['passengers'] }}
                @if ($booking->bookingData['date_ret'])
                    <br>
                    ritorno:
                    {{ \Carbon\Carbon::parse($booking->bookingData['date_ret'])->translatedFormat('d/m/Y H:i') }}
                @endif
            @elseif ($booking->bookingData['type'] == 'escursione')
                {{ $booking->bookingData['departure_name'] }},
                {{ \Carbon\Carbon::parse($booking->bookingData['date_dep'])->translatedFormat('d/m/Y H:i') }} <br>
                PAX: {{ $booking->bookingData['passengers'] }}
            @elseif ($booking->bookingData['type'] == 'noleggio')
                {{ \Carbon\Carbon::parse($booking->bookingData['date_start'])->translatedFormat('d/m/Y H:i') }}
                >>
                {{ \Carbon\Carbon::parse($booking->bookingData['date_end'])->translatedFormat('d/m/Y H:i') }}
                <br>
                {{ $booking->bookingData['car_name'] }}
            @endif
            <br>
            {{ $booking->body }}
        </p>
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex flex-column align-items-center justify-content-start">
                <a href="mailto:{{ $booking->email }}" class="card-link text-small">{{ $booking->email }}</a>
                <a href="tel:{{ $booking->phone }}" class="card-link text-small">{{ $booking->phone }}</a>
            </div>

            <span class="d-flex justify-content-center align-items-center">
                @if ($booking->status != 'confirmed')
                    <form action="{{ route('bookings.update', $booking) }}" method="POST"
                        style="display:inline-block;">
                        @csrf
                        <input type="hidden" name="status" value="confirmed">
                        <button title="Accetta prenotazione" type="submit" class="btn btn-sm">
                            <i class="bi bi-check-circle-fill text-success"></i>
                        </button>
                    </form>
                @endif

                @if ($booking->status != 'rejected')
                    <form action="{{ route('bookings.update', $booking) }}" method="POST"
                        style="display:inline-block;">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <button title="Rifiuta prenotazione" type="submit" class="btn btn-sm">
                            <i class="bi bi-x-circle-fill text-danger"></i>
                        </button>
                    </form>
                @endif

                @if ($booking->status != 'pending')
                    <form action="{{ route('bookings.update', $booking) }}" method="POST"
                        style="display:inline-block;">
                        @csrf
                        <input type="hidden" name="status" value="pending">
                        <button title="Sposta in lavorazione" type="submit" class="btn btn-sm">
                            <i class="bi bi-exclamation-circle-fill text-warning"></i>
                        </button>
                    </form>
                @endif
                
                {{-- <form action="{{ route('bookings.destroy', $booking) }}" method="POST"
                    style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn text-danger btn-sm">
                        <i class="bi bi-trash3-fill"></i>
                    </button>
                </form> --}}
            </span>
        </div>
    </div>
</div>
