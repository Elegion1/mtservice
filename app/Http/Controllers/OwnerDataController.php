<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\OwnerData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OwnerDataController extends Controller
{
    /**
     * Mostra l'elenco degli owner.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ownerData = OwnerData::with('images')->first(); // Supponendo che ci sia solo un record di owner
        return view('dashboard.ownerData', compact('ownerData'));
    }

    /**
     * Mostra il form per creare un nuovo owner.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Salva il nuovo owner nel database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ownerData = OwnerData::create($request->all());
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                Image::Create([
                    'path' => $path,
                    'owner_data_id' => $ownerData->id,
                ]);
            }
        }

        return redirect()->route('dashboard.ownerData')->with('success', 'Owner creato con successo.');
    }

    /**
     * Mostra il form per modificare l'owner specificato.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {

    }

    /**
     * Aggiorna l'owner nel database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OwnerData $ownerData)
    {
        $ownerData->update($request->all());
        // dd($ownerData->id);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                Image::Create([
                    'path' => $path,
                    'owner_data_id' => $ownerData->id,
                ]);
            }
        }

        return redirect()->route('dashboard.ownerData')->with('success', 'Owner aggiornato con successo.');
    }

    /**
     * Rimuove l'owner specificato dal database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $owner = OwnerData::findOrFail($id);
        $owner->delete();

        return redirect()->route('dashboard.ownerData')->with('success', 'Owner eliminato con successo.');
    }


}
