<div>
    @if ($tratte)
        <div class="container text-center my-3 border rounded bg-white p-3">
            @foreach ($tratte as $tratta)
                <div class="my-3">
                    <h6>Da
                        <span class="text-primary">{{ $tratta->departure->name }}</span>
                        a
                        <span class="text-primary">{{ $tratta->arrival->name }}</span>
                    </h6>
                    <p>A partire da <strong class="h4">{{ $tratta->price }} €</strong> a persona</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
