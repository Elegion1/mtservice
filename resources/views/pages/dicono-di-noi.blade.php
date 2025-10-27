<x-layout>
    <div>
        {{-- Contenuto principale --}}
        <div class="mx-3">
            <div>
                <x-show-content :pagine="$pagine" />
            </div>

            {{-- Sezione Recensioni --}}
            <div id="diconoDiNoi" class=" mt-5 mt-md-3 text-center">
                <h2 class="text-center">
                    {{ isset($reviewsP) ? __('ui.someReviews') : __('ui.noReviews') }}
                </h2>

                @if (isset($reviewsP))
                    <div class="container">
                        <div class="row d-flex justify-content-center gap-3">
                            @foreach ($reviewsP as $review)
                                <div id="reviewCard"
                                    class="col-12 col-md-6 col-lg-4 p-3 border rounded bg-white d-flex flex-column justify-content-between align-items-center">

                                    {{-- Titolo Recensione --}}
                                    <p class="h5 text-d">{{ $review->title }}</p>

                                    {{-- Corpo della Recensione --}}
                                    <p class="m-0">{!! $review->body !!}</p>

                                    {{-- Nome Recensore --}}

                                    <small class="text-a">
                                        {{ strtoupper(substr($review->name, 0, 1)) . '. ' . strtoupper(substr(explode(' ', $review->name)[1] ?? '', 0, 1)) . '.' }}
                                    </small>


                                    {{-- Stelle di valutazione --}}
                                    <div class="rating">
                                        @for ($i = 0; $i < $review['rating']; $i++)
                                            <span class="star">&#9733;</span>
                                        @endfor
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Paginazione --}}
                        <div class="d-flex justify-content-center align-items-center mt-4">
                            {{ $reviewsP->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sezione Servizi e Contatti --}}
        <div class=" mt-5">
            <div class=" my-5">
                <x-contact-link />
            </div>
            <h2 class="text-center">{{ __('ui.title2') }}</h2>
            <x-services />
        </div>
    </div>
</x-layout>
