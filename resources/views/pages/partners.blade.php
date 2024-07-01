<x-layout>
    <div id="partners" class="container bg-white rounded">
        <div class="container p-3">
            <h1>I nostri partner</h1>
            <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam cumque nulla harum sit corporis unde esse
                delectus eum, repudiandae perferendis assumenda aliquid, minus eius, dolor sapiente? At consequuntur dicta
                qui!
            </p>
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                @foreach ($partners as $partner)
                    <div class="m-3">
                        <a class="text-decoration-none text-black" target="_blank" href="{{ $partner->link }}">
                            <div class="d-flex justify-content-center align-items-center">
                                @if ($partner->images->count() > 0)
                                    <img width="200px" src="{{ Storage::url($partner->images->first()->path) }}"
                                        alt="">
                                @else
                                    <img width="200px" src="https://picsum.photos/2{{$partner->id}}" alt="">
                                @endif
                            </div>
                            <p class="text-center">{{ $partner->name }}</p>
                        </a>
                    </div>
                @endforeach
                <div class="d-flex justify-content-between align-items-center mt-4">
                    {{ $partners->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

</x-layout>
