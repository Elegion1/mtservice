<x-layout>
    <div id="partners" class="container bg-white rounded">
        <div class="container p-3">
            <x-show-content :pagine="$pagine" />
            <div class="d-flex justify-content-center align-items-end flex-wrap">
                @foreach ($partners as $partner)
                    <a class="text-decoration-none text-black m-3" target="_blank" href="{{ $partner->link }}">
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            @if ($partner->images->count() > 0)
                                <img class="shadow rounded" width="200px"
                                    src="{{ Storage::url($partner->images->first()->path) }}"
                                    alt="{{ $partner->name }}img">
                            @else
                                <img class="shadow rounded" width="200px"
                                    src="https://picsum.photos/{{ $partner->id }}00/{{ $partner->id + 150 }}"
                                    alt="">
                            @endif
                            <p class="text-center text-wrap m-3">{{ $partner->name }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4">
                {{ $partners->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="container my-5">
                <x-contact-link />
            </div>
        </div>
        <div class="col-12 mt-5">
            <h2 class="text-center">{{ __('ui.title2') }}</h2>
            <x-services />
        </div>
        <div class="col-12 mt-5">
            <h2 class="text-center mb-3">{{ __('ui.title3') }}</h2>
            <x-excursions />
        </div>
    </div>

</x-layout>
