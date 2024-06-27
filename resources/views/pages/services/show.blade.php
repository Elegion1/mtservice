<x-layout>
    <div class="container-fluid bg-white rounded p-3">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="container-fluid justify-content-center d-flex flex-column">
                    <p class="h2">{{ $service->title }}</p>
                    <p class="h5">{{ $service->subtitle }}</p>
                    <p>{{ $service->subtitleSec }}</p>
                    <p class="text-secondary small">{{ $service->abstract }}</p>
                    <p>{{ $service->body }}</p>
                    <a class="small" href="{{ $service->links }}">{{ $service->links }}</a>
                    <p class="small">{{ $service->condition }}</p>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="container-fluid my-2 my-md-0 justify-content-center align-items-center d-flex">
                    @if ($service->images->isNotEmpty())
                        @foreach ($service->images->first() as $image)
                            <img src="{{ Storage::url($image->path) }}" class="rounded shadow" alt="...">
                        @endforeach
                    @else
                        <img class="rounded shadow" src="https://picsum.photos/600/400" alt="">

                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <x-contact-link />
    </div>
</x-layout>
