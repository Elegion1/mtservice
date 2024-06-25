<x-layout>
    <div class="container bg-white rounded shadow p-3">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="col-12">
                    <p class="h2">{{ $service->title }}</p>
                </div>
                <div class="col-12">
                    <p class="h5">{{ $service->subtitle }}</p>
                </div>
                <div class="col-12">
                    <p>{{ $service->subtitleSec }}</p>
                </div>
                <div class="col-12">
                    <p class="text-secondary small">{{ $service->abstract }}</p>
                </div>
                <div class="col-12">
                    <p>{{ $service->body }}</p>
                </div>
                <div class="col-12">
                    <a class="small" href="{{ $service->links }}">{{ $service->links }}</a>
                </div>
                <div class="col-12">
                    <p class="small">{{ $service->condition }}</p>
                </div>

            </div>
            <div class="col-12 col-md-6">
                <div class="container d-flex align-items-center justify-content-center">
                    @if ($service->images->isNotEmpty())
                        @foreach ($service->images->first() as $image)
                                <img src="{{ Storage::url($image->path) }}" class="d-block w-100" alt="...">
                        @endforeach
                    @else
                        <div class="container-fluid my-2 justify-content-center align-items-center d-flex">
                            <img class="rounded shadow" src="https://picsum.photos/400" alt="">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <x-contact-link/>
    </div>
</x-layout>
