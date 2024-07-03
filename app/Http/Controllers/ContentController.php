<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Image;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::all();
        $contents = Content::with('images')->get(); // Carica anche le immagini associate
        return view('dashboard.content', compact('contents'));
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
        $content = Content::create($request->all());


        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                Image::create([
                    'path' => $path,
                    'content_id' => $content->id,
                ]);
            }
        }

        return redirect()->route('dashboard.content')->with('success', 'Contenuto creato con successo.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Content $content)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Content $content)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Content $content)
    {
        $content->update($request->all());

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('images', 'public');
                    Image::create([
                        'path' => $path,
                        'content_id' => $content->id,
                    ]);
                } else {
                    return back()->withErrors(['msg' => 'Errore nel caricamento del file: ' . $file->getErrorMessage()]);
                }
            }
        }

        return redirect()->route('dashboard.content')->with('success', 'Contenuto aggiornato con successo.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Content $content)
    {
        foreach ($content->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
        $content->delete();
        return redirect()->route('dashboard.content')->with('success', 'Contenuto eliminato con successo.');
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
