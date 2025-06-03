<x-dashboard-layout>
    <div class="d-flex justify-content-between align-items-center">
        <h2>Escursioni</h2>
        <a href="{{ route('excursion.create') }}" class="btn bg-a text-white">Crea</a>
    </div>

    <hr>
    @if (request()->header('User-Agent') && preg_match('/Mobile|Android|iPhone/i', request()->header('User-Agent')))
        <div class="overflow-y-auto border-bottom rounded" style="height: 75vh">
            <h2>Tutte le escursioni</h2>
            @foreach ($excursions as $excursion)
                <div class="card mb-3 excursion-card">
                    <div class="card-body">
                        <p class="card-text h5"><strong>{{ $excursion->name_it }}</strong></p>
                        <p class="card-text"><strong>Prezzo:</strong> {{ $excursion->price }} €</p>
                        <p class="card-text"><strong>Incremento di prezzo:</strong> {{ $excursion->price_increment }} €
                        </p>
                        <p class="card-text"><strong>Durata:</strong> {{ $excursion->duration }} h</p>
                        @if ($excursion->images->count() > 0)
                            <small>{{ $excursion->images->count() }} immagini caricate</small>
                        @else
                            <small>Nessuna immagine</small>
                        @endif

                        <div class="d-flex justify-content-end">
                            <a class="btn btn-primary btn-sm"
                                href="{{ route('excursion.edit', ['excursion' => $excursion]) }}">Dettagli</a>
                            <x-delete-button :route="'excursions'" :model="$excursion" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Prezzo</th>
                    <th>Incremento Prezzo</th>
                    <th>PAX Incremento</th>
                    <th>Durata</th>
                    <th>Data di aggiunta</th>
                    <th>Data di modifica</th>
                    <th>Mostra</th>
                    <th>Immagini</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($excursions as $excursion)
                    <tr>
                        <td>{{ $excursion->id }}</td>
                        <td>{{ $excursion->name_it }}</td>
                        <td>{{ $excursion->price }} €</td>
                        <td>{{ $excursion->price_increment }} €</td>
                        <td>{{ $excursion->increment_passengers }}</td>
                        <td>{{ $excursion->duration }} h</td>
                        <td>{{ $excursion->created_at }}</td>
                        <td>{{ $excursion->updated_at }}</td>
                        <td>{{ $excursion->show }}</td>
                        <td>
                            @if ($excursion->images->count() > 0)
                                <small>{{ $excursion->images->count() }} immagini caricate</small>
                            @else
                                <small>Nessuna immagine</small>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-primary btn-sm"
                                href="{{ route('excursion.edit', ['excursion' => $excursion]) }}">Dettagli</a>
                            <x-delete-button :route="'excursions'" :model="$excursion" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-dashboard-layout>
