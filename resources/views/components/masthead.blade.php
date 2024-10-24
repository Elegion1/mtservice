<header class="position-relative">
    <div class="masthead">
        @php
            use Carbon\Carbon;
            $currentRoute = Route::currentRouteName();
            $imageDisplayed = false;
            $now = Carbon::now();
            $defaultContent = null; // Variabile per il contenuto da mostrare su tutte le pagine
        @endphp

        @foreach ($contents as $content)
            @if ($content->order == 0 && $content->show)
                {{-- Controlla se ci sono contenuti specifici per la pagina --}}
                @if ($content->page && $content->page->link == $currentRoute)
                    {{-- Mostra solo se ha un'immagine --}}
                    @if ($content->images->isNotEmpty())
                        <img class="img_car" src="{{ Storage::url($content->images->first()->path) }}"
                            alt="HEADER-IMG-{{ $currentRoute }}">
                        @php
                            $imageDisplayed = true;
                        @endphp
                    @endif

                    {{-- Mostra titolo e sottotitolo se non ci sono date di inizio/fine o sono valide --}}
                    @if ((!$content->start_date || $content->start_date <= $now) && (!$content->end_date || $content->end_date >= $now))
                        <div class="position-absolute text_masthead translate-middle text-white text-center">
                            <h1 class="text-b text-shadow text-responsive mt-5">{!! strtoupper($content->{'title_' . app()->getLocale()}) !!}</h1>
                            <h2 class="text-shadow text-c btn_font_size">
                                {{ $content->{'subtitle_' . app()->getLocale()} }}</h2>
                        </div>
                    @endif
                @elseif (!$content->page)
                    {{-- Se non c'è pagina associata, memorizza il contenuto da mostrare su tutte le pagine --}}
                    @php
                        $defaultContent = $content;
                    @endphp
                @endif
            @endif
        @endforeach

        {{-- Se non è stata visualizzata nessuna immagine specifica, mostra il contenuto predefinito --}}
        @if (!$imageDisplayed && $defaultContent)
            @if ($defaultContent->images->isNotEmpty())
                <img class="img_car" src="{{ Storage::url($defaultContent->images->first()->path) }}"
                    alt="HEADER-IMG-Default">
            @else
                <img class="img_car"
                    src="https://tranchidatransfer.it/storage/images/XLwFNr204aSLbrGfbAQc3wJ5eq8emjznHq1X4ucK.jpg"
                    alt="HEADER-IMG-Default">
            @endif
            <div class="position-absolute text_masthead translate-middle text-white text-center">
                <h1 class="text-b text-shadow text-responsive mt-5">{!! strtoupper($defaultContent->{'title_' . app()->getLocale()}) !!}</h1>
                <h2 class="text-shadow text-c btn_font_size">{{ $defaultContent->{'subtitle_' . app()->getLocale()} }}
                </h2>
            </div>
        @endif
    </div>
</header>
