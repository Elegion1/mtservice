<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cars = Car::all();
        return view('dashboard.car', compact('cars'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $validated = $request->validate([
        //     'name' => 'required',
        //     'description' => 'required',
        //     'img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        //     'price' => 'required|numeric',
        // ]);

        // $imageName = time() . '.' . $request->img->extension();
        // $request->img->move(public_path('/storage/cars'), $imageName);

        // $car = new Car();
        // $car->name = $validated['name'];
        // $car->description = $validated['description'];
        // $car->img = $imageName;
        // $car->price = $validated['price'];
        // $car->save();

        $car = Car::create($request->all());
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                Image::Create([
                    'path' => $path,
                    'car_id' => $car->id,
                ]);
            }
        }

        return redirect()->route('dashboard.car')->with('success', 'Auto creata con successo!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        // $validated = $request->validate([
        //     'name' => 'required',
        //     'description' => 'required',
        //     'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        //     'price' => 'required|numeric',
        // ]);

        // // Verifica se è stata caricata una nuova immagine
        // if ($request->hasFile('img')) {
        //     // Elimina l'immagine precedente
        //     if ($car->img) {
        //         $imagePath = Storage::delete('public/cars/' . $car->img);
        //         if (file_exists($imagePath)) {
        //             unlink($imagePath);
        //         }
        //     }

        //     // Carica la nuova immagine
        //     $imageName = time() . '.' . $request->img->extension();
        //     $request->img->move(public_path('/storage/cars'), $imageName);
        //     $car->img = $imageName;
        // }

        // $car->update($validated);


        $car->update($request->all());

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                Image::Create([
                    'path' => $path,
                    'car_id' => $car->id,
                ]);
            }
        }


        return redirect()->route('dashboard.car')->with('success', 'Auto aggiornata con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Verifica se l'auto ha un'immagine associata e elimina l'immagine
        $car = Car::findOrFail($id);

        // Elimina l'auto dal database
        $car->delete();

        return redirect()->route('dashboard.car')->with('success', 'Auto eliminata con successo!');
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
