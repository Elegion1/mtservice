<div class="row">
    @php
        $prefix = 'driver';
        $fields = [
            ['licenseNumber', 'text', 'col-12 col-md-6'],
            ['licenseType', 'text', 'col-12 col-md-6'],
            [
                'licenseIssueDate',
                'date',
                'col-6 col-md-6',
                date('Y-m-d', strtotime('-80 years')),
                date('Y-m-d', strtotime('-1 days')),
            ],
            ['licenseExpirationDate', 'date', 'col-6 col-md-6', date('Y-m-d', strtotime($bookingData['date_end']))],
            ['licenseCountry', 'text', 'col-6 col-md-6'],
            ['licenseProvince', 'text', 'col-6 col-md-6'],
        ];
    @endphp

    @foreach ($fields as $field)
        @php
            $fieldId = $prefix . ucfirst($field[0]);
            $translationKey = "ui.$fieldId";
        @endphp

        <x-input-comp :field="$field" :field-id="$fieldId" :translation-key="$translationKey" :error="true" :required="true" />
    @endforeach
</div>
