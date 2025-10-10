<x-dashboard-layout>

    <h2>Contenuti</h2>
    <a href="{{ route('content.create') }}" class="btn bg-a text-white">Crea</a>

    <table class="table table-sm table-striped">
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
                    <td>
                        @if (isset($content->page->name))
                            {{ $content->page->name }}
                        @else
                            Tutte
                        @endif
                    </td>
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
                    <td>{{ $content->start_date }}</td>
                    <td>{{ $content->end_date }}</td>
                    <td>
                        {{-- @foreach ($content->images as $image)
                            <img loading="lazy" src="{{ asset('storage/' . $image->path) }}" alt="Immagine" width="100">
                        @endforeach --}}
                        @if ($content->images->count() > 0)
                            <small>
                                {{ $content->images->count() }} immagini caricate
                            </small>
                            @foreach ($content->images as $image)
                                <img loading="lazy" height="50px" src="{{ Storage::url($image->path) }}" alt="">
                            @endforeach
                        @else
                            <small>Nessuna immagine</small>
                        @endif
                    </td>
                    <td>
                        <a class="btn btn-primary btn-sm"
                            href="{{ route('content.edit', ['content' => $content]) }}">Dettagli</a>
                        <x-delete-button :route="'contents'" :model="$content" />
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-dashboard-layout>
