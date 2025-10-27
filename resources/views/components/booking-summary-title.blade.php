<div class="d-flex justify-content-between p-0">
    <h6 class="text-uppercase p-0">{{ __('ui.' . $slot) }}</h6>
    @if ($isRequired)
        <span class="text-danger text-nowrap"><x-required-field >{{ __('ui.obligatoryField') }}</span>
    @endif
</div>
