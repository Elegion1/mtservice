<x-dashboard-layout>
    <div class="container-fluid mt-5">
        <h2>Contenuti</h2>
        <a href="{{ route('content.create') }}" class="btn bg-a text-white">Crea</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Pagina</th>
                <th>Titolo</th>
                <th>Sottotitolo</th>
                <th>Corpo</th>
                <th>Ordine</th>
                <th>Mostra</th>
                <th>Inizio</th>
                <th>Fine</th>
                <th>Immagini</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contents as $content)
                <tr>
                    <td>{{ $content->id }}</td>
                    <td>{{ $content->page->name }}</td>
                    <td>{{ $content->title_it }}</td>
                    <td>{{ $content->subtitle_it }}</td>
                    <td>{{ $content->body_it }}</td>
                    <td>{{ $content->order }}</td>
                    <td>
                        @if ($content->show)
                            Si
                        @else
                            No
                        @endif
                    </td>
                    <td>{{$content->start_date}}</td>
                    <td>{{$content->end_date}}</td>
                    <td>
                        {{-- @foreach ($content->images as $image)
                            <img src="{{ asset('storage/' . $image->path) }}" alt="Immagine" width="100">
                        @endforeach --}}
                        @if ($content->images->count() > 0)
                            <small>
                                {{ $content->images->count() }} immagini caricate
                            </small>
                        @else
                            <small>Nessuna immagine</small>
                        @endif
                    </td>
                    <td>
                        <a class="btn btn-primary btn-sm"
                            href="{{ route('content.edit', ['content' => $content]) }}">Dettagli</a>
                        <form action="{{ route('contents.destroy', $content) }}" method="POST"
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
