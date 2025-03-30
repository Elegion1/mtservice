<div class="row mb-3">
    @php
        $fields = [
            ['flightNumber', 'text', 'col-12'],
            ['departureAirport', 'text', 'col-6'],
            ['departureTime', 'time', 'col-6'],
            ['arrivalAirport', 'text', 'col-6'],
            ['arrivalTime', 'time', 'col-6'],
        ];
    @endphp

    @foreach ($fields as $field)
        @php
            $fieldId = $field[0];
            $translationKey = "ui.$fieldId";
        @endphp

        <x-input-comp :field="$field" :field-id="$fieldId" :translation-key="$translationKey" :error="false" :required="false" />
    @endforeach
</div>
