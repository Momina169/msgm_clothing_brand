<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GuestController extends Controller
{
    public function index()
    {
        $guests = Guest::latest()->paginate(10);
        return view('guests.index', compact('guests'));
    }

    // public function show(Guest $guest): View
    // {
    //     $guest->load(['carts.items.product', 'orders.items.product']);
    //     return view('guests.show', compact('guest'));
    // }


    public function destroy(Guest $guest): RedirectResponse
    {
        $guest->delete();

        return redirect()->route('guestUser')
                         ->with('success', 'Guest deleted successfully.');
    }
}
