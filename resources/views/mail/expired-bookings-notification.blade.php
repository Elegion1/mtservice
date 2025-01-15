<x-mail-layout>
    <h1>Notifica Prenotazioni Scadute</h1>
    <p>Ciao,</p>
    <p>Ti informiamo che le seguenti prenotazioni sono state automaticamente recesse perché non sono state confermate
        entro il tempo limite stabilito.</p>

    <p><strong>Numero totale di prenotazioni scadute:</strong> {{ $expiredBookings->count() }}</p>

    <table>
        <thead>
            <tr>
                <th>Codice</th>
                <th>Cliente</th>
                <th>Telefono</th>
                <th>Data di Creazione</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expiredBookings as $booking)
                <tr>
                    <td>{{ $booking->code }}</td>
                    <td>{{ $booking->name }} {{ $booking->surname }}</td>
                    <td>{{ $booking->phone }}</td>
                    <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    
</x-mail-layout>
