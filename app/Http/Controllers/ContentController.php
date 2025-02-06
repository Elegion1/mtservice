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
        return view('dashboard.create.content');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // // Validazione dei dati
        // $validatedData = $request->validate([
        //     'title_it' => 'nullable|string|max:255',
        //     'subtitle_it' => 'nullable|string',
        //     'body_it' => 'nullable|string',
        //     'links' => 'nullable|string',
        //     'order' => 'nullable|string',
        //     'show' => 'boolean',
        //     'page_id' => 'nullable|integer|exists:pages,id', // Assicurati che sia un intero e che esista nella tabella pages
        //     // Aggiungi altre regole di validazione se necessario
        // ]);

        // // Rimuovi 'page_id' se non è presente nel request e impostalo a null
        // if (empty($validatedData['page_id']) || $validatedData['page_id'] === 'Seleziona una pagina') {
        //     $validatedData['page_id'] = null; // Imposta page_id a null se non fornito
        // }

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
        return view('dashboard.edit.content', compact('content'));
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
        $content->delete();
        return redirect()->route('dashboard.content')->with('success', 'Contenuto eliminato con successo.');
    }
}
