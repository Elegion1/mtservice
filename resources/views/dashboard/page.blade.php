<x-dashboard-layout>
    <div class="container-fluid mt-5">
        <h1>Gestione Pagine</h1>
        <h3 class="text-danger">NON MODIFICARE</h3>


        <!-- Form per aggiungere una nuova pagina -->
        <form action="{{ route('pages.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-3">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" class="form-control form_input_focused" id="name" name="name" required>
                </div>
                <div class="col-3">
                    <label for="link" class="form-label">Link</label>
                    <input type="text" class="form-control form_input_focused" id="link" name="link"
                        required>
                </div>
                <div class="col-3">
                    <label for="order" class="form-label">Ordine</label>
                    <input type="number" class="form-control form_input_focused" id="order" name="order"
                        required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi Pagina</button>
        </form>

        <hr>

        <!-- Tabella per visualizzare le pagine esistenti -->
        <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Link</th>
                    <th>Ordine</th>
                    <th>Mostra</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pages as $page)
                    <tr>
                        <td>{{ $page->id }}</td>
                        <td>{{ $page->name }}</td>
                        <td>{{ $page->link }}</td>
                        <td>{{ $page->order }}</td>
                        <td>
                            @if ($page->show)
                                Si
                            @else
                                No
                            @endif
                        </td>
                        <td>
                            <x-edit-button :id="'Page'" :data="$page" />
                            <x-delete-button :route="'pages.destroy'" :model="$page" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modale per modificare la pagina -->
    <x-modal :id="'Page'" :title="'Modifica pagina'">
        <div class="mb-3">
            <label for="edit_name" class="form-label">Nome</label>
            <input type="text" class="form-control form_input_focused" id="edit_name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="edit_link" class="form-label">Link</label>
            <input type="text" class="form-control form_input_focused" id="edit_link" name="link" required>
        </div>
        <div class="mb-3">
            <label for="edit_order" class="form-label">Order</label>
            <input type="number" class="form-control form_input_focused" id="edit_order" name="order" required>
        </div>
        <div class="mb-3">
            <label for="edit_show" class="form-label">Mostra</label>
            <select name="show" id="edit_show" class="form-select" aria-label="Default select example">
                <option value="1">Si</option>
                <option value="0">No</option>
            </select>
        </div>
    </x-modal>

</x-dashboard-layout>
