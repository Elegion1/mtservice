<x-layout>
    <div class="container mt-3">
        <div class="row">
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
            <div class="col-12">
                <div class="container">
                    @foreach ($service->images as $image)
                        <img src="{{ Storage::url($image->path) }}" alt="">
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-layout>
