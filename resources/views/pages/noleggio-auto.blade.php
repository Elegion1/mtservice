<x-layout>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="container bg-white rounded p-3">
                <x-show-content :pagine="$pagine" />
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="container bg-white rounded p-3 border_custom">
                <livewire:prenotazione />
            </div>
        </div>
        <div class="col-12">
            <x-contact-link />
        </div>
    </div>
</x-layout>
