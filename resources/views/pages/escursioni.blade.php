<x-layout>
    <div class="row">
        <div class="col-12 col-md-6">

            <div class="container rounded bg-white border_custom">
                <div class="container p-3">
                    <livewire:prenotazione />
                </div>
            </div>

            <div class="container rounded p-3 mt-3">
                <x-show-content :pagine="$pagine" />
            </div>
        </div>

        <div class="col-12 col-md-6 ">
            <div id="escursioni"
                class="container d-flex justify-content-center align-items-center flex-column rounded bg-white">
                <p class="h2 my-3">{{__('ui.excursionPageTitle')}}</p>

                @foreach ($excursionsP as $excursion)
                    <div class="card border-0 mb-3" style="max-width: 540px;">
                        <div class="row g-0">
                            <div class="col-md-4 d-flex justify-content-center align-items-center">
                                @if ($excursion->images->isNotEmpty())
                                    <img src="{{ Storage::url($excursion->images->first()->path) }}"
                                        class="img-fluid rounded-start" alt="...">
                                @else
                                    <img src="https://picsum.photos/100{{ $excursion->id }}"
                                        class="img-fluid rounded-start" alt="immagine non disponibile">
                                @endif
                            </div>

                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $excursion->{'name_' . app()->getLocale()} }}</h5>
                                    <small>{{__('ui.duration')}} {{ $excursion->duration }}
                                        @if ($excursion->duration == 1)
                                            {{__('ui.hour')}}
                                        @else
                                        {{__('ui.hours')}}
                                        @endif
                                        {{__('ui.approx')}}
                                    </small>
                                    <p class="card-text">{!! $excursion->{'abstract_' . app()->getLocale()} !!}
                                    </p>
                                    <p class="card-text"><small
                                            class="text-body-secondary">{!! $excursion->{'description_' . app()->getLocale()} !!}</small>
                                    </p>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="m-0">
                                            <small class="text-black">{{__('ui.priceStartingFrom')}}</small>
                                            <strong class="fs-4">{{ $excursion->price }} €</strong>
                                        </p>

                                        <a class="btn rounded-4 bg-a text-white " href="{{ route('excursion.show', ['id' => $excursion->id]) }}">{{__('ui.details')}}</a>
                                        {{-- <a class="btn rounded-4 bg-b text-white " href="">Prenota</a> --}}

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Controlli di paginazione -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    {{ $excursionsP->links('vendor.pagination.bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
</x-layout>
