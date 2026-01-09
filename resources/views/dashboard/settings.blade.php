<x-dashboard-layout>

    @php
        $defaultSettings = [
            (object) ['name' => 'review_request_default_time'],
            (object) ['name' => 'review_request_delay_days'],
            (object) ['name' => 'email_notification'],
            (object) ['name' => 'log_max_character_length'],
            (object) ['name' => 'booking_pending_expire_time'],
            (object) ['name' => 'booking_rejected_notification'],
            (object) ['name' => 'create_customer'],
            (object) ['name' => 'send_whatsapp_message'],
            (object) ['name' => 'minimum_rent_days'],
            (object) ['name' => 'transfer_return_minimum_wait_time_minutes'],
            (object) ['name' => 'default_header_image'],
            (object) ['name' => 'whatsapp_access_token'],
            (object) ['name' => 'show_transfer'],
            (object) ['name' => 'show_escursioni'],
            (object) ['name' => 'show_noleggio'],
            (object) ['name' => 'vehicle_capacity'],
            (object) ['name' => 'garage_address'],
            (object) ['name' => 'hand_off_price_per_km'],

        ];

        $filteredSettings = collect($defaultSettings)->filter(function ($setting) use ($settings) {
            $existingSetting = $settings->firstWhere('name', $setting->name);
            return !$existingSetting || is_null($existingSetting->value);
        });
    @endphp

    <h1>Gestione Impostazioni</h1>

    <!-- Form per Aggiungere Nuova Impostazione -->
    <form action="{{ route('settings.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="mb-3 col-12 col-md-4">
                <label for="name" class="form-label">Nome Impostazione</label>
                <select name="name" id="name" class="form-select">
                    <option value="">Seleziona un'impostazione</option>
                    @foreach ($filteredSettings as $setting)
                        <option value="{{ $setting->name }}">{{ $setting->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 col-12 col-md-4">
                <label for="type" class="form-label">Tipo</label>
                <select name="type" id="type" class="form-select" required>
                    <option value="">Seleziona un tipo</option>
                    <option value="text">Testo</option>
                    <option value="date">Data</option>
                    <option value="time">Ora</option>
                    <option value="url">URL</option>
                    <option value="number">Numero</option>
                </select>
            </div>
            <div class="mb-3 col-12 col-md-4">
                <label for="value" class="form-label">Valore</label>
                <input type="text" class="form-control" id="value" name="value" required>
            </div>
            <div class="mb-3 col-12 d-flex align-items-end">
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
            <select name="type" id="edit_type" class="form-select" required>
                <option value="">Seleziona un tipo</option>
                <option value="text">Testo</option>
                <option value="date">Data</option>
                <option value="time">Ora</option>
                <option value="url">URL</option>
                <option value="number">Numero</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="edit_value" class="form-label">Valore</label>
            <input type="" class="form-control" id="edit_value" name="value">
        </div>
    </x-modal>

</x-dashboard-layout>
