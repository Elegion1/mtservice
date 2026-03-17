<x-dashboard-layout>
    <h1>Gestione SEO Metadata</h1>

    <p class="mb-3">Modifica i meta title/description delle pagine pubbliche. La cache viene invalidata automaticamente dopo salvataggio.</p>

    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Page Key</th>
                <th>Title</th>
                <th>Description</th>
                <th>Azione</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($defaults as $pageKey)
                @php
                    $meta = $seoMeta[$pageKey] ?? null;
                @endphp
                <tr>
                    <td class="align-middle">{{ $pageKey }}</td>
                    <td>
                        <form action="{{ $meta ? route('seoMeta.update', $meta) : route('seoMeta.store') }}" method="POST">
                            @csrf
                            @if ($meta)
                                @method('PUT')
                            @endif
                            <input type="hidden" name="page_key" value="{{ $pageKey }}">
                            <input type="text" name="title" class="form-control form-control-sm" value="{{ old('title', $meta->title ?? '') }}" required>
                    </td>
                    <td>
                            <input type="text" name="description" class="form-control form-control-sm" value="{{ old('description', $meta->description ?? '') }}" required>
                    </td>
                    <td class="align-middle">
                            <button class="btn btn-sm btn-primary mb-1" type="submit">{{ $meta ? 'Aggiorna' : 'Crea' }}</button>
                        @if ($meta)
                            <button class="btn btn-sm btn-danger" type="submit" form="delete-{{ $meta->id }}">Elimina</button>
                        @endif
                        </form>
                        @if ($meta)
                            <form id="delete-{{ $meta->id }}" method="POST" action="{{ route('seoMeta.destroy', $meta) }}" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</x-dashboard-layout>
