<x-dashboard-layout>

    <h1>Gestione Messaggi</h1>

    @if (request()->header('User-Agent') && preg_match('/Mobile|Android|iPhone/i', request()->header('User-Agent')))
        {{-- Vista Mobile (Card) --}}
        <div class="overflow-y-auto border-bottom rounded" style="height: 80vh">
            @foreach ($contacts as $contact)
                <div class="card mb-3">
                    <div class="card-body">
                        <p><strong>Nome:</strong> {{ $contact->nome }} {{ $contact->cognome }}</p>
                        <p><strong>Email:</strong> <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></p>
                        <p><strong>Telefono:</strong> <a href="tel:{{ $contact->telefono }}">{{ $contact->telefono }}</a>
                        </p>
                        <p><strong>Messaggio:</strong> {{ $contact->messaggio }}</p>
                        <p><strong>Letto:</strong>
                            <button class="btn btn-link p-0 m-0 align-baseline text-primary"
                                onclick="toggleReadStatus({{ $contact->id }})">
                                <i class="bi {{ $contact->read ? 'bi-eye' : 'bi-eye-slash' }}"></i>
                            </button>
                        </p>
                        <p><strong>Data di Aggiunta:</strong> {{ $contact->created_at }}</p>
                        <div class="d-flex justify-content-end">
                            <x-delete-button :route="'contacts'" :model="$contact" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Vista Desktop (Tabella) --}}
        <div class="table-responsive">
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
                                <x-delete-button :route="'contacts'" :model="$contact" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

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
