<div class="container justify-content-center align-items-center d-flex">
    <div id="serviceCarousel" class="carousel service slide shadow rounded p-3" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach ($services as $index => $service)
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $service->id }}"
                    class=" bg-secondary {{ $index === 0 ? 'active' : '' }}" aria-current="true"
                    aria-label="Slide {{ $service->id }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @foreach ($services as $index => $service)
                <div class="carousel-item service {{ $index === 0 ? 'active' : '' }}">
                    <a class="text-reset text-decoration-none"
                        href="{{ route('service.show', ['id' => $service->id]) }}">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <p class="h3">{{ $service->title }}</p>
                                </div>
                                <div class="col-12">
                                    <p class="h6">{{ $service->subtitle }}</p>
                                </div>
                                {{-- <div class="col-12">
                                <p class="h6">{{ $service->subtitleSec }}</p>
                            </div> --}}
                                <div class="col-12">
                                    <p class="text-secondary">{{ $service->abstract }}</p>
                                </div>
                                {{-- <div class="col-12">
                                <p>{{ $service->body }}</p>
                            </div> --}}
                                <div class="col-12">
                                    <a class="small" href="{{ $service->links }}">{{ $service->links }}</a>
                                </div>
                                {{-- <div class="col-12">
                                <p class="small">{{ $service->condition }}</p>
                            </div> --}}
                                <div class="container justify-content-center align-items-center">
                                    @if ($service->images->isNotEmpty())
                                        <div id="carouselImages{{ $service->id }}" class="carousel slide"
                                            data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                @foreach ($service->images as $imgIndex => $image)
                                                    <div class="carousel-item {{ $imgIndex === 0 ? 'active' : '' }}">
                                                        <img src="{{ Storage::url($image->path) }}"
                                                            class="d-block w-100" alt="...">
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button class="carousel-control-prev" type="button"
                                                data-bs-target="#carouselImages{{ $service->id }}"
                                                data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button"
                                                data-bs-target="#carouselImages{{ $service->id }}"
                                                data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                    @else
                                        <div
                                            class="container-fluid my-2 justify-content-center align-items-center d-flex">
                                            <img class="service-img rounded shadow" src="https://picsum.photos/400"
                                                alt="">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#serviceCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#serviceCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

</div>
