<?php

namespace App\Http\Controllers;

use App\Models\Car;
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

        Car::create([
            'name' => $request->name,
            'description' => $request->description,
            'img' => $request->file('img')->store('public/cars'),
            'price' => $request->price,
        ]);

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

        if ($request->file('img')) {
            Storage::delete($car->img);
            $img = $request->file('img')->store('public/cars');
        } else {
            $img = $car->img;
        }

        $car->update([
            'name' => $request->name,
            'description' => $request->description,
            'img' => $img,
            'price' => $request->price,
        ]);


        return redirect()->route('dashboard.car')->with('success', 'Auto aggiornata con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        // Verifica se l'auto ha un'immagine associata e elimina l'immagine
        if ($car->img) {
           Storage::delete($car->img);
        }

        // Elimina l'auto dal database
        $car->delete();

        return redirect()->route('dashboard.car')->with('success', 'Auto eliminata con successo!');
    }
}
