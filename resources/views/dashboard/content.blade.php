<x-dashboard-layout>
    <h2>Contenuti</h2>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Pagina</th>
                <th>Titolo</th>
                <th>Sottotitolo</th>
                <th>Corpo</th>
                <th>Links</th>
                <th>Ordine</th>
                <th>Mostra</th>
                <th>Immagini</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contents as $content)
                <tr>
                    <td>{{ $content->id }}</td>
                    <td>{{ $content->page->name }}</td>
                    <td>{{ $content->title }}</td>
                    <td>{{ $content->subtitle }}</td>
                    <td>{{ $content->body }}</td>
                    <td>{{ $content->links }}</td>
                    <td>{{ $content->order }}</td>
                    <td>
                        @if ($content->show)
                            Si
                        @else
                            No
                        @endif
                    </td>
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
