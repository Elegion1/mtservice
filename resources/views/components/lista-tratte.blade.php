<div class="container mt-3">
    @if ($tratte)
        <div>
            @foreach ($tratte as $tratta)
            <div class="row mb-3">
                {{-- <div class="col-2 d-flex justify-content-end align-items-center">
                    <button class="btn btn-sm bg-a text-white prenota-btn">Prenota</button>
                </div> --}}
                <div class="col-10">
                    <p class="h6">Da
                        <span class="text-primary">{{ $tratta->departure->name }}</span>
                        a
                        <span class="text-primary">{{ $tratta->arrival->name }}</span>
                    </p>
                    <p>A partire da <strong class="h4">{{ $tratta->price }} €</strong> a persona</p>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <p class="h3 text-danger text-center">Non ci sono tratte disponibili</p>
    @endif
</div>