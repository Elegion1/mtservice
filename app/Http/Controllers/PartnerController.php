<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $partners = Partner::with('images')->get(); // Carica anche le immagini associate
        return view('dashboard.partner', compact('partners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $partner = Partner::create($request->all());

        if($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                Image::Create([
                    'path' => $path,
                    'partner_id' => $partner->id,
                ]);
            }
        }

        return redirect()->route('dashboard.partner')->with('success', 'Partner creato con successo');
    }

    /**
     * Display the specified resource.
     */
    public function show(Partner $partner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partner $partner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Partner $partner)
    {
        $partner->update($request->all());

        if($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                Image::Create([
                    'path' => $path,
                    'partner_id' => $partner->id,
                ]);
            }
        }

        return redirect()->route('dashboard.partner')->with('success', 'Partner aggiornato con successo');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partner $partner)
    {
        foreach ($partner->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
        $partner->delete();
        return redirect()->route('dashboard.partner')->with('success', 'Partner eliminato con successo.');
    }

}
