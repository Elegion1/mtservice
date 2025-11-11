<x-dashboard-layout>
    <div class="container">
        <h1 class="mb-4">Phone Clicks</h1>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-light text-center">
                    <div class="card-body">
                        <h6>Total Clicks</h6>
                        <h3>{{ $stats['total_clicks'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-light text-center">
                    <div class="card-body">
                        <h6>Unique Numbers</h6>
                        <h3>{{ $stats['unique_numbers'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-light text-center">
                    <div class="card-body">
                        <h6>Today's Clicks</h6>
                        <h3>{{ $stats['today_clicks'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-light text-center">
                    <div class="card-body">
                        <h6>This Week</h6>
                        <h3>{{ $stats['this_week_clicks'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Numbers -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Top Clicked Numbers</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach ($topNumbers as $number)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $number->number }}
                            <span class="badge bg-primary rounded-pill">{{ $number->clicks }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Clicks Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>IP Address</th>
                                <th>Number</th>
                                <th>User Agent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clicks as $click)
                                <tr>
                                    <td>{{ $click->visited_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $click->ip_address }}</td>
                                    <td>{{ $click->details }}</td>
                                    <td>{{ Str::limit($click->user_agent, 50) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No phone clicks found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($clicks->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $clicks->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-dashboard-layout>
