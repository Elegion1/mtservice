@php
    use Carbon\Carbon;
    $now = Carbon::now();
    $headingLevel = $headingLevel ?? 2; // di default inizia da h2, ma puoi sovrascriverlo da fuori
@endphp

<div class="row mb-3">
    @foreach ($pagine as $page)
        @foreach ($page->contents as $content)
            @if (
                $content->show &&
                    (!$content->start_date || $content->start_date <= $now) &&
                    (!$content->end_date || $content->end_date >= $now))
                <div class="col-12">
                    {{-- Immagine principale --}}
                    @if ($content->images->count())
                        <x-responsive-image loading="lazy" width="100%" image="{{ $content->images[0]->path }}"
                            alt="img_{{ $content->title_en }}" class="rounded" />
                    @endif

                    {{-- Titolo dinamico --}}
                    @if ($content->{'title_' . app()->getLocale()})
                        @php
                            $tag = 'h' . $headingLevel;
                        @endphp
                        <<?= $tag ?> class="text-uppercase text-wrap">
                            {{ $content->{'title_' . app()->getLocale()} }}
                        </<?= $tag ?>>
                    @endif

                    {{-- Sottotitolo --}}
                    @if ($content->{'subtitle_' . app()->getLocale()})
                        @php
                            $subTag = 'h' . min($headingLevel + 1, 6);
                        @endphp
                        <<?= $subTag ?>>{{ $content->{'subtitle_' . app()->getLocale()} }}</<?= $subTag ?>>
                    @endif

                    {{-- Corpo testo --}}
                    <div style="width: 100%; box-sizing: border-box; word-wrap: break-word; overflow-wrap: break-word;">
                        {!! $content->{'body_' . app()->getLocale()} !!}
                    </div>

                    {{-- Link eventuale --}}
                    @if ($content->links)
                        <a href="{{ $content->links }}">Link</a>
                    @endif
                </div>
            @endif
        @endforeach
    @endforeach
</div>
