<x-dashboard-layout>
    <div class="container-fluid">
        <h1>Visualizzazione Logs</h1>
        @if (count($logEntries) > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Entry</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logEntries as $log)
                        @php
                            // Estrai il timestamp
                            preg_match('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]/', $log, $timestampMatch);
                            $timestamp = $timestampMatch[0] ?? 'N/A';
                        @endphp
                        <tr>
                            <td>{{ $timestamp }}</td>
                            <td>
                                <pre style="white-space: pre-wrap;">{{ $log }}</pre>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Non ci sono logs disponibili.</p>
        @endif
    </div>
</x-dashboard-layout>