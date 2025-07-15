<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){

        $users = User::latest()->paginate(10);
        return view('admin.user', compact('users'));
    }

    public function store(Request $request)
    {
        $users = new User;
        $users->id = $request->id;
        $users->name = $request->name;
        $users->email = $request->email;
        $users->password = $request->password;
        $users->usertype = $request->usertype;
        $users->save();
        return redirect()->route('users');
    }

    public function edit($id)
    {
        $users = user::find($id);
        return view('/admin.edituser', compact('users'));
    }

   public function update(Request $request)
    {
    $request->validate([
        'name' => 'required|max:255',
        'email' => 'required|email|unique:users,email,' . $request->id,
    ]);

    $user = User::find($request->id);
    
    if (!$user) {
        return redirect()->back()->withErrors(['User not found.']);
    }

    $user->name = $request->name;
    $user->email = $request->email;
    $user->usertype = $request->usertype;
    $user->save();

    return redirect()->route('users')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $id = User::find($id);
        $id->delete();
        return redirect()->route('users');
        
    }
}

