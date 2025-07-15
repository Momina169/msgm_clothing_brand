<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product; 
use App\Models\User;   
use App\Models\Guest;   
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $sales = Sale::with(['product', 'user', 'guest'])->latest()->paginate(10);

        $products = Product::all();
        $users = User::all();
        $guests = Guest::all();

        return view('sales.index', compact('sales', 'products', 'users', 'guests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price_at_sale' => 'required|numeric|min:0',
            'sale_date' => 'nullable|date',
            'user_id' => 'nullable|exists:users,id',
            'guest_id' => 'nullable|exists:guests,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        
        if (empty($validatedData['user_id']) && empty($validatedData['guest_id'])) {
            
        }

        
        if (empty($validatedData['sale_date'])) {
            $validatedData['sale_date'] = now();
        }

        Sale::create($validatedData);

        return redirect()->route('sales.index')
                         ->with('success', 'Sale recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale): View
    {
        $sale->load(['product', 'user', 'guest']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale): View
    {
        $products = Product::all();
        $users = User::all();
        $guests = Guest::all();
        return view('sales.edit', compact('sale', 'products', 'users', 'guests'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale): RedirectResponse
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price_at_sale' => 'required|numeric|min:0',
            'sale_date' => 'nullable|date',
            'user_id' => 'nullable|exists:users,id',
            'guest_id' => 'nullable|exists:guests,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        if (empty($validatedData['user_id']) && empty($validatedData['guest_id'])) {
        }
        if (empty($validatedData['sale_date'])) {
            $validatedData['sale_date'] = now();
        }

        $sale->update($validatedData);

        return redirect()->route('sales.index')
                         ->with('success', 'Sale updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale): RedirectResponse
    {
        $sale->delete();

        return redirect()->route('sales.index')
                         ->with('success', 'Sale deleted successfully.');
    }
}
