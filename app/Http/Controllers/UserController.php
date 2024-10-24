<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot delete admin user');
        }
    
        $user->delete();
        
        return redirect()->back()->with('success', 'User deleted successfully');
    }
    
    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => $user
        ]);
    }
    public function update(User $user) 
{
    // Validate the request data
    $validated = request()->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $user->id,
        // Add other validation rules as needed
    ]);
    
    $user->update($validated);
    
    return redirect()->back()->with('success', 'User updated successfully');
}
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return response()->json($users);
    }
}


