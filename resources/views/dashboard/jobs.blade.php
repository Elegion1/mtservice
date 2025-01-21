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

                        // Verifica se il job è un tipo che ha una prenotazione
                        if (isset($payload['data']['command'])) {
                            $commandData = unserialize($payload['data']['command']);

                            // Verifica se 'booking' è presente nel comando
                            if (isset($commandData->booking)) {
                                $bookingId = $commandData->booking->id;
                                $booking = \App\Models\Booking::find($bookingId);
                            } else {
                                $booking = null;
                            }
                        } else {
                            $booking = null;
                        }
                    @endphp
                    <tr>
                        <td>{{ $job->id }}</td>
                        <td>{{ $booking ? $booking->code : 'N/A' }}</td>
                        <td>{{ $booking ? $booking->status : 'N/A' }}</td>
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
                        // Verifica se il job è un tipo che ha una prenotazione
                        if (isset($payload['data']['command'])) {
                            $commandData = unserialize($payload['data']['command']);

                            // Verifica se 'booking' è presente nel comando
                            if (isset($commandData->booking)) {
                                $bookingId = $commandData->booking->id;
                                $booking = \App\Models\Booking::find($bookingId);
                            } else {
                                $booking = null;
                            }
                        } else {
                            $booking = null;
                        }

                        $pattern = '/: (.*?) in/';
                        preg_match($pattern, $failedJob->exception, $matches);

                        if (isset($matches[1])) {
                            $error = $matches[1];
                            $exceptionMessage = $error; // Mostra l'errore estratto
                        }

                    @endphp
                    <tr>
                        <td>{{ $failedJob->id }}</td>
                        <td>{{ $failedJob->queue }}</td>
                        <td>{{ $payload['displayName'] }}</td>
                        <td>{{ $exceptionMessage }}</td>
                        <td>{{ $failedJob->failed_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-dashboard-layout>
