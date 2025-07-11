<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function show($locale, $title, $id)
    {

        $service = Service::where('id', $id)
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
        $service = Service::create($request->all());

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                Image::create([
                    'path' => $path,
                    'service_id' => $service->id,
                ]);
            }
        }

        return redirect()->route('dashboard.service')->with('success', 'Servizio creato con successo.');
    }

    public function update(Request $request, Service $service)
    {
        $service->update($request->all());

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('images', 'public');
                Image::create([
                    'path' => $path,
                    'service_id' => $service->id,
                ]);
            }
        }

        return redirect()->route('dashboard.service')->with('success', 'Servizio aggiornato con successo.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('dashboard.service')->with('success', 'Servizio eliminato con successo.');
    }
}
