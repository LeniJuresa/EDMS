<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        unset($fields['role']);
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
    public function registerFromAdmin()
    {
        // ===== START: Validate input =====
        $fields = request()->validate([
            'name'     => ['required', \Illuminate\Validation\Rule::unique('users', 'name')],
            'email'    => ['required', 'email', \Illuminate\Validation\Rule::unique('users', 'email')],
            'password' => ['required', 'min:8'],
            'role'     => ['required', \Illuminate\Validation\Rule::in(['admin','dispatcher'])],
        ]);
        // ===== END =====

        // ===== START: Keep plain password for download =====
        $plainPassword = $fields['password'];
        $fields['password'] = bcrypt($plainPassword);
        // ===== END =====

        // ===== START: Set roles =====
        $fields['is_admin'] = $fields['role'] === 'admin' ? 1 : 0;
        $fields['is_dispatcher'] = $fields['role'] === 'dispatcher' ? 1 : 0;
        // ===== END =====

        // ===== START: Create user (model will auto-generate id_number)
        $user = User::create($fields);
        // ===== END =====

        // Flash success and temporary data for download
        request()->session()->flash('success', 'Staff account created successfully.');
        request()->session()->flash('new_user_id', $user->id_number);
        request()->session()->flash('new_user_name', $user->name);
        request()->session()->flash('plain_password', $plainPassword);

        return redirect('/admin');
    }

    /**
     * Download the staff account file for a given id_number
     */
    public function downloadStaffFile($id)
    {
        $user = User::where('id_number', $id)->firstOrFail();

        $plainPassword = request()->session()->get('plain_password', '(not available)');

        $text = 
"--------------------------------------------------
        STAFF ACCOUNT CREATION
--------------------------------------------------

Name:        {$user->name}
Email:       {$user->email}
Password:    {$plainPassword}
ID Number:   {$user->id_number}

Role:        " . ($user->is_admin ? 'admin' : 'dispatcher') . "\n\nCreated At:  {$user->created_at}

--------------------------------------------------
DISCLAIMER: This document contains sensitive information.
Please destroy it immediately after use. Do not share
this file with anyone. Keep it safe and secure.
--------------------------------------------------
";

        return response($text, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => "attachment; filename=staff_account_{$user->id_number}.txt",
        ]);
    }



}