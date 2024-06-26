<?php

namespace App\Http\Controllers;

use App\Models\Excursion;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExcursionController extends Controller
{
    public function index() {
        $excursions = Excursion::all();
        return view('dashboard.excursion', compact('excursions'));
    }

    public function store(Request $request)
    {

        $excursion = Excursion::create($request->all());

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                Image::create([
                    'path' => $path,
                    'excursion_id' => $excursion->id,
                ]);
            }
        }

        return redirect()->route('dashboard.excursion')->with('success', 'Escursione creata con successo!');
    }

    public function update(Request $request, Excursion $excursion)
    {


        $excursion->update($request->all());

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                Image::create([
                    'path' => $path,
                    'excursion_id' => $excursion->id,
                ]);
            }
        }

        return redirect()->route('dashboard.excursion')->with('success', 'Escursione aggiornata con successo!');
    }

    public function destroy(Excursion $excursion)
    {
        foreach ($excursion->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
        $excursion->delete();
        return redirect()->route('dashboard.excursion')->with('success', 'Escursione eliminata con successo.');
    }

    public function deleteImage($id)
    {
        $image = Image::find($id);

        if ($image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'error' => 'Immagine non trovata'], 404);
    }
}
