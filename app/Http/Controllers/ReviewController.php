<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product; 
use App\Models\User;    
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $reviews = Review::with(['product', 'user'])->latest()->paginate(10);
        $products = Product::all(); // For the create modal dropdown
        $users = User::all();       // For the create modal dropdown
        return view('reviews.index', compact('reviews', 'products', 'users'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'is_approved' => 'boolean', // Handled by hidden input for unchecked state
        ]);

        Review::create($validatedData);

        return redirect()->route('reviews.index')
                         ->with('success', 'Review created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review): View
    {
        $review->load(['product', 'user']); // Eager load product and user for display
        return view('reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review): View
    {
        $products = Product::all(); // For the dropdown
        $users = User::all();       // For the dropdown
        return view('reviews.edit', compact('review', 'products', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review): RedirectResponse
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'is_approved' => 'boolean',
        ]);

        $review->update($validatedData);

        return redirect()->route('reviews.index')
                         ->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return redirect()->route('reviews.index')
                         ->with('success', 'Review deleted successfully.');
    }

    /**
     * Toggle the approval status of a review.
     */
    public function toggleApproval(Review $review): RedirectResponse
    {
        $review->update(['is_approved' => !$review->is_approved]);
        $status = $review->is_approved ? 'approved' : 'unapproved';

        return redirect()->route('reviews.index')
                         ->with('success', "Review {$review->id} has been {$status}.");
    }
}
