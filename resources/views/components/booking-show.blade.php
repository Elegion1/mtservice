<div class="row">
    @if (isset($booking))
        <div class="col-md-6 col-12 mb-3 mb-md-0">
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

        @php
            $type = $bookingData['type'];
            $fieldsMap = [
                'transfer' => [
                    ['label' => 'bookingType', 'value' => ucfirst($type)],
                    ['label' => 'from', 'value' => $bookingData['departure_name'] ?? 'N/A'],
                    ['label' => 'to', 'value' => $bookingData['arrival_name'] ?? 'N/A'],
                    ['label' => 'date', 'value' => $bookingData['date_dep'] ?? null, 'type' => 'date'],
                    [
                        'label' => 'duration',
                        'value' => ($bookingData['duration'] ?? 'N/A') . ' ' . __('ui.minutes') . ' ' . __('ui.approx'),
                    ],
                    [
                        'label' => 'return',
                        'value' => $bookingData['date_ret'] ?? null,
                        'type' => 'date',
                        'optional' => true,
                    ],
                    ['label' => 'passengers', 'value' => $bookingData['passengers'] ?? 'N/A'],
                ],
                'escursione' => [
                    [
                        'label' => 'bookingType',
                        'value' => ucfirst($type) . ' a ' . ($bookingData['departure_name'] ?? 'N/A'),
                    ],
                    ['label' => 'date', 'value' => $bookingData['date_dep'] ?? null, 'type' => 'date'],
                    [
                        'label' => 'duration',
                        'value' => ($bookingData['duration'] ?? 'N/A') . ' ' . __('ui.hours') . ' ' . __('ui.approx'),
                    ],
                    ['label' => 'passengers', 'value' => $bookingData['passengers'] ?? 'N/A'],
                ],
                'noleggio' => [
                    [
                        'label' => 'bookingType',
                        'value' =>
                            ucfirst($type) .
                            ' ' .
                            ($bookingData['car_name'] ?? 'N/A') .
                            ' ' .
                            ($bookingData['car_description'] ?? 'N/A'),
                    ],
                    ['label' => 'collectionDate', 'value' => $bookingData['date_start'] ?? null, 'type' => 'date'],
                    ['label' => 'pickupLocation', 'value' => $bookingData['pickup'] ?? 'N/A'],
                    ['label' => 'returnDate', 'value' => $bookingData['date_end'] ?? null, 'type' => 'date'],
                    ['label' => 'deliveryLocation', 'value' => $bookingData['delivery'] ?? 'N/A'],
                    // ['label' => 'quantity', 'value' => $bookingData['quantity'] ?? 'N/A'],
                ],
            ];
        @endphp

        @foreach ($fieldsMap[$type] as $field)
            @php
                $label = ucfirst(__('ui.' . $field['label']));
                $value = $field['value'] ?? 'N/A';
            @endphp
            @if (!empty($value) || empty($field['optional']))
                <p>{{ $label }}:
                    <span class="text_col">
                        @if (($field['type'] ?? '') === 'date')
                            {{ \Carbon\Carbon::parse($value)->translatedFormat('d/m/Y H:i') }}
                        @else
                            {{ $value }}
                        @endif
                    </span>
                </p>
            @endif
        @endforeach

        @if (isset($bookingData['price']))
            <p class="text-capitalize">{{ __('ui.price') }}: <span class="text_col">{{ $bookingData['price'] }} €</span>
            </p>
        @endif

    </div>

    @if (isset($booking->info))
        @php $data = json_decode($booking->info, true); @endphp
        <div class="row">
            @foreach ($data as $section => $fields)
                <div class="col-12 col-md-6">
                    <h3 class="mt-4 text-uppercase">{{ __('ui.info') }} {{ __('ui.' . $section) }}</h3>
                    <ul class="list-group mb-4">
                        @foreach ($fields as $key => $value)
                            <li class="list-group-item">
                                <strong>{{ __('ui.' . $key) }}:</strong> {{ $value }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    @endif
</div>
