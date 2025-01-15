<div class="container mt-3">
    @if (isset($tratte))
        <div class="d-flex justify-content-center align-items-center flex-column text-center mb-3">
            @foreach ($tratte as $tratta)
                <div class="container text-uppercase">
                {{-- <div class="col-2 d-flex justify-content-end align-items-center">
                    <button class="btn btn-sm bg-a text-white prenota-btn">Prenota</button>
                </div> --}}
               
                    <p class="small mb-1">{{__('ui.from')}}
                        <span class="text_col">{{ $tratta->departure->name }}</span>
                        {{__('ui.to')}}
                        <span class="text_col">{{ $tratta->arrival->name }}</span>
                    </p>
                    <p class="small">{{__('ui.priceStartingFrom')}} <strong class="small text-d">{{ $tratta->price }} €</strong> {{__('ui.perPerson')}}</p>
                </div>
            @endforeach
        </div>
    @else
        <p class="h3 text-a text-center">{{__('ui.noRoutesAvailable')}}</p>
    @endif
</div>