<x-dashboard-layout>
    <div class="container-fluid mt-5">
        <h1>Gestione Destinazioni</h1>

        <form action="{{ route('destinations.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="mb-3 col-lg-4 col-12">
                    <label for="name" class="form-label">Destinazione</label>
                    <input type="text" class="form-control form_input_focused" id="name" name="name" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi Destinazione</button>
        </form>
        <hr>
        <h2>Tutte le Destinazioni</h2>
        <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($destinations as $destination)
                    <tr>
                        <td>{{ $destination->id }}</td>
                        <td>{{ $destination->name }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editDestinationModal" data-id="{{ $destination->id }}"
                                data-name="{{ $destination->name }}">Modifica</button>
                            <form action="{{ route('destinations.destroy', $destination) }}" method="POST"
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
    </div>

    <!-- Modale per Modifica Destinazione -->
    <div class="modal fade" id="editDestinationModal" tabindex="-1" aria-labelledby="editDestinationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDestinationModalLabel">Modifica Destinazione</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editDestinationForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nome Destinazione</label>
                            <input type="text" class="form-control form_input_focused" id="edit_name" name="name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editDestinationModal = document.getElementById('editDestinationModal');
            editDestinationModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');

                var modal = this;
                modal.querySelector('#edit_name').value = name;

                var form = modal.querySelector('#editDestinationForm');
                form.action = '{{ url("dashboard/destinations") }}/' + id;
            });
        });
    </script>
</x-dashboard-layout>
