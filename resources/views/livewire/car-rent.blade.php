<div>
    <div class="container">
        <div class="row">
            <div class="col">
                <form wire:submit.prevent="submitBookingRent">
                    <div class="mb-3 row bg-b p-2 rounded">
                        <div class="col-12 col-md-5">
                            <label class="form-label" for="dateStart">Data di ritiro</label>
                            <input wire:model.live="dateStart" type="date" class="form-control" id="dateStart">
                        </div>
                        <div class="col-12 col-md-5">
                            <label class="form-label" for="dateEnd">Data di consegna</label>
                            <input wire:model.live="dateEnd" type="date" class="form-control" id="dateEnd">
                        </div>
                        <div class="col-12 col-md-2">
                            <label class="form-label" for="quantity">Quantità</label>
                            <input wire:model.live="quantity" type="number" class="form-control" id="quantity"
                                min="1" max="5">
                        </div>
                    </div>
                    <div class="container p-md-3">
                        <p><strong>SELEZIONA UN MEZZO</strong></p>
                        @foreach ($cars as $car)
                            <div class="form-check border rounded mb-3 row d-flex justify-content-between align-items-center">
                                <div class="col-1">
                                    <input wire:model.live="carID" value="{{ $car->id }}" class="form-check-input"
                                        type="radio" name="flexRadioDefault" id="car{{ $car->id }}">
                                </div>
                                <div class="col-3 p-1 my-auto">
                                    <img width="80px" src="{{ Storage::url($car->img) }}" alt="">
                                </div>
                                <div class="col-4 text-center text-md-start d-flex   flex-column">
                                    <p class="m-1 h6">{{ $car->name }}</p>
                                    <p><small>{{ $car->description }}</small></p>
                                </div>
                                <div class="col-4 d-flex align-items-end justify-content-center flex-column">
                                    <p class="m-1">A partire da</p>
                                    <p class="h3">{{ $car->price }} €</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="container-fluid">
                        <p><strong>TOTALE</strong></p>
                        <div class="d-flex justify-content-between mb-3">
                            <input wire:model.live="rentPrice" class="form-control mx-1" type="text"
                                aria-label="readonly input example" readonly>
                            <button class="btn bg-a text-white mx-1" type="submit">Prenota</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
