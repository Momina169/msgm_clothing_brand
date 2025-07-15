<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('coupons.index', compact('coupons'));
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code',
            'type' => 'required|string|in:percentage,fixed', 
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'max_uses_per_user' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
        ]);

        
            Coupon::create($validatedData);
            return redirect()->route('coupons.index')
                             ->with('success', 'Coupon created successfully.');
        
    }

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon): View
    {
        return view('coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code,' . $coupon->id,
            'type' => 'required|string|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'max_uses_per_user' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
        ]);

    
            $coupon->update($validatedData);
            return redirect()->route('coupons.index')
                             ->with('success', 'Coupon updated successfully.');
       
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon): RedirectResponse
    {
        
            $coupon->delete();
            return redirect()->route('coupons.index')
                             ->with('success', 'Coupon deleted successfully.');
        
    }
}
