<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
    public function create()
    {
        $destinations = Destination::all();

        return view('dashboard.destination', compact('destinations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'show' => 'nullable|boolean',

        ]);

        // Genera slug univoco
        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;

        while (Destination::where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter++;
        }

        // Crea direttamente la destinazione
        Destination::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'show' => $validated['show'] ?? true,

        ]);

        return redirect()->route('dashboard.route')
            ->with('success', 'Destinazione creata con successo!');
    }

    public function update(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'show' => 'required|boolean',

        ]);

        // Genera slug univoco
        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;

        while (Destination::where('slug', $slug)
            ->where('id', '!=', $destination->id)
            ->exists()) {
            $slug = $originalSlug.'-'.$counter++;
        }

        $destination->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'show' => $validated['show'],

        ]);

        return redirect()->route('dashboard.destination')
            ->with('success', 'Destinazione aggiornata con successo!');
    }

    public function destroy(Destination $destination)
    {
        $destination->delete();

        return redirect()->route('dashboard.destination')->with('success', 'Destinazione eliminata con successo!');
    }
}
