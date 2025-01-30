<x-dashboard-layout>

    <h1>Gestione Partner</h1>

    <button id="partnerCreateBtn" class="btn btn-success">Crea partner</button>

    <form id="partnerCreateForm" class="d-none" action="{{ route('partners.store') }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="mb-3 col-4">
                <label for="name" class="form-label">Nome Partner</label>
                <input type="text" class="form-control form_input_focused" id="name" name="name" required>
            </div>
            <div class="mb-3 col-4">
                <label for="link" class="form-label">Link</label>
                <input type="text" class="form-control form_input_focused" id="link" name="link" required>
            </div>
            <div class="mb-3 col-4">
                <label for="images" class="form-label">Immagini</label>
                <input type="file" class="form-control form_input_focused" id="images" name="images[]" multiple>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Aggiungi Partner</button>
    </form>
    <hr>
    <h2>Tutti i Partner</h2>
    <table class="table table-sm table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Link</th>
                <th>Immagini</th>
                <th>Data di aggiunta</th>
                <th>Data di modifica</th>
                <th>Azione</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($partners as $partner)
                <tr>
                    <td>{{ $partner->id }}</td>
                    <td>{{ $partner->name }}</td>
                    <td><a href="{{ $partner->link }}" target="_blank">{{ $partner->link }}</a></td>
                    <td>
                        @if ($partner->images->count() > 0)
                            <small>{{ $partner->images->count() }} immagini caricate</small>
                        @else
                            <small>Nessuna immagine</small>
                        @endif
                    </td>
                    <td>{{ $partner->created_at }}</td>
                    <td>{{ $partner->updated_at }}</td>
                    <td>
                        <x-edit-button :id="'Partner'" :data="$partner" />
                        <x-delete-button :route="'partners'" :model="$partner" />
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <!-- Modale per Modifica Partner -->
    <x-modal :id="'Partner'" :title="'Modifica partner'">
        <div class="mb-3">
            <label for="edit_name" class="form-label">Nome Partner</label>
            <input type="text" class="form-control form_input_focused" id="edit_name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="edit_link" class="form-label">Link</label>
            <input type="text" class="form-control form_input_focused" id="edit_link" name="link" required>
        </div>
        <div class="mb-3">
            <label for="edit_images" class="form-label">Aggiungi nuove immagini</label>
            <input type="file" class="form-control form_input_focused" id="edit_images" name="images[]" multiple>
        </div>
        <div class="mb-3">
            <label for="edit_current_images" class="form-label">Immagini Caricate</label>
            <div id="edit-current-images">
                <!-- Anteprime delle immagini esistenti verranno aggiunte qui -->
            </div>
        </div>
    </x-modal>

    <!-- JavaScript per gestire il modale di modifica -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var partnerCreateBtn = document.getElementById('partnerCreateBtn');
            var partnerCreateForm = document.getElementById('partnerCreateForm');
            partnerCreateBtn.addEventListener('click', () => {
                partnerCreateForm.classList.toggle('d-none');
                partnerCreateBtn.innerHTML = partnerCreateForm.classList.contains('d-none') ?
                    'Crea partner' : 'Nascondi';
            });

        });
    </script>
</x-dashboard-layout>
