<x-dashboard-layout>
    <h2>Tutti i Servizi</h2>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Titolo</th>
                <th>Sottotitolo</th>
                <th>Links</th>
                <th>Immagini</th>
                <th>Azione</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($services as $service)
                <tr>
                    <td>{{ $service->id }}</td>
                    <td>{{ $service->title_it }}</td>
                    <td>{{ $service->subtitle_it }} €</td>
                    <td><a href="{{ $service->links }}">{{ $service->links }}</a></td>
                    <td>{{ $service->created_at }}</td>
                    <td>{{ $service->updated_at }}</td>
                    <td>
                        @if ($service->images->count() > 0)
                            <small>{{ $service->images->count() }} immagini caricate</small>
                        @else
                            <small>Nessuna immagine</small>
                        @endif
                    </td>
                    <td>
                        <a class="btn btn-primary btn-sm"
                            href="{{ route('service.edit', ['service' => $service]) }}">Dettagli</a>
                        <form action="{{ route('services.destroy', $service) }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Elimina</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-dashboard-layout>
