<x-layout>
    <div class="container bg-white rounded">
        <div class="row mx-3">
            <div class="col-12">
                <x-show-content :pagine="$pagine"/>
            </div>
            <div id="diconoDiNoi" class="col-12 mt-3 text-center">
                <h4>
                    <strong class="text-uppercase text-a">{{__('ui.someReviews')}}</strong>
                </h4>
                <div class="container-fluid p-0">
                    <div class="row d-flex justify-content-center">
                        @foreach ($reviewsP as $review)
                            <div class="col-12 col-md-3 m-3 p-2 border rounded">
                                <p class="h5 text-d">{{ $review->title }}</p>
                                <p>{!! $review->body !!}</p>
                                <p>
                                    <small class="text-a">
                                        {{ strtoupper(substr($review->name, 0, 1)) . '. ' . strtoupper(substr(explode(' ', $review->name)[1], 0, 1)) }}.
                                    </small>
                                </p>
                                <div class="rating">
                                    @for ($i = 0; $i < $review['rating']; $i++)
                                        <span class="star">&#9733;</span>
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                        <div class="d-flex justify-content-center align-items-center mt-4">
                            {{ $reviewsP->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-12 mt-5">
                <div class="container my-5">
                    <x-contact-link />
                </div>
                <h2 class="text-center">{{ __('ui.title2') }}</h2>
                <x-services />
            </div>
        </div>
    </div>

</x-layout>
