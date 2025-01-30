<x-dashboard-layout>

    <h2>Escursioni</h2>
    <a href="{{ route('excursion.create') }}" class="btn bg-a text-white">Crea</a>

    <table class="table table-sm table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Prezzo</th>
                <th>Incremento Prezzo</th>
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
</x-dashboard-layout>
