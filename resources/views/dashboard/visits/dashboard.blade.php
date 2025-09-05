<x-dashboard-layout>


    <div class="container">
        <h1 class="mb-4">Visit Statistics Dashboard</h1>

        <!-- Stats Row -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Visits</h5>
                        <h2 class="mb-0">{{ $stats['total_visits'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Unique Visitors</h5>
                        <h2 class="mb-0">{{ $stats['unique_visitors'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Today's Visits</h5>
                        <h2 class="mb-0">{{ $stats['today_visits'] }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h5 class="card-title">This Week</h5>
                        <h2 class="mb-0">{{ $stats['this_week_visits'] }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Visits Overview (Last 30 Days)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="visitsChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Top Pages</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach ($topPages as $page)
                                <a href="{{ $page->url }}" target="_blank"
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    {{ Str::limit($page->url, 30) }}
                                    <span class="badge bg-primary rounded-pill">{{ $page->total }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Visits -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Visits</h5>
                <a href="{{ route('visits.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>IP Address</th>
                                <th>Page</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentVisits as $visit)
                                <tr>
                                    <td>{{ $visit->visited_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $visit->ip_address }}</td>
                                    <td title="{{ $visit->url }}">
                                        <a href="{{ $visit->url }}" target="_blank">
                                            {{ Str::limit($visit->url, 40) }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Visits Chart
        const ctx = document.getElementById('visitsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($visitsByDay->pluck('date')),
                datasets: [{
                    label: 'Visits',
                    data: @json($visitsByDay->pluck('total')),
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    pointRadius: 3,
                    pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointBorderColor: 'rgba(78, 115, 223, 1)',
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>



</x-dashboard-layout>
