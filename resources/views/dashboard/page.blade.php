<x-dashboard-layout>
    <div class="container-fluid mt-5">
        <h1>Gestione Pagine</h1>
        <h3 class="text-danger">NON MODIFICARE</h3>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form per aggiungere una nuova pagina -->
        <form action="{{ route('pages.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-3">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="col-3">
                    <label for="link" class="form-label">Link</label>
                    <input type="text" class="form-control" id="link" name="link" required>
                </div>
                <div class="col-3">
                    <label for="order" class="form-label">Ordine</label>
                    <input type="number" class="form-control" id="order" name="order" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi Pagina</button>
        </form>

        <hr>

        <!-- Tabella per visualizzare le pagine esistenti -->
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Link</th>
                    <th>Ordine</th>
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
                            <!-- Pulsante per aprire il modale di modifica -->
                            <button class="btn btn-warning btn-sm open-edit-modal" data-bs-toggle="modal"
                                data-bs-target="#editPageModal" data-page="{{ json_encode($page) }}">
                                Modifica
                            </button>
                            <!-- Form per eliminare la pagina -->
                            <form action="{{ route('pages.destroy', $page) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Elimina</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modale per modificare la pagina -->
    <div class="modal fade" id="editPageModal" tabindex="-1" aria-labelledby="editPageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPageModalLabel">Modifica Pagina</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editPageForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="editName" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editLink" class="form-label">Link</label>
                            <input type="text" class="form-control" id="editLink" name="link" required>
                        </div>
                        <div class="mb-3">
                            <label for="editOrder" class="form-label">Order</label>
                            <input type="number" class="form-control" id="editOrder" name="order" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Aggiorna Pagina</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Aggiungi evento ai pulsanti "Modifica" per aprire il modale con i dati corretti
            document.querySelectorAll('.open-edit-modal').forEach(button => {
                button.addEventListener('click', () => {
                    const page = JSON.parse(button.getAttribute('data-page'));
                    const form = document.getElementById('editPageForm');
                    form.action = `/dashboard/pages/${page.id}`;
                    form.querySelector('#editName').value = page.name;
                    form.querySelector('#editLink').value = page.link;
                    form.querySelector('#editOrder').value = page.order;
                });
            });
        });
    </script>
</x-dashboard-layout>