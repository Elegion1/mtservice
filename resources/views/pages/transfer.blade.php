<x-layout>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="container bg-white rounded border_custom">
                <div class="container p-3 ">
                    <livewire:prenotazione />
                </div>
                <div class="container">
                    <x-services />
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="container p-3 bg-white rounded">
                <x-show-content :pagine="$pagine" />
            </div>
        </div>
    </div>
</x-layout>
