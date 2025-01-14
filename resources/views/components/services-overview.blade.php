@foreach ($services as $service)
    @if ($service->show && $service->flag)
        <div class="service">
            <a class="text-reset text-decoration-none"
                href="{{ route('service.show', ['title' => $service->{'title_' . app()->getLocale()}, 'id' => $service->id]) }}">
                <div class="container">
                    <div class="row">
                        <div class="col-12 my-3 d-flex justify-content-center align-items-center">
                            @if ($service->images->isNotEmpty())
                                @foreach ($service->images as $image)
                                    <img src="{{ Storage::url($image->path) }}" class="service-img rounded"
                                        alt="img_{{ $service->title_en }}">
                                @endforeach
                            @else
                                <img class="service-img rounded" src="https://picsum.photos/1920/108{{ $service->id }}"
                                    alt="placeholder">
                            @endif
                        </div>
                        <div class="col-12 d-flex justify-content-center align-items-center flex-column text-center">
                            <p class="h5 fs-6 text-d text-uppercase">{!! $service->{'title_' . app()->getLocale()} !!}</p>
                            <p class="h6">{!! $service->{'subtitle_' . app()->getLocale()} !!}</p>
                            @if ($service->links)
                                <a class="small" target="__blank"
                                    href="{{ $service->links }}">{{ __('ui.clickLink') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif
@endforeach
