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
        return view('dashboard.route', compact('destinations', 'routes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'distance' => 'required|numeric',
            'price' => 'required|numeric',
            'duration' => 'required|numeric',
            'price_increment' => 'nullable|numeric',
            'show' => 'nullable|boolean',
        ]);
        
        $validated['show'] = $validated['show'] ?? 0; // Imposta un valore predefinito per 'show'

        $route = new Route();
        $route->departure_id = $validated['departure_id'];
        $route->arrival_id = $validated['arrival_id'];
        $route->distance = $validated['distance'];
        $route->price = $validated['price'];
        $route->duration = $validated['duration'];
        $route->price_increment = $validated['price_increment'];
        $route->show = $validated['show'];
        $route->save();

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
        $validated = $request->validate([
            'distance' => 'required|numeric',
            'price' => 'required|numeric',
            'duration' => 'required|numeric',
            'price_increment' => 'nullable|numeric',
            'show' => 'nullable|boolean',
        ]);
        
        $validated['show'] = $validated['show'] ?? 0; // Imposta un valore predefinito per 'show'
        
        $route->update($validated);

        return redirect()->route('dashboard.route')->with('success', 'Rotta aggiornata con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Route $route)
    {
        $route->delete();

        return redirect()->route('dashboard.route')->with('success', 'Rotta eliminata con successo!');
    }
}
