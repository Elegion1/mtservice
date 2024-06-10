<x-dashboard-layout>
    <div class="container mt-5">
        <h2>Gestione Messaggi</h2>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Email</th>
                    <th>Telefono</th>
                    <th>Messaggio</th>
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
                        <td>{{ $contact->created_at }}</td>
                        <td>
                            <form action="{{ route('contacts.destroy', $contact) }}" method="POST"
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

</x-dashboard-layout>
