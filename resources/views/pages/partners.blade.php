<x-layout>
    <x-seo-data :seoTitle="$seoTitle" :seoDescription="$seoDescription" />
    <div id="partners" class="container rounded mt-5 mt-md-3">
        <div class="container">
            <x-show-content :pagine="$pagine" />
            <div class="row justify-content-center align-items-center">
                @foreach ($partners as $partner)
                    <a id="partnerCard" class="col-12 col-md-6 col-lg-4 text-decoration-none text-black" target="_blank"
                        href="{{ $partner->link }}">
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            @if ($partner->images->count() > 0)
                                <x-responsive-image loading="lazy" image="{{ $partner->images->first()->path }}"
                                    alt="{{ $partner->name }}img" class="shadow rounded img-partner" />
                            @else
                                <x-responsive-image loading="lazy"
                                    image="https://picsum.photos/{{ $partner->id }}00/{{ $partner->id + 150 }}"
                                    class="shadow rounded" alt="" />
                            @endif
                            <p class="text-center text-wrap mt-3">{{ $partner->name }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="d-flex justify-content-center align-items-center mt-4">
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
