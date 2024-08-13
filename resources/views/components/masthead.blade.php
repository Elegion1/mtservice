<header class="position-relative">
    <div class="masthead">
        @php
            $currentRoute = Route::currentRouteName();
            $imageDisplayed = false;
        @endphp

        @foreach ($contents as $content)
            @if ($content->page->link == $currentRoute)
                @if ($content->images->isNotEmpty() && $content->order == 0)
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
            <img class="img_car" src="https://tranchidatransfer.it/storage/images/rDbh790BjCjSxKQKIGWeV5COjit8n53cGkOqYbat.jpg" alt="Default Image">
        @endif
        
        <div class="position-absolute text_masthead translate-middle text-white text-center">
            @if ($content->page->link == $currentRoute && $content->order == 0)
                <h1 class="text-d text-shadow text-responsive mt-5">
                    {!! strtoupper($content->{'title_' . app()->getLocale()}) !!}</h1>
                <p class="text-shadow btn_font_size">{{ $content->{'subtitle_' . app()->getLocale()} }}</p>
            @endif
        </div>
    </div>
</header>
