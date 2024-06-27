<div class="container mt-3">
    @if ($tratte)
        <div class="d-flex flex-wrap justify-content-center align-items-center">
            @foreach ($tratte as $tratta)
                <div class="container text-center text-wrap">
                {{-- <div class="col-2 d-flex justify-content-end align-items-center">
                    <button class="btn btn-sm bg-a text-white prenota-btn">Prenota</button>
                </div> --}}
               
                    <p class="h6">Da
                        <span class="text_col">{{ $tratta->departure->name }}</span>
                        a
                        <span class="text_col">{{ $tratta->arrival->name }}</span>
                    </p>
                    <p>A partire da <strong class="h4">{{ $tratta->price }} €</strong> a persona</p>
                </div>
            @endforeach
        </div>
    @else
        <p class="h3 text-a text-center">Non ci sono tratte disponibili</p>
    @endif
</div>