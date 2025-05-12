<?php

// app/Http/Controllers/Auth/RegisterController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:6|confirmed',
            'agree_terms' => 'accepted',
        ]);
    
        // Manually generate a new ID since $incrementing = false
        $newId = DB::table('user')->max('id') + 1;
    
        $user = User::create([
            'id' => $newId,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'roles' => json_decode('["ROLE_USER"]'),
            'is_paid_user' => false,
            'organization_id' => 1,
        ]);
    
        Auth::login($user);
        return redirect('/dashboard');
    }

}

