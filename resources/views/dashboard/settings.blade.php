<x-dashboard-layout>

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
                <label for="type" class="form-label">Tipo</label>
                <input type="text" class="form-control" id="type" name="type" required>
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
                <th>Tipo</th>
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
                    <td>{{ $setting->type }}</td>
                    <td>{{ $setting->value }}</td>
                    <td>{{ $setting->created_at }}</td>
                    <td>{{ $setting->updated_at }}</td>
                    <td>
                        <x-edit-button :id="'Setting'" :data="$setting" />
                        <x-delete-button :route="'settings'" :model="$setting" />
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <!-- Modale per Modifica Impostazione -->
    <x-modal :id="'Setting'" :title="'Modifica impostazione'">
        <div class="mb-3">
            <label for="edit_name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="edit_name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="edit_type" class="form-label">Tipo</label>
            <input type="text" class="form-control" id="edit_type" name="type" required>
        </div>
        <div class="mb-3">
            <label for="edit_value" class="form-label">Valore</label>
            <input type="" class="form-control" id="edit_value" name="value">
        </div>
    </x-modal>

</x-dashboard-layout>
