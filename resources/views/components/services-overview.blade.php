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
                                    <x-responsive-image loading="lazy" image="{{ $image->path }}" 
                                        alt="img_{{ $service->title_en }}" class="service-img rounded"/>
                                @endforeach
                            @else
                                <x-responsive-image loading="lazy"  image="https://picsum.photos/1920/108{{ $service->id }}"
                                    alt="placeholder" class="service-img rounded"/>
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
