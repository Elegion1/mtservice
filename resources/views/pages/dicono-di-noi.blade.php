<x-layout>
    <div class="container bg-white rounded shadow p-3">
        <div class="row">
            <div class="col-12">
                <h1>TITOLO</h1>
                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Ratione vero repudiandae libero ipsa rem
                    tenetur maiores nisi a molestias cupiditate fugit, sequi asperiores iusto aliquid eaque unde,
                    accusantium, officia quo?
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium fuga soluta optio quaerat
                    corrupti in natus debitis, laboriosam incidunt pariatur sint minus, tempore impedit omnis corporis
                    consequuntur ipsam possimus ipsa!
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Non magnam, neque corrupti, odio, culpa
                    corporis unde tempora qui doloremque consectetur aperiam dolorum? Libero a architecto molestias, in
                    excepturi numquam eaque?
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam asperiores suscipit perspiciatis,
                    cupiditate quisquam libero ut. Aliquid voluptatum saepe, odio, at eos aliquam, nemo quasi
                    praesentium quibusdam ullam totam animi?
                </p>
            </div>
            <div class="col-12 mt-5 text-center">
                <p class="h4">
                    <strong class="text-uppercase">Alcune recensioni</strong>
                </p>
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        @foreach ($reviews as $review)
                            <div class="col-3 my-3 mx-3 p-3 border rounded">
                                <p class="h5">{{ $review->title }}</p>
                                <p>{{ $review->body }}</p>
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

                    </div>
                </div>

            </div>
        </div>
    </div>

</x-layout>
