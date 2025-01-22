<x-dashboard-layout>
    <div class="container-fluid mt-5">
        <h2>Servizi</h2>
        <a href="{{ route('service.create') }}" class="btn bg-a text-white">Crea</a>
    </div>
    <table class="table table-sm table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Titolo</th>
                <th>Sottotitolo</th>
                <th>Links</th>
                <th>Immagini</th>
                <th>Mostra</th>
                <th>Sulla home ({{ $services->where('flag', 1)->count() }})</th>
                <th>Azione</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($services as $service)
                <tr>
                    <td>{{ $service->id }}</td>
                    <td>{{ $service->title_it }}</td>
                    <td>{{ $service->subtitle_it }}</td>
                    <td><a href="{{ $service->links }}">{{ $service->links }}</a></td>

                    <td>
                        @if ($service->images->count() > 0)
                            <small>{{ $service->images->count() }} immagini caricate</small>
                        @else
                            <small>Nessuna immagine</small>
                        @endif
                    </td>
                    <td>{{ $service->show }}</td>
                    <td>{{ $service->flag }}</td>
                    <td>
                        <a class="btn btn-primary btn-sm"
                            href="{{ route('service.edit', ['service' => $service]) }}">Dettagli</a>
                        <x-delete-button :route="'services.destroy'" :model="$service" />
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-dashboard-layout>
