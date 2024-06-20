<div class="container justify-content-center align-items-center d-flex">
    <div id="excursionCarousel" class="carousel excursion slide shadow rounded p-3" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach ($excursions as $index => $excursion)
                <button type="button" data-bs-target="#excursionCarousel" data-bs-slide-to="{{ $index }}"
                    class="bg-secondary {{ $index === 0 ? 'active' : '' }}" aria-current="true"
                    aria-label="Slide {{ $index }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @foreach ($excursions as $index => $excursion)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <h3>{{ $excursion->name }}</h3>
                            </div>
                            <div class="col-12">
                                <p class="text-secondary">{{ $excursion->abstract }}</p>
                            </div>
                            <div class="col-12">
                                <p>{{ $excursion->description }}</p>
                            </div>
                            {{-- <div class="col-12">
                                <a href="{{ route('excursions.show', $excursion->id) }}"
                                    class="btn btn-primary">Dettagli</a>
                            </div> --}}
                            <div class="col-12 d-flex justify-content-center align-items-center">
                                @if ($excursion->images->isNotEmpty())
                                    <div id="carouselImages{{ $excursion->id }}" class="carousel slide"
                                        data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach ($excursion->images as $imgIndex => $image)
                                                <div class="carousel-item {{ $imgIndex === 0 ? 'active' : '' }}">
                                                    <img src="{{ Storage::url($image->path) }}" class="d-block w-100"
                                                        alt="...">
                                                </div>
                                            @endforeach
                                        </div>
                                        <button class="carousel-control-prev" type="button"
                                            data-bs-target="#carouselImages{{ $excursion->id }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#carouselImages{{ $excursion->id }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                @else
                                    <img class="excursion-img rounded shadow" src="https://picsum.photos/401"
                                        alt="">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#excursionCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#excursionCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
