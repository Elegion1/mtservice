<div class="row mb-3">
    {{-- @dd($pagine) --}}
    @foreach ($pagine as $page)
        @foreach ($page->contents as $content)
            @if ($content->show)
                @if ($content->images->count())
                    <div class="col-12">
                        <img class="rounded" width="100%" src="{{ Storage::url($content->images[0]->path) }}"
                            alt="img_{{ $content->title_en }}">
                    </div>
                @endif
                <div class="col-12">
                    @if ($content->{'title_' . app()->getLocale()})
                        <h1 class="text-uppercase text-wrap">{{ $content->{'title_' . app()->getLocale()} }}</h1>
                    @endif
                    @if ($content->{'subtitle_' . app()->getLocale()})
                        <h6>{{ $content->{'subtitle_' . app()->getLocale()} }}</h6>
                    @endif
                    <div style="width: 100%; box-sizing: border-box; word-wrap: break-word; overflow-wrap: break-word;">
                        <div style="max-width: 100%;">
                            {!! $content->{'body_' . app()->getLocale()} !!}
                        </div>
                    </div>

                    @if ($content->links)
                        <a href="{{ $content->links }}">Link</a>
                    @endif
                </div>
            @endif
        @endforeach
    @endforeach
</div>

