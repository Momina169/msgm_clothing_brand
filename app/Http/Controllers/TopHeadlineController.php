<?php

namespace App\Http\Controllers;

use App\Models\TopHeadline;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TopHeadlineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $topHeadlines = TopHeadline::latest()->paginate(10);
        return view('top_headlines.index', compact('topHeadlines'));
    }

    /**
     * Show the form for creating a new resource.
     * (This will typically be part of the index page modal)
     */
    public function create(): View
    {
        return view('top_headlines.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'Headline' => 'required|string|max:255',
        ]);

        TopHeadline::create($validatedData);

        return redirect()->route('top_headlines.index')
                         ->with('success', 'Top Headline created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TopHeadline $topHeadline): View
    {
        return view('top_headlines.show', compact('topHeadline'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TopHeadline $topHeadline): View
    {
        return view('top_headlines.edit', compact('topHeadline'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TopHeadline $topHeadline): RedirectResponse
    {
        $validatedData = $request->validate([
            'Headline' => 'required|string|max:255',
        ]);

        $topHeadline->update($validatedData);

        return redirect()->route('top_headlines.index')
                         ->with('success', 'Top Headline updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TopHeadline $topHeadline): RedirectResponse
    {
        $topHeadline->delete();

        return redirect()->route('top_headlines.index')
                         ->with('success', 'Top Headline deleted successfully.');
    }
}
