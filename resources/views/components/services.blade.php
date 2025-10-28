<div id="serviceCarousel" class="carousel service slide" data-bs-ride="carousel">
    {{-- <div class="carousel-indicators">
        @foreach ($services as $index => $service)
            <button type="button" data-bs-target="#serviceCarousel" data-bs-slide-to="{{ $index }}"
                class="carouselBtn {{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                aria-label="service-name-{{ $service->{'slug_' . app()->getLocale()} }}">
            </button>
        @endforeach
    </div> --}}
    <div class="carousel-inner">
        @foreach ($services as $index => $service)
            <div class="carousel-item service {{ $index === 0 ? 'active' : '' }}">
                <a class="text-reset text-decoration-none"
                    href="{{ route('service.show', ['slug' => $service->{'slug_' . app()->getLocale()}]) }}">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 my-3 d-flex justify-content-center align-items-center">
                                @if ($service->images->isNotEmpty())
                                    @foreach ($service->images as $image)
                                        <x-responsive-image loading="lazy" image="{{ $image->path }}"
                                            alt="img_{{ $service->title_en }}" class="service-img" />
                                    @endforeach
                                @else
                                    <x-responsive-image loading="lazy"
                                        image="https://picsum.photos/1920/108{{ $service->id }}" alt="placeholder"
                                        class="service-img" />
                                @endif
                            </div>
                            <div
                                class="col-12 d-flex justify-content-center align-items-center flex-column text-center">
                                <p class="fs-6 text-d text-uppercase">{!! $service->{'title_' . app()->getLocale()} !!}</p>
                                <p class="fs-6">{!! $service->{'subtitle_' . app()->getLocale()} !!}</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
    <x-carousel-navigation-arrows :carouselID="'serviceCarousel'" />
    {{-- <div class="indicators_space"></div> --}}
</div>
