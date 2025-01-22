<x-dashboard-layout>
    <div class="container-fluid mt-5">
        <h1>Gestione Messaggi</h1>
        {{-- <div class="d-flex mb-3">
            <button id="mark-all-read" class="btn btn-success me-2">Segna tutti come letti</button>
            <button id="mark-all-unread" class="btn btn-warning">Segna tutti come non letti</button>
        </div> --}}

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Email</th>
                    <th>Telefono</th>
                    <th>Messaggio</th>
                    <th>Letto</th>
                    <th>Data di Aggiunta</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contacts as $contact)
                    <tr>
                        <td>{{ $contact->id }}</td>
                        <td>{{ $contact->nome }}</td>
                        <td>{{ $contact->cognome }}</td>
                        <td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                        <td><a href="tel:{{ $contact->telefono }}">{{ $contact->telefono }}</a></td>
                        <td>{{ $contact->messaggio }}</td>
                        <td class="fs-5 text-primary">
                            <button class="btn btn-link p-0 m-0 align-baseline text-primary"
                                onclick="toggleReadStatus({{ $contact->id }})">
                                <i class="bi {{ $contact->read ? 'bi-eye' : 'bi-eye-slash' }}"></i>
                            </button>
                        </td>
                        <td>{{ $contact->created_at }}</td>
                        <td>
                            <x-delete-button :route="'contacts.destroy'" :model="$contact" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function toggleReadStatus(contactId) {
            fetch(`/dashboard/contacts/${contactId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Errore durante l\'aggiornamento dello stato.');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Aggiorna visivamente l'icona
                        const icon = document.querySelector(`[onclick="toggleReadStatus(${contactId})"] i`);
                        if (icon) {
                            if (data.read) {
                                icon.classList.remove('bi-eye-slash');
                                icon.classList.add('bi-eye');
                            } else {
                                icon.classList.remove('bi-eye');
                                icon.classList.add('bi-eye-slash');
                            }
                        }
                    } else {
                        alert('Errore durante l\'aggiornamento dello stato.');
                    }
                })
                .catch(error => console.error('Errore:', error));
        }
    </script>
</x-dashboard-layout>
