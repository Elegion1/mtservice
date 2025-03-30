<div class="row">
    @php
        $prefix = 'driver';
        $fields = [
            ['name', 'text', 'col-12'],
            ['birthDate', 'date', 'col-6 col-md-6', null, date('Y-m-d', strtotime('-18 years'))],
            ['birthPlace', 'text', 'col-6 col-md-6'],
            ['address', 'text', 'col-12 col-md-6'],
            ['city', 'text', 'col-12 col-md-6'],
            ['postalCode', 'text', 'col-6 col-md-6'],
            ['country', 'text', 'col-6 col-md-6'],
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
