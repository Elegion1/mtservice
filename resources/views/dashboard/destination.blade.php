<x-dashboard-layout>

    <h1>Gestione Destinazioni</h1>
    <div class="d-flex flex-wrap gap-2 mb-3">
        <button id="destinationCreateBtn" class="btn btn-success">Crea destinazione</button>
        <a href="{{ route('dashboard.route') }}" class="btn btn-secondary">Mostra tratte</a>
    </div>
    <form class="d-none" id="destinationFormCreate" action="{{ route('destinations.store') }}" method="POST">
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
                <th>Slug</th>
                <th>Mostra</th>

                <th>Azione</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($destinations as $destination)
                <tr>
                    <td>{{ $destination->id }}</td>
                    <td>{{ $destination->name }}</td>
                    <td>{{ $destination->slug }}</td>
                    <td>{{ $destination->show ? 'Si' : 'No' }}</td>

                    <td>
                        <x-edit-button :id="'Destination'" :data="$destination" />
                        <x-delete-button :route="'destinations'" :model="$destination" />
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <!-- Modale per Modifica Destinazione -->
    <x-modal :id="'Destination'" :title="'Modifica destinazione'">
        <div class="mb-3">
            <input type="hidden" name="show" value="0">
            <label for="edit_show">Mostra</label>
            <input type="checkbox" class="form-check-input" id="edit_show" name="show" value="1">
        </div>
        <div class="mb-3">
            <label for="edit_name" class="form-label">Nome Destinazione</label>
            <input type="text" class="form-control form_input_focused" id="edit_name" name="name" required>
        </div>
    </x-modal>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var destinationCreateBtn = document.getElementById('destinationCreateBtn');
            var destinationFormCreate = document.getElementById('destinationFormCreate');

            destinationCreateBtn.addEventListener('click', function() {
                destinationFormCreate.classList.toggle('d-none');
                destinationCreateBtn.innerText = destinationFormCreate.classList.contains('d-none') ?
                    'Crea Destinazione' : 'Nascondi';
            });

        });
    </script>
</x-dashboard-layout>
