<div class="container mt-3">
    @if (!empty($tratte) && count($tratte))
        <div class="d-flex justify-content-center align-items-center flex-column text-center mb-3">
            @foreach ($tratte as $tratta)
                @php
                    $locale = App::getLocale();
                    $departureSlug = $tratta->departure->slug;
                    $arrivalSlug = $tratta->arrival->slug;
                @endphp

                <div class="container text-uppercase py-1 px-0 d-flex justify-content-center align-items-center flex-column">
                    <a href="{{ route('transfer.show', ['locale' => $locale, 'departure' => $departureSlug, 'arrival' => $arrivalSlug]) }}"
                        class="text-decoration-none text-dark d-block">
                        <p class="small">
                            {{ __('ui.from') }}
                            <span class="text_col">{{ $tratta->departure->name }}</span>
                            {{ __('ui.to') }}
                            <span class="text_col">{{ $tratta->arrival->name }}</span>
                        </p>
                    </a>
                        <p class="small">
                            {{ __('ui.priceStartingFrom') }}
                            <strong class="small text-d">{{ $tratta->price }} €</strong>
                            {{ __('ui.perPerson') }}
                        </p>
                </div>
            @endforeach
        </div>
    @else
        <p class="h3 text-a text-center">{{ __('ui.noRoutesAvailable') }}</p>
    @endif
</div>
