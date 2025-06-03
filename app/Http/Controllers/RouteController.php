<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Destination;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $destinations = Destination::where('show', 1)->get();
        $routes = Route::with(['departure', 'arrival'])->get();

        $groupedRoutes = [];
        foreach ($routes as $route) {
            $reverseRoute = $route->reverseRoute();
            if ($reverseRoute && !isset($groupedRoutes[$reverseRoute->id])) {
                $groupedRoutes[$route->id] = [
                    'route' => $route,
                    'reverseRoute' => $reverseRoute
                ];
            }
        }
        return view('dashboard.route', compact('destinations', 'groupedRoutes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'departure_id' => 'required|exists:destinations,id',
            'arrival_id' => 'required|exists:destinations,id',
            'distance' => 'required|numeric',
            'price' => 'required|numeric',
            'duration' => 'required|numeric',
            'price_increment' => 'nullable|numeric',
            'show' => 'nullable|boolean',
            'increment_passengers' => 'nullable|numeric',
        ]);

        // Verifica che departure_id e arrival_id siano diversi
        if ($validated['departure_id'] == $validated['arrival_id']) {
            return redirect()->back()->withErrors(['departure_id' => 'Partenza e arrivo non possono essere uguali.']);
        }

        // Controlla se esiste già una rotta con la stessa partenza e arrivo
        $existingRoute = Route::where('departure_id', $validated['departure_id'])
            ->where('arrival_id', $validated['arrival_id'])
            ->exists();

        if ($existingRoute) {
            return redirect()->back()->withErrors(['departure_id' => 'Questa rotta esiste già.']);
        }

        $validated['show'] = $validated['show'] ?? 0;
        $validated['price_increment'] = $validated['price_increment'] ?? 0;

        // Creazione della nuova rotta
        Route::create($validated);

        if (!Route::where('departure_id', $validated['arrival_id'])->where('arrival_id', $validated['departure_id'])->exists()) {
            Route::create([
                'departure_id' => $validated['arrival_id'],
                'arrival_id' => $validated['departure_id'],
                'price' => $validated['price'],
                'price_increment' => $validated['price_increment'],
                'duration' => $validated['duration'],
                'distance' => $validated['distance'],
                'show' => $validated['show'],
                'increment_passengers' => $validated['increment_passengers'] ?? 4,
            ]);
        }

        return redirect()->route('dashboard.route')->with('success', 'Rotta creata con successo!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Route $route)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Route $route)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Route $route)
    {
        $validatedData = $request->validate([
            'price' => 'required|numeric',
            'price_increment' => 'required|numeric',
            'duration' => 'required|numeric',
            'distance' => 'required|numeric',
            'show' => 'required|boolean',
            'increment_passengers' => 'required|numeric',
        ]);

        // Aggiorna la rotta originale
        $route->update($validatedData);

        // Trova la rotta di ritorno
        $returnRoute = Route::where('departure_id', $route->arrival_id)
            ->where('arrival_id', $route->departure_id)
            ->first();

        if ($returnRoute) {
            $returnRoute->update($validatedData);
        }

        return redirect()->back()->with('success', 'Rotta aggiornata con successo');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Route $route)
    {
        // Trova la rotta inversa
        $returnRoute = Route::where('departure_id', $route->arrival_id)
            ->where('arrival_id', $route->departure_id)
            ->first();

        if ($returnRoute) {
            $returnRoute->delete();
        }

        $route->delete();

        return redirect()->back()->with('success', 'Rotta eliminata con successo');
    }
}
