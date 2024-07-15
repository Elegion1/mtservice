<x-layout>
    <div class="container bg-white rounded p-3">
        <div class="row">
            <div class="col-12">
                <x-show-content :pagine="$pagine"/>
            </div>
            <div id="diconoDiNoi" class="col-12 mt-5 text-center">
                <p class="h4">
                    <strong class="text-uppercase">Alcune recensioni</strong>
                </p>
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        @foreach ($reviewsP as $review)
                            <div class="col-12 col-md-3 my-3 mx-3 p-3 border rounded">
                                <p class="h5">{{ $review->title }}</p>
                                <p>{!! $review->body !!}</p>
                                <p>
                                    <small>
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
        </div>
    </div>

</x-layout>
