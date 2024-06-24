<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create()
    {
        return view('dashboard.review');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'title' => 'required',
            'body' => 'required',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review = new Review();
        $review->name = $validated['name'];
        $review->title = $validated['title'];
        $review->body = $validated['body'];
        $review->rating = $validated['rating'];
        $review->save();

        return redirect()->route('dashboard.review')->with('success', 'Recensione creata con successo!');
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'name' => 'required',
            'title' => 'required',
            'body' => 'required',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review->update($validated);

        return redirect()->route('dashboard.review')->with('success', 'Recensione aggiornata con successo!');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('dashboard.review')->with('success', 'Recensione eliminata con successo!');
    }
}
