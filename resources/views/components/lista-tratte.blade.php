<div class="container">
    @if ($tratte)
        <div class="text-center">
            @foreach ($tratte as $tratta)
                <div class="my-3">
                    <p class="h6">Da
                        <span class="text-primary">{{ $tratta->departure->name }}</span>
                        a
                        <span class="text-primary">{{ $tratta->arrival->name }}</span>
                    </p>
                    <p>A partire da <strong class="h4">{{ $tratta->price }} €</strong> a persona</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
