<div id="excursionCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach ($excursions as $index => $excursion)
            <button aria-label="excursion-{{ $excursion->{"name_" . app()->getLocale()} }}" type="button" data-bs-target="#excursionCarousel" data-bs-slide-to="{{ $index }}"
                class="{{ $index === 0 ? 'active' : '' }} bg-a" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                aria-label="Slide {{ $index + 1 }}"></button>
        @endforeach
    </div>
    <div class="carousel-inner" id="carouselDynamic">

    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#excursionCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#excursionCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
    <div class="indicators_space"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateCarouselVisibility() {

            const carouselDynamic = document.querySelector('#carouselDynamic');

            if (window.innerWidth >= 768) {
                carouselDynamic.innerHTML = `@foreach ($excursions->chunk(3) as $index => $chunk)
                <div class="carousel-item excursion {{ $index === 0 ? 'active' : '' }}">
                    <div class="row d-flex justify-content-center">
                        @foreach ($chunk as $excursion)
                           <div class="col-md-4">
    <a class="text-reset text-decoration-none"
        href="{{ route('excursion.show', ['name' => $excursion->{'name_' . app()->getLocale()}, 'id' => $excursion->id]) }}">
        <div class="container">
            <div class="row">
                <div class="col-12 my-3 d-flex justify-content-center align-items-center position-relative">
                    @if ($excursion->images->isNotEmpty())
                        @foreach ($excursion->images as $image)
                            <x-responsive-image loading="lazy" image="{{ $image->path }}"
                                 alt="img_{{ $excursion->name_en }}" class="excursion-img"/>
                        @endforeach
                    @else
                        <x-responsive-image loading="lazy"  image="https://picsum.photos/20{{ $excursion->id }}/{{ $excursion->id + 100 }}"
                            alt="placeholder" class="excursion-img"/>
                    @endif
                    <div class="price-tag text-nowrap">
                    {{ __('ui.priceStartingFrom') }} <span class="text-b">€{{ $excursion->price }}</span>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-center align-items-center flex-column text-center">
                    <h3 class="fs-6 text-d text-uppercase">{!! $excursion->{'name_' . app()->getLocale()} !!}</h3>
                    <p class="text-wrap text-secondary-subtle">
                        {!! $excursion->{'abstract_' . app()->getLocale()} !!}
                    </p>
                </div>
            </div>
        </div>
    </a>
</div>
                        @endforeach
                    </div>
                </div>
            @endforeach`
            } else {
                carouselDynamic.innerHTML = `@foreach ($excursions as $index => $excursion)
                <div class="carousel-item excursion {{ $index === 0 ? 'active' : '' }}">
                    <a class="text-reset text-decoration-none"
        href="{{ route('excursion.show', ['name' => $excursion->{'name_' . app()->getLocale()}, 'id' => $excursion->id]) }}">
        <div class="container">
            <div class="row">
                <div class="col-12 my-3 d-flex justify-content-center align-items-center position-relative">
                    @if ($excursion->images->isNotEmpty())
                        @foreach ($excursion->images as $image)
                            <x-responsive-image loading="lazy" image="{{ $image->path }}"
                                 alt="img_{{ $excursion->name_en }}" class="excursion-img"/>
                        @endforeach
                    @else
                        <x-responsive-image loading="lazy"  image="https://picsum.photos/20{{ $excursion->id }}/{{ $excursion->id + 100 }}"
                            alt="placeholder" class="excursion-img"/>
                    @endif
                    <div class="price-tag text-nowrap">
                    {{ __('ui.priceStartingFrom') }} <span class="text-b">€{{ $excursion->price }}</span>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-center align-items-center flex-column text-center">
                    <h3 class="fs-6 text-d text-uppercase">{!! $excursion->{'name_' . app()->getLocale()} !!}</h3>
                    <p class="text-wrap text-secondary-subtle">
                        {!! $excursion->{'abstract_' . app()->getLocale()} !!}
                    </p>
                </div>
            </div>
        </div>
    </a>
                </div>
            @endforeach`
            }
        }

        // Initial check
        updateCarouselVisibility();

        // Update visibility on window resize
        window.addEventListener('resize', updateCarouselVisibility);
    });
</script>
