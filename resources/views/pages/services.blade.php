<x-layout>
    <div class="container bg-white rounded mb-3">
        <div class="container p-3">
            <h1>{{ __('ui.servicesTitle') }}</h1>
            <p>{{ __('ui.servicesSubtitle') }}</p>
            <p>{{ __('ui.servicesBody') }}</p>
        </div>
        <div class="row">
            @foreach ($services as $index => $service)
                <div class="col-md-4 col-12">
                    <div class="service_custom">
                        <a class="text-reset text-decoration-none"
                            href="{{ route('service.show', ['title_it' => $service->title_it, 'id' => $service->id]) }}">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 my-3 d-flex justify-content-center align-items-center">
                                        @if ($service->images->isNotEmpty())
                                            @foreach ($service->images as $image)
                                                <img src="{{ Storage::url($image->path) }}" class="service-img_custom"
                                                    alt="img_{{ $service->title_en }}">
                                            @endforeach
                                        @else
                                            <img class="service-img_custom "
                                                src="https://picsum.photos/1920/108{{ $service->id }}"
                                                alt="placeholder">
                                        @endif
                                    </div>
                                    <div
                                        class="col-12 d-flex justify-content-center align-items-center flex-column text-center">
                                        <p class="h3 text-d">{{ $service->{'title_' . app()->getLocale()} }}</p>
                                        <p class="h6">{{ $service->{'subtitle_' . app()->getLocale()} }}</p>
                                        <p class=" text-wrap ">{{ $service->{'abstract_' . app()->getLocale()} }}</p>
                                        @if ($service->links)
                                            <a class="small" target="__blank"
                                                href="{{ $service->links }}">{{ __('ui.clickLink') }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
            <div class="ccol-12 my-3">
                <x-contact-link />
            </div>
            <div class="col-12 mt-5">
                <h2 class="text-center mb-3">{{ __('ui.title3') }}</h2>
                <x-excursions />
            </div>
        </div>
    </div>
</x-layout>
