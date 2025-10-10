<x-layout>
    <div class="container mb-3">
        <div class="container p-3">
            <h2>{{ __('ui.servicesTitle') }}</h2>
            <p>{{ __('ui.servicesSubtitle') }}</p>
            <p>{{ __('ui.servicesBody') }}</p>
        </div>
        <div class="row">
            @foreach ($services as $service)
                @php
                    $locale = app()->getLocale();
                    $title = $service->{'title_' . $locale};
                    $subtitle = $service->{'subtitle_' . $locale};
                    $imagePath = $service->images->isNotEmpty()
                        ? Storage::url($service->images->first()->path)
                        : "https://picsum.photos/1920/108{$service->id}";
                    $slug = $service->{'slug_' . $locale};
                @endphp
                <div class="col-md-4 col-12">
                    <div class="service_custom">
                        <a class="text-reset text-decoration-none" href="{{ route('service.show', ['title' => $slug]) }}">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 my-3 d-flex justify-content-center align-items-center">
                                        <img src="{{ $imagePath }}" class="service-img_custom"
                                            alt="img_{{ $service->{'slug_' . app()->getLocale()} }}">
                                    </div>
                                    <div
                                        class="col-12 d-flex justify-content-center align-items-center flex-column text-center">
                                        <p class="h3 text-d">{{ $title }}</p>
                                        <p class="h6">{{ $subtitle }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
            <div class="col-12 mb-3 mt-5">
                <x-contact-link />
            </div>
            <div class="col-12 mt-5">
                <h2 class="text-center mb-3">{{ __('ui.title3') }}</h2>
                <x-excursions />
            </div>
        </div>
    </div>
</x-layout>
