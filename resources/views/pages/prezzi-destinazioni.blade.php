<x-layout>
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="container bg-white rounded p-3">
                <x-show-content :pagine="$pagine" />
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="container bg-white rounded  p-3">
                <x-lista-tratte />
            </div>
        </div>
    </div>


</x-layout>
