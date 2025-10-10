<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function show($locale, $slug)
    {
        // Determina la colonna dello slug in base alla lingua
        $slugColumn = $locale === 'en' ? 'slug_en' : 'slug_it';

        // Trova il servizio visibile con lo slug corrispondente
        $service = Service::where($slugColumn, $slug)
            ->where('show', true)
            ->firstOrFail();

        return view('pages.services.show', compact('service'));
    }

    public function index()
    {
        $services = Service::with('images')->get(); // Carica anche le immagini associate

        return view('dashboard.service', compact('services'));
    }

    public function create()
    {
        return view('dashboard.create.service');
    }

    public function edit(Service $service)
    {
        return view('dashboard.edit.service', compact('service'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_it' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',

            'subtitle_it' => 'required|string|max:255',
            'subtitle_en' => 'nullable|string|max:255',

            'subtitleSec_it' => 'nullable|string|max:255',
            'subtitleSec_en' => 'nullable|string|max:255',

            'abstract_it' => 'nullable|string|max:255',
            'abstract_en' => 'nullable|string|max:255',

            'body_it' => 'required|string',
            'body_en' => 'nullable|string',

            'links' => 'nullable|string|max:255',

            'condition_it' => 'nullable|string',
            'condition_en' => 'nullable|string',

            'flag' => 'boolean',
            'show' => 'boolean',

        ]);

        $service = new Service($validated);

        // Slug IT (solo se esiste il titolo)
        if (! empty($validated['title_it'])) {
            $slugIt = Str::slug($validated['title_it']);
            $originalSlugIt = $slugIt;
            $counter = 1;
            while (Service::where('slug_it', $slugIt)->exists()) {
                $slugIt = $originalSlugIt.'-'.$counter++;
            }
            $service->slug_it = $slugIt;
        }

        // Slug EN (solo se esiste il titolo)
        if (! empty($validated['title_en'])) {
            $slugEn = Str::slug($validated['title_en']);
            $originalSlugEn = $slugEn;
            $counter = 1;
            while (Service::where('slug_en', $slugEn)->exists()) {
                $slugEn = $originalSlugEn.'-'.$counter++;
            }
            $service->slug_en = $slugEn;
        }

        $service->save();

        // Gestione immagini
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                Image::create([
                    'path' => $path,
                    'service_id' => $service->id,
                ]);
            }
        }

        return redirect()->route('dashboard.service')
            ->with('success', 'Servizio creato con successo.');
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title_it' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',

            'subtitle_it' => 'required|string|max:255',
            'subtitle_en' => 'nullable|string|max:255',

            'subtitleSec_it' => 'nullable|string|max:255',
            'subtitleSec_en' => 'nullable|string|max:255',

            'abstract_it' => 'nullable|string|max:255',
            'abstract_en' => 'nullable|string|max:255',

            'body_it' => 'required|string',
            'body_en' => 'nullable|string',

            'links' => 'nullable|string|max:255',

            'condition_it' => 'nullable|string',
            'condition_en' => 'nullable|string',

            'flag' => 'boolean',
            'show' => 'boolean',
        ]);

        $service->fill($validated);

        // Slug IT (solo se è presente e modificato)
        if (! empty($validated['title_it']) && $service->isDirty('title_it')) {
            $slugIt = Str::slug($validated['title_it']);
            $originalSlugIt = $slugIt;
            $counter = 1;
            while (Service::where('slug_it', $slugIt)->where('id', '!=', $service->id)->exists()) {
                $slugIt = $originalSlugIt.'-'.$counter++;
            }
            $service->slug_it = $slugIt;
        }

        // Slug EN (solo se è presente e modificato)
        if (! empty($validated['title_en']) && $service->isDirty('title_en')) {
            $slugEn = Str::slug($validated['title_en']);
            $originalSlugEn = $slugEn;
            $counter = 1;
            while (Service::where('slug_en', $slugEn)->where('id', '!=', $service->id)->exists()) {
                $slugEn = $originalSlugEn.'-'.$counter++;
            }
            $service->slug_en = $slugEn;
        }

        $service->save();

        // Gestione immagini
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                Image::create([
                    'path' => $path,
                    'service_id' => $service->id,
                ]);
            }
        }

        return redirect()->route('dashboard.service')
            ->with('success', 'Servizio aggiornato con successo.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('dashboard.service')->with('success', 'Servizio eliminato con successo.');
    }
}
