<x-layout>
    <div class="container-fluid rounded p-3">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="container-fluid justify-content-center d-flex flex-column">
                    <h2 class="text-d text-wrap">{{ $service->{'title_' . app()->getLocale()} }}</h2>
                    <p>{!! $service->{'subtitle_' . app()->getLocale()} !!}</p>
                    <p>{!! $service->{'subtitleSec_' . app()->getLocale()} !!}</p>
                    {{-- <p class="text-secondary small">{!! $service->{'abstract_' . app()->getLocale()} !!}</p> --}}
                    <p>{!! $service->{'body_' . app()->getLocale()} !!}</p>
                    <p class="small">{!! $service->{'condition_' . app()->getLocale()} !!}</p>
                    @if ($service->links)
                        <a class="btn bg-dark p-1 text-light w-50" target="_blank" href="{{ $service->links }}">
                            {{ $service->{'abstract_' . app()->getLocale()} ?? __('ui.clickLink') }}
                        </a>
                    @endif
                    <br>
                    <p>{!! __('ui.usefulLinks') !!}</p>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="container-fluid my-2 my-md-0 justify-content-center align-items-center d-flex">
                    @if ($service->images->isNotEmpty())
                        @foreach ($service->images as $image)
                            <img loading="lazy" width="500px" src="{{ Storage::url($image->path) }}" class="img-show rounded m-1"
                                alt="img_{{ $service->{'slug_' . app()->getLocale()} }}">
                        @endforeach
                    @else
                        <img loading="lazy" class="img-show rounded" src="https://picsum.photos/500/400" alt="placeholder">
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
