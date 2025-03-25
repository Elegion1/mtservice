<div class="row">
    @if (isset($booking))
        <div class="col-md-6 col-12">
            @if (isset($booking->status))
                <p>{{ __('ui.status') }}: {!! __('ui.' . $booking->status) !!}</p>
                <p>{{ __('ui.paymentStatus') }}: {!! __('ui.pay' . $booking->payment_status) !!}</p>
            @endif
            <p>{{ __('ui.bookingID') }}: {{ $booking->code }}</p>
            <p>{{ __('ui.name') }}: {{ $booking->name }} {{ $booking->surname }}</p>
            <p>{{ __('ui.email') }}: {{ $booking->email }}</p>
            <p>{{ __('ui.phone') }}: {{ $booking->dial_code }} {{ $booking->phone }}</p>
            <p>{{ __('ui.body') }}: {{ $booking->body }}</p>
        </div>
    @endif
    <div class="col-md-6 col-12">
        @if ($admin)
            <p>PRENOTAZIONE SITO FAVIGNANA</p>
        @endif
        @if ($bookingData['type'] == 'transfer')
            <p>{{ __('ui.bookingType') }}: <span class="text_col">{{ ucfirst($bookingData['type']) }}</span>
            </p>
            <p>{{ __('ui.from') }}: <span class="text_col">{{ $bookingData['departure_name'] ?? 'N/A' }}</span>
                {{ __('ui.to') }}: <span class="text_col">{{ $bookingData['arrival_name'] ?? 'N/A' }}</span>
            </p>
            <p>{{ __('ui.date') }}: <span
                    class="text_col">{{ \Carbon\Carbon::parse($bookingData['date_dep'])->translatedFormat('d/m/Y H:i') ?? 'N/A' }}</span>
            </p>
            <p>{{ __('ui.duration') }}: <span class="text_col">{{ $bookingData['duration'] ?? 'N/A' }}</span>
                {{ __('ui.minutes') }} {{ __('ui.approx') }}</p>
            @if (!empty($bookingData['date_ret']))
                <p>{{ __('ui.return') }}: <span
                        class="text_col">{{ \Carbon\Carbon::parse($bookingData['date_ret'])->translatedFormat('d/m/Y H:i') }}</span>
                </p>
            @endif
            <p>{{ __('ui.passengers') }}: <span class="text_col">{{ $bookingData['passengers'] ?? 'N/A' }}</span>
            </p>
        @elseif ($bookingData['type'] == 'escursione')
            <p>{{ __('ui.bookingType') }}: <span class="text_col">{{ ucfirst($bookingData['type']) }}</span>
                a
                <span class="text_col">{{ $bookingData['departure_name'] ?? 'N/A' }}</span>
            </p>
            <p>{{ __('ui.date') }}: <span
                    class="text_col">{{ \Carbon\Carbon::parse($bookingData['date_dep'])->translatedFormat('d/m/Y H:i') ?? 'N/A' }}</span>
            </p>

            <p>{{ __('ui.duration') }}: <span class="text_col">{{ $bookingData['duration'] ?? 'N/A' }}</span>
                {{ __('ui.hours') }} {{ __('ui.approx') }}</p>
            <p>{{ __('ui.passengers') }}: <span class="text_col">{{ $bookingData['passengers'] ?? 'N/A' }}</span>
            </p>
        @elseif ($bookingData['type'] == 'noleggio')
            <p>{{ __('ui.bookingType') }}: <span class="text_col">{{ ucfirst($bookingData['type']) }}</span>
                <span class="text_col">{{ $bookingData['car_name'] ?? 'N/A' }}
                    {{ $bookingData['car_description'] ?? 'N/A' }}</span>
            </p>
            <p>{{ __('ui.collectionDate') }}: <span
                    class="text_col">{{ \Carbon\Carbon::parse($bookingData['date_start'])->translatedFormat('d/m/Y H:i') ?? 'N/A' }}</span>
                {{ __('ui.returnDate') }}:
                <span
                    class="text_col">{{ \Carbon\Carbon::parse($bookingData['date_end'])->translatedFormat('d/m/Y H:i') ?? 'N/A' }}</span>
            </p>
            <p>{{ __('ui.quantity') }}: <span class="text_col">{{ $bookingData['quantity'] ?? 'N/A' }}</span>
            </p>
        @endif

        @if (isset($bookingData['price']))
            <p class="text-capitalize">{{ __('ui.price') }}: <span class="text_col">{{ $bookingData['price'] }}
                    €</span></p>
        @endif

    </div>
</div>
