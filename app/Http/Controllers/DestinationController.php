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

        $destination = new Destination;
        $destination->name = $validated['name'];
        $slug = Str::slug($validated['name']);
        $counter = 1;
        $originalSlug = $slug;

        while (Destination::where('slug', $slug)->where('id', '!=', $destination->id)->exists()) {
            $slug = $originalSlug.'-'.$counter++;
        }

        $destination->slug = $slug;
        $destination->show = $validated['show'] ?? true;    // opzionale: default true
        $destination->save();

        return redirect()->route('dashboard.route')->with('success', 'Destinazione creata con successo!');
    }

    public function update(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'show' => 'required|boolean',
        ]);

        // Aggiorna il nome e lo slug
        $destination->name = $validated['name'];
        $slug = Str::slug($validated['name']);
        $counter = 1;
        $originalSlug = $slug;

        while (Destination::where('slug', $slug)->where('id', '!=', $destination->id)->exists()) {
            $slug = $originalSlug.'-'.$counter++;
        }

        $destination->slug = $slug;
        $destination->show = $validated['show'];
        $destination->save();

        return redirect()->route('dashboard.destination')
            ->with('success', 'Destinazione aggiornata con successo!');
    }

    public function destroy(Destination $destination)
    {
        $destination->delete();

        return redirect()->route('dashboard.destination')->with('success', 'Destinazione eliminata con successo!');
    }
}
