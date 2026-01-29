<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dom\Comment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function login()
    {
        $incomingFields = request()->validate([
            'id_number' => 'required',
            'password' => 'required',
        ]);

        if (auth()->attempt([
            'id_number' => $incomingFields['id_number'],
            'password' => $incomingFields['password']
        ])) {
            request()->session()->regenerate();

            $user = auth()->user();
            if ($user->is_admin) return redirect('/admin');
            if ($user->is_dispatcher) return redirect('/dispatcher');
            return redirect('/');
            }
        return back()->withErrors(['id_number' => 'Invalid ID Number or Password'])->onlyInput('id_number');
    
    }

    public function register()
    {
        $incomingFields = request()->validate([
            'name'=> ['required', Rule::unique('users', 'name')],
            'email'=> ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8',],
        ]);

        $incomingFields['password'] = bcrypt($incomingFields['password']);
        $user = User::create($incomingFields);

        auth()->login($user);

        return redirect('/admin');
    }

    public function logout()
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    }


}
