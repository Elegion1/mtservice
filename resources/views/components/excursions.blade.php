{{-- carosello responsive --}}

<div id="excursionCarousel" class="carousel slide p-3" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach ($excursions as $index => $excursion)
            <button type="button" data-bs-target="#excursionCarousel" data-bs-slide-to="{{ $index }}"
                class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                aria-label="Slide {{ $index + 1 }}"></button>
        @endforeach
    </div>
    <div class="carousel-inner" id="carouselDynamic">
        <!-- For medium and large screens -->
        {{-- <div class="carousel-inner-large">
            @foreach ($excursions->chunk(3) as $index => $chunk)
                <div class="carousel-item excursion {{ $index === 0 ? 'active' : '' }}">
                    <div class="row d-flex justify-content-center">
                        @foreach ($chunk as $excursion)
                            <div class="col-md-4">
                                <a class="text-reset text-decoration-none"
                                    href="{{ route('excursion.show', ['id' => $excursion->id]) }}">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12 my-3 d-flex justify-content-center align-items-center">
                                                @if ($excursion->images->isNotEmpty())
                                                    @foreach ($excursion->images as $image)
                                                        <img src="{{ Storage::url($image->path) }}"
                                                            class="excursion-img" alt="...">
                                                    @endforeach
                                                @else
                                                    <img class="excursion-img" src="https://picsum.photos/1920/1081"
                                                        alt="">
                                                @endif
                                            </div>
                                            <div
                                                class="col-12 d-flex justify-content-center align-items-center flex-column text-center">
                                                <p class="h3 text-d">{{ $excursion->{'name_' . app()->getLocale()} }}
                                                </p>
                                                <p class="text-wrap text-secondary-subtle">
                                                    {{ $excursion->{'abstract_' . app()->getLocale()} }}</p>
                                                <p class="text-wrap">
                                                    {{ $excursion->{'description_' . app()->getLocale()} }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div> --}}
        <!-- For small screens -->
        {{-- <div class="carousel-inner-small">
            @foreach ($excursions as $index => $excursion)
                <div class="carousel-item excursion {{ $index === 0 ? 'active' : '' }}">
                    <a class="text-reset text-decoration-none"
                        href="{{ route('excursion.show', ['id' => $excursion->id]) }}">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 my-3 d-flex justify-content-center align-items-center">
                                    @if ($excursion->images->isNotEmpty())
                                        @foreach ($excursion->images as $image)
                                            <img src="{{ Storage::url($image->path) }}" class="excursion-img"
                                                alt="...">
                                        @endforeach
                                    @else
                                        <img class="excursion-img" src="https://picsum.photos/1920/1081" alt="">
                                    @endif
                                </div>
                                <div
                                    class="col-12 d-flex justify-content-center align-items-center flex-column text-center">
                                    <p class="h3 text-d">{{ $excursion->{'name_' . app()->getLocale()} }}</p>
                                    <p class="text-wrap text-secondary-subtle">
                                        {{ $excursion->{'abstract_' . app()->getLocale()} }}</p>
                                    <p class="text-wrap">{{ $excursion->{'description_' . app()->getLocale()} }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div> --}}
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
            // const largeScreenCarousel = document.querySelector('.carousel-inner-large');
            // const smallScreenCarousel = document.querySelector('.carousel-inner-small');

            const carouselDynamic = document.querySelector('#carouselDynamic');

            // if (window.innerWidth >= 768) {
            //     largeScreenCarousel.style.display = 'block';
            //     smallScreenCarousel.style.display = 'none';
            // } else {
            //     largeScreenCarousel.style.display = 'none';
            //     smallScreenCarousel.style.display = 'block';
            // }

            if (window.innerWidth >= 768) {
                carouselDynamic.innerHTML = `@foreach ($excursions->chunk(3) as $index => $chunk)
                <div class="carousel-item excursion {{ $index === 0 ? 'active' : '' }}">
                    <div class="row d-flex justify-content-center">
                        @foreach ($chunk as $excursion)
                            <div class="col-md-4">
                                <a class="text-reset text-decoration-none"
                                    href="{{ route('excursion.show', ['name_it' => $excursion->name_it, 'id' => $excursion->id]) }}">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12 my-3 d-flex justify-content-center align-items-center">
                                                @if ($excursion->images->isNotEmpty())
                                                    @foreach ($excursion->images as $image)
                                                        <img src="{{ Storage::url($image->path) }}"
                                                            class="excursion-img" alt="img_{{$excursion->name_en}}">
                                                    @endforeach
                                                @else
                                                    <img class="excursion-img" src="https://picsum.photos/1920/108{{ $excursion->id }}"
                                                        alt="placeholder">
                                                @endif
                                            </div>
                                            <div
                                                class="col-12 d-flex justify-content-center align-items-center flex-column text-center">
                                                <p class="h3 text-d">{{ $excursion->{'name_' . app()->getLocale()} }}
                                                </p>
                                                <p class="text-wrap text-secondary-subtle">
                                                    {{ $excursion->{'abstract_' . app()->getLocale()} }}</p>
                                                
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
                        href="{{ route('excursion.show', ['name_it' => $excursion->name_it, 'id' => $excursion->id]) }}">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 my-3 d-flex justify-content-center align-items-center">
                                    @if ($excursion->images->isNotEmpty())
                                        @foreach ($excursion->images as $image)
                                            <img src="{{ Storage::url($image->path) }}" class="excursion-img"
                                                alt="img_{{$excursion->name_en}}">
                                        @endforeach
                                    @else
                                        <img class="excursion-img" src="https://picsum.photos/1920/108{{ $excursion->id }}" alt="placeholder">
                                    @endif
                                </div>
                                <div
                                    class="col-12 d-flex justify-content-center align-items-center flex-column text-center">
                                    <p class="h3 text-d">{{ $excursion->{'name_' . app()->getLocale()} }}</p>
                                    <p class="text-wrap text-secondary-subtle">
                                        {{ $excursion->{'abstract_' . app()->getLocale()} }}</p>
                                    
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
