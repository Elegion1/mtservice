<x-layout>
    <div class="container-fluid bg-white rounded p-3">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="container-fluid justify-content-center d-flex flex-column">
                    <p class="h2">{{ $service->{'title_' . app()->getLocale()} }}</p>
                    <p class="h5">{{ $service->{'subtitle_' . app()->getLocale()} }}</p>
                    <p>{{ $service->{'subtitleSec_' . app()->getLocale()} }}</p>
                    <p class="text-secondary small">{{ $service->{'abstract_' . app()->getLocale()} }}</p>
                    <p>{!! $service->{'body_' . app()->getLocale()} !!}</p>
                    <p class="small">{!! $service->{'condition_' . app()->getLocale()} !!}</p>
                    @if ($service->links)
                        <a class="small" target="__blank" href="{{ $service->links }}">{{__('ui.clickLink')}}</a>
                    @endif
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="container-fluid my-2 my-md-0 justify-content-center align-items-center d-flex">
                    @if ($service->images->isNotEmpty())
                        @foreach ($service->images as $image)
                            <img width="500px" src="{{ Storage::url($image->path) }}" class="img-show m-1" alt="...">
                        @endforeach
                    @else
                        <img class="img-show" src="https://picsum.photos/500/400" alt="">

                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <x-contact-link />
    </div>
    <div class="row">
        <div class="col-12 mt-5">
            <h2 class="text-center">{{ __('ui.title2') }}</h2>
            <x-services />
        </div>
        <div class="col-12 mt-5">
            <h2 class="text-center mb-3">{{ __('ui.title3') }}</h2>
            <x-excursions />
        </div>
    </div>
</x-layout>
