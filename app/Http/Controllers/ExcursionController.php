<?php

namespace App\Http\Controllers;

use App\Models\Excursion;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ExcursionController extends Controller
{
    public function index()
    {
        $excursions = Excursion::all();
        return view('dashboard.excursion', compact('excursions'));
    }

    public function show($locale, $name, $id)
    {
        $excursion = Excursion::findOrFail($id);

        return view('pages.excursions.show', compact('excursion'));
    }

    public function create()
    {
        return view('dashboard.create.excursion');
    }

    public function edit(Excursion $excursion)
    {
        return view('dashboard.edit.excursion', compact('excursion'));
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
}
