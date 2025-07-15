<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $addresses = Address::with('user')->latest()->paginate(10);
        $users = User::all(); // For the create modal dropdown
        return view('addresses.index', compact('addresses', 'users'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string|in:shipping,billing,home,work,other', 
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'is_default' => 'boolean',
        ]);

        
            // If this address is set as default, unset default for other addresses of this user
            if (isset($validatedData['is_default']) && $validatedData['is_default']) {
                Address::where('user_id', $validatedData['user_id'])
                       ->update(['is_default' => false]);
            }

            Address::create($validatedData);

            return redirect()->route('addresses.index')
                             ->with('success', 'Address created successfully.');
      
    }

   
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Address $address): View
    {
        $users = User::all(); // For the dropdown
        return view('addresses.edit', compact('address', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Address $address): RedirectResponse
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string|in:shipping,billing,home,work,other',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'is_default' => 'boolean',
        ]);

        
            // If this address is set as default, unset default for other addresses of this user
            if (isset($validatedData['is_default']) && $validatedData['is_default']) {
                Address::where('user_id', $validatedData['user_id'])
                       ->where('id', '!=', $address->id) 
                       ->update(['is_default' => false]);
            }

            $address->update($validatedData);

            return redirect()->route('addresses.index')
                             ->with('success', 'Address updated successfully.');
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address): RedirectResponse
    {
        
            $address->delete();
            return redirect()->route('addresses.index')
                             ->with('success', 'Address deleted successfully.');
        
    }
}
