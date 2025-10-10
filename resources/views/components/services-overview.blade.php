@foreach ($services as $service)
    @if ($service->flag)
        <div class="service">
            <a class="text-reset text-decoration-none"
                href="{{ route('service.show', ['slug' => $service->{'slug_' . app()->getLocale()}]) }}">
                <div class="container">
                    <div class="row">
                        <div class="col-12 my-3 d-flex justify-content-center align-items-center">
                            @if ($service->images->isNotEmpty())
                                @foreach ($service->images as $image)
                                    <img loading="lazy" src="{{ Storage::url($image->path) }}" class="service-img rounded"
                                        alt="img_{{ $service->title_en }}">
                                @endforeach
                            @else
                                <img loading="lazy" class="service-img rounded" src="https://picsum.photos/1920/108{{ $service->id }}"
                                    alt="placeholder">
                            @endif
                        </div>
                        <div class="col-12 d-flex justify-content-center align-items-center flex-column text-center">
                            <p class="h5 fs-6 text-d text-uppercase">{!! $service->{'title_' . app()->getLocale()} !!}</p>
                            <p class="h6">{!! $service->{'subtitle_' . app()->getLocale()} !!}</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif
@endforeach
