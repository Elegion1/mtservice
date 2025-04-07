<div class="card my-2">
    <div class="card-body">
        <button class="btn btn-sm open-details-modal text-start m-0 p-0" data-bs-toggle="modal"
            data-bs-target="#bookingDetailsModal" data-booking-data="{{ json_encode($booking) }}">
            <p class="h6 card-text text-capitalize">{{ $booking->bookingData['type'] }}
                <i class="bi bi-info-circle text-primary"></i>
                <x-status :status="$booking->status" /><br>
                {{ $booking->name }}, {{ $booking->surname }}
            </p>
        </button>
        <p class="mb-0">
            Pagamento: @if ($booking->payment_status == 'pending')
                In attesa
            @elseif ($booking->payment_status == 'deposit_paid')
                Acconto pagato
            @elseif ($booking->payment_status == 'paid')
                Pagato
            @endif
        </p>
        <p class="card-text ">

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
            Note: {{ $booking->body }}
        </p>

        <div class="d-flex justify-content-between align-items-center">

            <div class="d-flex flex-column align-items-start justify-content-center">
                <a href="tel:{{ $booking->dial_code }}{{ $booking->phone }}"
                    class="text-small">{{ $booking->dial_code }} {{ $booking->phone }}</a>
                <a href="mailto:{{ $booking->email }}" class="text-small">{{ $booking->email }}</a>
            </div>

            @php
                $statuses = [
                    'confirmed' => [
                        'title' => 'Accetta prenotazione',
                        'icon' => 'bi-check-circle-fill',
                        'class' => 'text-success',
                    ],
                    'rejected' => [
                        'title' => 'Rifiuta prenotazione',
                        'icon' => 'bi-x-circle-fill',
                        'class' => 'text-danger',
                    ],
                    'pending' => [
                        'title' => 'Sposta in lavorazione',
                        'icon' => 'bi-exclamation-circle-fill',
                        'class' => 'text-warning',
                    ],
                ];
            @endphp

            <span class="d-flex justify-content-center align-items-center">
                @foreach ($statuses as $status => $info)
                    @if ($booking->status !== $status)
                        <form action="{{ route('bookings.update', $booking->id) }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="{{ $status }}">
                            <button title="{{ $info['title'] }}" type="submit" class="btn btn-sm">
                                <i class="bi {{ $info['icon'] }} {{ $info['class'] }} fs-1"></i>
                            </button>
                        </form>
                    @endif
                @endforeach
            </span>

        </div>
    </div>
</div>
