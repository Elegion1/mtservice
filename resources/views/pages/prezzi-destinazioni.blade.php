<x-layout>
    <div class="row">
        <div class="col-12 col-md-8">
            <div class="container bg-white rounded p-3">
                <div class="d-flex align-items-center justify-content-center">

                    <img class="rounded" src="{{ Storage::url($page->contents[1]->images[0]->path) }}" alt="">

                </div>

                <h1>{{ $page->contents[1]->title }}</h1>
                <h6>{{ $page->contents[1]->subtitle }}</h6>
                <p>{{ $page->contents[1]->body }}</p>
                <a href="{{ $page->contents[1]->links }}">Link</a>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="container bg-white rounded  p-3">
                <x-lista-tratte />
            </div>
        </div>
    </div>


</x-layout>
