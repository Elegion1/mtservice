<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::all();
        return view('dashboard.page', compact('pages'));
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
        $order = $request->input('order');
        if ($order) {
            Page::where('order', '>=', $order)->increment('order');
        } else {
            $order = Page::max('order') + 1;
        }

        $page = Page::create($request->all() + ['order' => $order]);

        return redirect()->route('dashboard.page')->with('success', 'Pagina creata con successo!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $newOrder = $request->input('order');
        if ($newOrder && $newOrder != $page->order) {
            Page::where('order', '>=', $newOrder)->increment('order');
        }

        $page->update($request->all());

        return redirect()->route('dashboard.page')->with('success', 'Pagina aggiornata con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('dashboard.page')->with('success', 'Pagina eliminata con successo.');
    }
}
