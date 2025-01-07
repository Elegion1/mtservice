<div class="container mt-3">
    @if ($tratte)
        <div class="d-flex flex-wrap justify-content-center align-items-center">
            @foreach ($tratte as $tratta)
                <div class="container text-center text-wrap text-uppercase">
                {{-- <div class="col-2 d-flex justify-content-end align-items-center">
                    <button class="btn btn-sm bg-a text-white prenota-btn">Prenota</button>
                </div> --}}
               
                    <p class="h6">{{__('ui.from')}}
                        <span class="text_col">{{ $tratta->departure->name }}</span>
                        {{__('ui.to')}}
                        <span class="text_col">{{ $tratta->arrival->name }}</span>
                    </p>
                    <p>{{__('ui.priceStartingFrom')}} <strong class="h4 text-d">{{ $tratta->price }} €</strong> {{__('ui.perPerson')}}</p>
                </div>
            @endforeach
        </div>
    @else
        <p class="h3 text-a text-center">{{__('ui.noRoutesAvailable')}}</p>
    @endif
</div>