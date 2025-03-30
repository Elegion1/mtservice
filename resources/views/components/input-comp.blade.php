<div class="{{ $field[2] }}">
    <label for="{{ $fieldId }}" class="form-label">
        {{ __($translationKey) }}
        @if ($required)
            <x-required-field />
        @endif
    </label>
    <input required type="{{ $field[1] }}" class="form-control form_input" id="{{ $fieldId }}"
        wire:model="{{ $fieldId }}" placeholder="{{ __($translationKey . 'Placeholder') }}"
        @if (isset($field[3])) min="{{ $field[3] }}" @endif
        @if (isset($field[4])) max="{{ $field[4] }}" @endif>
    @if ($error)
        <x-error-message field="{{ $fieldId }}" />
    @endif
</div>
