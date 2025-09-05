<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the visits.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Visit::query();

        // Filter by date range if provided
        if ($request->filled('start_date')) {
            $query->whereDate('visited_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('visited_at', '<=', $request->end_date);
        }

        // Filter by IP if provided
        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }

        // Get paginated results
        $visits = $query->latest('visited_at')->paginate(20);

        // Get statistics
        $stats = [
            'total_visits' => number_format(Visit::count()),
            'unique_visitors' => number_format(Visit::distinct('ip_address')->count('ip_address')),
            'today_visits' => number_format(Visit::whereDate('visited_at', today())->count()),
            'this_week_visits' => number_format(Visit::whereBetween('visited_at', [now()->startOfWeek(), now()])->count()),
            'this_month_visits' => number_format(Visit::whereYear('visited_at', now()->year)
                ->whereMonth('visited_at', now()->month)
                ->count()),
        ];

        // Get popular pages
        $popularPages = Visit::select('url', DB::raw('count(*) as total'))
            ->groupBy('url')
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();

        return view('dashboard.visits.index', compact('visits', 'stats', 'popularPages'));
    }

    /**
     * Show the visit statistics dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $stats = [
            'total_visits' => number_format(Visit::count()),
            'unique_visitors' => number_format(Visit::distinct('ip_address')->count('ip_address')),
            'today_visits' => number_format(Visit::whereDate('visited_at', today())->count()),
            'this_week_visits' => number_format(Visit::whereBetween('visited_at', [now()->startOfWeek(), now()])->count()),
            'this_month_visits' => number_format(Visit::whereYear('visited_at', now()->year)
                ->whereMonth('visited_at', now()->month)
                ->count()),
        ];
        
        // Get visits by day for the last 30 days
        $visitsByDay = Visit::select(
                DB::raw('DATE(visited_at) as date'),
                DB::raw('count(*) as total')
            )
            ->where('visited_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Get top visited pages
        $topPages = Visit::select('url', DB::raw('count(*) as total'))
            ->groupBy('url')
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();
            
        // Get recent visits
        $recentVisits = Visit::latest('visited_at')
            ->take(10)
            ->get();
            
        return view('dashboard.visits.dashboard', compact('stats', 'visitsByDay', 'topPages', 'recentVisits'));
    }

    /**
     * Get visit statistics for charts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(Request $request)
    {
        $days = $request->get('days', 30); // Default to 30 days
        $endDate = now();
        $startDate = now()->subDays($days);

        $visits = Visit::select(
            DB::raw('DATE(visited_at) as date'),
            DB::raw('count(*) as total'),
            DB::raw('count(DISTINCT ip_address) as unique_visitors')
        )
        ->whereBetween('visited_at', [$startDate, $endDate])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return response()->json([
            'labels' => $visits->pluck('date'),
            'visits' => $visits->pluck('total'),
            'unique_visitors' => $visits->pluck('unique_visitors'),
        ]);
    }

    /**
     * Clear all visit records.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearAll()
    {
        Visit::truncate();
        return back()->with('success', 'All visit records have been cleared.');
    }

    /**
     * Remove the specified visit from storage.
     *
     * @param  \App\Models\Visit  $visit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Visit $visit)
    {
        $visit->delete();
        return back()->with('success', 'Visit record deleted successfully.');
    }
}
