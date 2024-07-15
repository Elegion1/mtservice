<div class="d-flex justify-content-center align-items-center flex-column">
    <div class="row mb-3">
        {{-- @dd($pagine) --}}
        @foreach ($pagine as $page)
            @foreach ($page->contents as $content)
                @if ($content->show)
                    <div class="col-12">
                        <img class="rounded" width="100%" src="{{ Storage::url($content->images[0]->path) }}" alt="">
                    </div>
                    <div class="col-12 mt-3">
                        <h1>{{ $content->{'title_' . app()->getLocale()} }}</h1>
                        <h6>{{ $content->{'subtitle_' . app()->getLocale()} }}</h6>
                        {!! $content->{'body_' . app()->getLocale()} !!}
                        @if ($content->links)
                            <a href="{{ $content->links }}">Link</a>
                        @endif

                    </div>
                @endif
            @endforeach
        @endforeach
    </div>
</div>