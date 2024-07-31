<x-dashboard-layout>
    <div class="row">
        <div class="col-8">
            <h1>Lista prenotazioni</h1>
        </div>
        <div class="col-4">
            <button title="Prenotazione in corso" class="btn btn-success btn-sm text-small"><i
                    class="bi bi-calendar-event"></i></button> <small>In corso</small>
        </div>
    </div>
    <p class="text-small text-center">Oggi: {{ \Carbon\Carbon::now()->timezone('Europe/Rome')->format('d/m/Y') }}</p>
    <!-- Data di oggi -->
    @foreach ($bookings as $booking)
        @php
            $now = \Carbon\Carbon::now()->timezone('Europe/Rome');
            $startDate = \Carbon\Carbon::parse($booking->start_date)->timezone('Europe/Rome');
            $endDate = isset($booking->bookingData['date_end'])
                ? \Carbon\Carbon::parse($booking->bookingData['date_end'])->timezone('Europe/Rome')
                : null;
            $isInProgress = $startDate->lessThanOrEqualTo($now) && ($endDate && $endDate->greaterThanOrEqualTo($now));
        @endphp

        <div class="container text-small border mb-3">
            <div class="row mt-1">
                <div class="col-9">
                    <p>Cliente: {{ $booking->name }} {{ $booking->surname }}</p>
                </div>
                <div class="col-1">
                    @if ($isInProgress)
                        <button title="Prenotazione in corso" class="btn btn-success btn-sm text-small"><i
                                class="bi bi-calendar-event"></i></button>
                    @endif
                </div>
                <div class="col-1">
                    <button title="Elimina prenotazione" type="button" class="btn btn-danger btn-sm text-small"
                        data-bs-toggle="modal" data-bs-target="#deleteBookingModal{{ $booking->id }}"><i
                            class="bi bi-trash"></i></button>

                    <div class="modal fade" id="deleteBookingModal{{ $booking->id }}" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <p class="modal-title fs-5" id="exampleModalLabel">Vuoi eliminare questa
                                        prenotazione?</p>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-bs-dismiss="modal">Chiudi</button>
                                    <form action="{{ route('bookings.destroy', $booking) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm text-small">Elimina</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <p>Tipo: {{ ucfirst($booking->bookingData['type']) }}
                @if ($booking->bookingData['type'] == 'escursione')
                    a {{ $booking->bookingData['departure_name'] }}
                @endif
            </p>
            <p>
                @if ($booking->bookingData['type'] == 'transfer')
                    Da {{ $booking->bookingData['departure_name'] }} <br>
                    a {{ $booking->bookingData['arrival_name'] }}
                @elseif ($booking->bookingData['type'] == 'noleggio')
                    Auto: {{ $booking->bookingData['car_name'] }}
                @endif
            </p>
            @if ($booking->bookingData['type'] != 'noleggio')
                <p>Passeggeri: {{ $booking->bookingData['passengers'] }}</p>
            @endif
            @if ($booking->bookingData['type'] == 'transfer' || $booking->bookingData['type'] == 'escursione')
                <p>Andata:
                    {{ \Carbon\Carbon::parse($booking->bookingData['date_departure'])->timezone('Europe/Rome')->format('d/m/Y') }}
                    ore
                    {{ \Carbon\Carbon::parse($booking->bookingData['time_departure'])->timezone('Europe/Rome')->format('H:i') }}
                </p>
            @else
                <p>Data di inizio:
                    {{ \Carbon\Carbon::parse($booking->bookingData['date_start'])->timezone('Europe/Rome')->format('d/m/Y') }}
                </p>
            @endif

            @if ($booking->bookingData['type'] != 'escursione')
                @if ($booking->bookingData['type'] == 'transfer' && !is_null($booking->bookingData['date_ret']))
                    <p>Ritorno:
                        {{ \Carbon\Carbon::parse($booking->bookingData['date_return'])->timezone('Europe/Rome')->format('d/m/Y') }}
                        ore
                        {{ \Carbon\Carbon::parse($booking->bookingData['time_return'])->timezone('Europe/Rome')->format('H:i') }}
                    @elseif ($booking->bookingData['type'] == 'noleggio')
                    <p>Data di fine:
                        {{ \Carbon\Carbon::parse($booking->bookingData['date_end'])->timezone('Europe/Rome')->format('d/m/Y') }}
                @endif
                @if ($booking->bookingData['type'] == 'transfer' && $booking->bookingData['sola_andata'])
                    Sola andata
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
