<header class="position-relative">
    <div class="masthead">
        @php
            $currentRoute = Route::currentRouteName();
            $imageDisplayed = false;
        @endphp

        @foreach ($contents as $content)
            @if ($content->page->link == $currentRoute)
                @if ($content->images->isNotEmpty() && $content->order == 0 && $content->show)
                    <img class="img_car" src="{{ Storage::url($content->images->first()->path) }}"
                        alt="HEADER-IMG-{{ $currentRoute }}">
                    @php
                        $imageDisplayed = true;
                        break;
                    @endphp
                @endif
            @endif
        @endforeach

        @if (!$imageDisplayed)
            <img class="img_car" src="https://tranchidatransfer.it/storage/images/XLwFNr204aSLbrGfbAQc3wJ5eq8emjznHq1X4ucK.jpg" alt="Default Image">
        @endif
        
        <div class="position-absolute text_masthead translate-middle text-white text-center">
            @if ($content->page->link == $currentRoute && $content->order == 0 && $content->show)
                <h1 class="text-b text-shadow text-responsive mt-5">
                    {!! strtoupper($content->{'title_' . app()->getLocale()}) !!}</h1>
                <h2 class="text-shadow text-c btn_font_size">{{ $content->{'subtitle_' . app()->getLocale()} }}</h2>
            @endif
        </div>
    </div>
</header>
