<?php

namespace App\Http\Controllers;

use App\Models\SeoMeta;
use Illuminate\Http\Request;

class SeoMetaController extends Controller
{
    protected function defaultSeoKeys(): array
    {
        return [
            'home',
            'noleggio',
            'transfer',
            'servizi',
            'escursioni',
            'prezziDestinazioni',
            'diconoDiNoi',
            'contattaci',
            'partners',
            'faq',
            'privacy',
        ];
    }

    public function index()
    {
        $defaults = $this->defaultSeoKeys();
        $seoMeta = SeoMeta::whereIn('page_key', $defaults)->orderBy('page_key')->get()->keyBy('page_key');

        return view('dashboard.seoMeta', compact('defaults', 'seoMeta'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_key' => 'required|string|max:255|unique:seo_metas,page_key',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        SeoMeta::create($validated);

        return redirect()->route('dashboard.seoMeta')->with('success', 'Meta SEO creato con successo.');
    }

    public function update(Request $request, SeoMeta $seoMeta)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $seoMeta->update($validated);

        return redirect()->route('dashboard.seoMeta')->with('success', 'Meta SEO aggiornato con successo.');
    }

    public function destroy(SeoMeta $seoMeta)
    {
        $seoMeta->delete();

        return redirect()->route('dashboard.seoMeta')->with('success', 'Meta SEO eliminato con successo.');
    }
}
