<x-layout>
    <div class="container bg-white rounded">
        {{-- Contenuto principale --}}
        <div class="row mx-3">
            <div class="col-12">
                <x-show-content :pagine="$pagine" />
            </div>

            {{-- Sezione Recensioni --}}
            <div id="diconoDiNoi" class="col-12 mt-5 mt-md-3 text-center">
                <h1 class="text-center">
                    {{ isset($reviewsP) ? __('ui.someReviews') : __('ui.noReviews') }}
                </h1>

                @if (isset($reviewsP))
                    <div class="container-fluid p-0">
                        <div class="row d-flex justify-content-center">
                            @foreach ($reviewsP as $review)
                                <div class="col-12 col-md-3 m-3 p-2 border rounded">
                                    {{-- Titolo Recensione --}}
                                    <p class="h5 text-d">{{ $review->title }}</p>

                                    {{-- Corpo della Recensione --}}
                                    <p>{!! $review->body !!}</p>

                                    {{-- Nome Recensore --}}
                                    <p>
                                        <small class="text-a">
                                            {{ strtoupper(substr($review->name, 0, 1)) . '. ' . strtoupper(substr(explode(' ', $review->name)[1] ?? '', 0, 1)) }}.
                                        </small>
                                    </p>

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
        <div class="col-12 mt-5">
            <div class="container my-5">
                <x-contact-link />
            </div>
            <h2 class="text-center">{{ __('ui.title2') }}</h2>
            <x-services />
        </div>
    </div>
</x-layout>
