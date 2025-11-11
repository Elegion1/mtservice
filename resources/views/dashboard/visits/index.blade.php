<x-dashboard-layout>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Visit Logs</h1>
            <div>
                <a href="{{ route('visits.dashboard') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                </a>
                <a href="{{ route('visits.phone_clicks') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-telephone me-1"></i> Phone Clicks
                </a>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                    data-bs-target="#clearVisitsModal">
                    <i class="fas fa-trash-alt me-1"></i> Clear All
                </button>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Filters</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('visits.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date"
                            value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="ip_address" class="form-label">IP Address</label>
                        <input type="text" class="form-control" id="ip_address" name="ip_address"
                            placeholder="Filter by IP" value="{{ request('ip_address') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('visits.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="card-subtitle mb-2 text-muted">Total Visits</h6>
                        <h3 class="mb-0">{{ $stats['total_visits'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="card-subtitle mb-2 text-muted">Unique Visitors</h6>
                        <h3 class="mb-0">{{ $stats['unique_visitors'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="card-subtitle mb-2 text-muted">This Week</h6>
                        <h3 class="mb-0">{{ $stats['this_week_visits'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="card-subtitle mb-2 text-muted">This Month</h6>
                        <h3 class="mb-0">{{ $stats['this_month_visits'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visits Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>IP Address</th>
                                <th>Page</th>
                                <th>User Agent</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($visits as $visit)
                                <tr>
                                    <td>{{ $visit->visited_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $visit->ip_address }}</td>
                                    <td>
                                        <a href="{{ $visit->url }}" target="_blank"
                                            class="text-truncate d-inline-block" style="max-width: 200px;"
                                            title="{{ $visit->url }}">
                                            {{ $visit->url }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 200px;"
                                            title="{{ $visit->user_agent }}">
                                            {{ $visit->user_agent }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('visits.destroy', $visit) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to delete this visit record?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No visits found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($visits->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $visits->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Clear All Modal -->
    <div class="modal fade" id="clearVisitsModal" tabindex="-1" aria-labelledby="clearVisitsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clearVisitsModalLabel">Confirm Clear All Visits</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete all visit records? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('visits.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Yes, Clear All</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
