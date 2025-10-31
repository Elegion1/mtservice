<?php

namespace App\Http\Controllers;

use App\Models\Excursion;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExcursionController extends Controller
{
    public function index()
    {
        $excursions = Excursion::all();

        return view('dashboard.excursion', compact('excursions'));
    }

    public function show($locale,$slug)
    {
        $slugColumn = $locale === 'en' ? 'slug_en' : 'slug_it';

        $excursion = Excursion::where($slugColumn, $slug)
            ->where('show', true)
            ->first();

        // fallback: se non trova lo slug nella lingua attuale, prova l'altra lingua
        if (! $excursion) {
            $fallbackColumn = $locale === 'en' ? 'slug_it' : 'slug_en';
            $excursion = Excursion::where($fallbackColumn, $slug)
                ->where('show', true)
                ->firstOrFail();
        }

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
        $validated = $request->validate(
            [
                'name_it' => ['required', 'string', 'max:255'],
                'name_en' => ['nullable', 'string', 'max:255'],

                'price_increment' => ['required', 'numeric', 'min:0'],
                'price' => ['required', 'numeric', 'min:0'],

                'abstract_it' => ['nullable', 'string', 'max:255'],
                'abstract_en' => ['nullable', 'string', 'max:255'],

                'description_it' => ['required', 'string'],
                'description_en' => ['nullable', 'string'],

                'duration' => ['required', 'string', 'max:255'],

                'show' => ['boolean'],
                'increment_passengers' => ['integer', 'min:1'],

            ]
        );

        $excursion = new Excursion($validated);

        // Slug IT (solo se esiste il titolo)
        if (! empty($validated['name_it'])) {
            $slugIt = Str::slug($validated['name_it']);
            $originalSlugIt = $slugIt;
            $counter = 1;
            while (Excursion::where('slug_it', $slugIt)->exists()) {
                $slugIt = $originalSlugIt.'-'.$counter++;
            }
            $excursion->slug_it = $slugIt;
        }

        // Slug EN (solo se esiste il titolo)
        if (! empty($validated['name_en'])) {
            $slugEn = Str::slug($validated['name_en']);
            $originalSlugEn = $slugEn;
            $counter = 1;
            while (Excursion::where('slug_en', $slugEn)->exists()) {
                $slugEn = $originalSlugEn.'-'.$counter++;
            }
            $excursion->slug_en = $slugEn;
        }

        $excursion->save();

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
        $validated = $request->validate(
            [
                'name_it' => ['required', 'string', 'max:255'],
                'name_en' => ['nullable', 'string', 'max:255'],

                'price_increment' => ['required', 'numeric', 'min:0'],
                'price' => ['required', 'numeric', 'min:0'],

                'abstract_it' => ['nullable', 'string', 'max:255'],
                'abstract_en' => ['nullable', 'string', 'max:255'],

                'description_it' => ['required', 'string'],
                'description_en' => ['nullable', 'string'],

                'duration' => ['required', 'string', 'max:255'],

                'show' => ['boolean'],
                'increment_passengers' => ['integer', 'min:1'],

            ]
        );

        $excursion->fill($validated);

        // Slug IT (solo se esiste il titolo)
        if (! empty($validated['name_it'])) {
            $slugIt = Str::slug($validated['name_it']);
            $originalSlugIt = $slugIt;
            $counter = 1;
            while (Excursion::where('slug_it', $slugIt)->exists()) {
                $slugIt = $originalSlugIt.'-'.$counter++;
            }
            $excursion->slug_it = $slugIt;
        }
        // Slug EN (solo se esiste il titolo)
        if (! empty($validated['name_en'])) {
            $slugEn = Str::slug($validated['name_en']);
            $originalSlugEn = $slugEn;
            $counter = 1;
            while (Excursion::where('slug_en', $slugEn)->exists()) {
                $slugEn = $originalSlugEn.'-'.$counter++;
            }
            $excursion->slug_en = $slugEn;
        }

        $excursion->save();

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
        $excursion->delete();

        return redirect()->route('dashboard.excursion')->with('success', 'Escursione eliminata con successo.');
    }
}
