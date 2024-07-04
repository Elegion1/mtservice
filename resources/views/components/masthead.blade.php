<header class="position-relative">
    <div class="masthead">
        @php
            $currentRoute = Route::currentRouteName();
            $imageDisplayed = false;
        @endphp

        @foreach ($contents as $content)
            @if ($content->page->link == $currentRoute)
                @if ($content->images->isNotEmpty() && $content->order == 0)
                    <img class="img_car" src="{{ Storage::url($content->images->first()->path) }}" alt="">
                    @php
                        $imageDisplayed = true;
                        break;
                    @endphp
                @endif
            @endif
        @endforeach

        @if (!$imageDisplayed)
            <img class="img_car" src="https://picsum.photos/1920/1080" alt="Default Image">
        @endif
    </div>
</header>