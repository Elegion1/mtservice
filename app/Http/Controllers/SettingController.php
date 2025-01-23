<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Visualizza la pagina delle impostazioni.
     */
    public function index()
    {
        $settings = Setting::all();
        return view('dashboard.settings', compact('settings'));
    }

    /**
     * Salva una nuova impostazione.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255|unique:settings,name',
            'value' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);

        Setting::create($request->only(['name', 'value', 'type']));

        return redirect()->route('dashboard.settings')->with('success', 'Impostazione aggiunta con successo.');
    }

    /**
     * Aggiorna un'impostazione esistente.
     */
    public function update(Request $request, Setting $setting)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:settings,name,' . $setting->id,
            'value' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);

        $setting->update($request->only(['name', 'value', 'type']));

        return redirect()->route('dashboard.settings')->with('success', 'Impostazione aggiornata con successo.');
    }

    /**
     * Elimina un'impostazione.
     */
    public function destroy(Setting $setting)
    {
        $setting->delete();

        return redirect()->route('dashboard.settings')->with('success', 'Impostazione eliminata con successo.');
    }
}
