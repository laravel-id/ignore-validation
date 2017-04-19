<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function form()
    {
        return view('profile.form');
    }

    public function update()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|max:30',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore(Auth::id(), 'id')
            ]
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('profile.form')
                ->with('errors', $validator->errors());
        }

        $user = Auth::user();

        $user->name = request('name');
        $user->email = request('email');
        $user->save();

        return redirect()
            ->route('profile.form');
    }
}
