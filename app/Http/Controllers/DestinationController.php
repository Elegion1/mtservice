<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;

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
            'name' => 'required',
            'show' => 'required | boolean',
        ]);

        $destination = new Destination();
        $destination->name = $validated['name'];
        $destination->save();

        return redirect()->route('dashboard.destination')->with('success', 'Destinazione creata con successo!');
    }

    public function update(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'name' => 'required',
            'show' => 'required | boolean',
        ]);

        $destination->update($validated);

        return redirect()->route('dashboard.destination')->with('success', 'Destinazione aggiornata con successo!');
    }

    public function destroy(Destination $destination)
    {
        $destination->delete();

        return redirect()->route('dashboard.destination')->with('success', 'Destinazione eliminata con successo!');
    }
}
