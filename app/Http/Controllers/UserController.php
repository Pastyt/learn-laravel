<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function create()
    {
        return view('users.register');
    }

    public function store(Request $request)
    {
        $form_fields = $request->validate([
            'name' => ['required','min:3'],
            'email' => ['required','email',Rule::unique('users','email')],
            'password' => ['required','confirmed', 'min:6'],
        ]);

        $form_fields['password'] = bcrypt($form_fields['password']);

        $user = User::create($form_fields);

        auth()->login($user);

        return redirect('/')->with('message','User created and logged in!');
    }
}
