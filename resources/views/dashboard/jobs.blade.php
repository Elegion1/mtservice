<x-dashboard-layout>

    <div class="container-fluid mt-5">
        <h1>Job in Corso</h1>

        <!-- Job in Coda -->
        <h2>Job in Coda</h2>
        <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Codice prenotazione</th>
                    <th>Stato</th>
                    <th>Attempts</th>
                    <th>Stato</th>
                    <th>Data esecuzione</th>
                    <th>Data Creazione</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jobs as $job)
                    @php
                        // Decodifica il payload
                        $payload = json_decode($job->payload, true);

                        // Verifica se 'command' esiste e se è un oggetto serializzato
                        $commandData = isset($payload['data']['command'])
                            ? unserialize($payload['data']['command'])
                            : null;
                        $bookingId = $commandData ? $commandData->booking->id : null;

                        // Recupera la prenotazione, se presente
                        if ($bookingId) {
                            $booking = \App\Models\Booking::find($bookingId);
                        } else {
                            $booking = null;
                        }
                    @endphp
                    <tr>
                        <td>{{ $job->id }}</td>
                        <td>{{ $booking->code }}</td>
                        <td>{{ $booking->status }}</td>
                        <!-- Limitiamo il payload per leggibilità -->
                        <td>{{ $job->attempts }}</td>
                        <td>
                            @if ($job->reserved_at)
                                In Esecuzione
                            @else
                                In Coda
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::createFromTimestamp($job->available_at)->toDateTimeString() }}</td>
                        <td>{{ \Carbon\Carbon::createFromTimestamp($job->created_at)->toDateTimeString() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Job Falliti -->
        <h2>Job Falliti</h2>
        <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Queue</th>
                    <th>Payload</th>
                    <th>Eccezione</th>
                    <th>Data Fallimento</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($failedJobs as $failedJob)
                    @php
                        // Decodifica il payload
                        $payload = json_decode($failedJob->payload, true);

                        // Verifica se 'command' esiste e se è un oggetto serializzato
                        $commandData = isset($payload['data']['command'])
                            ? unserialize($payload['data']['command'])
                            : null;
                        $bookingId = $commandData ? $commandData->booking->id : null;

                        // Recupera la prenotazione, se presente
                        if ($bookingId) {
                            $booking = \App\Models\Booking::find($bookingId);
                        } else {
                            $booking = null;
                        }
                    @endphp
                    <tr>
                        <td>{{ $failedJob->id }}</td>
                        <td>{{ $failedJob->queue }}</td>
                        <td>{{ $booking->code }}</td>
                        <!-- Limitiamo il payload per leggibilità -->
                        <td>{{ \Illuminate\Support\Str::limit($failedJob->exception, 50) }}</td>
                        <!-- Limitiamo l'eccezione per leggibilità -->
                        <td>{{ \Carbon\Carbon::createFromTimestamp($failedJob->failed_at)->toDateTimeString() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-dashboard-layout>
