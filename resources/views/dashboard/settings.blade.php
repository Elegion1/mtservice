<x-dashboard-layout>
    <div class="container-fluid mt-5">
        <h1>Gestione Impostazioni</h1>
        <!-- Form per Aggiungere Nuova Impostazione -->
        <form action="{{ route('settings.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="mb-3 col-5">
                    <label for="name" class="form-label">Nome Impostazione</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3 col-5">
                    <label for="value" class="form-label">Valore</label>
                    <input type="text" class="form-control" id="value" name="value" required>
                </div>
                <div class="mb-3 col-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Aggiungi</button>
                </div>
            </div>
        </form>
        <hr>

        <!-- Tabella delle Impostazioni -->
        <h2>Tutte le Impostazioni</h2>
        <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Valore</th>
                    <th>Data Creazione</th>
                    <th>Data Modifica</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($settings as $setting)
                    <tr>
                        <td>{{ $setting->id }}</td>
                        <td>{{ $setting->name }}</td>
                        <td>{{ $setting->value }}</td>
                        <td>{{ $setting->created_at }}</td>
                        <td>{{ $setting->updated_at }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editSettingModal" data-id="{{ $setting->id }}"
                                data-name="{{ $setting->name }}" data-value="{{ $setting->value }}">Modifica</button>
                            <form action="{{ route('settings.destroy', $setting) }}" method="POST" style="display:inline-block;">
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

    <!-- Modale per Modifica Impostazione -->
    <div class="modal fade" id="editSettingModal" tabindex="-1" aria-labelledby="editSettingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSettingModalLabel">Modifica Impostazione</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editSettingForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_value" class="form-label">Valore</label>
                            <input type="text" class="form-control" id="edit_value" name="value" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script per Gestire la Modale -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editSettingModal = document.getElementById('editSettingModal');
            editSettingModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');
                var value = button.getAttribute('data-value');

                var modal = this;
                modal.querySelector('#edit_name').value = name;
                modal.querySelector('#edit_value').value = value;

                var form = modal.querySelector('#editSettingForm');
                form.action = '{{ url('/dashboard/settings') }}/' + id;
            });
        });
    </script>
</x-dashboard-layout>