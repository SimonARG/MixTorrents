<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function create()
    {
        session(['link' => url()->previous()]);
        return view('register');
    }

    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => ['required', 'min:6', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()]
        ]);

        $formFields['password'] = bcrypt($formFields['password']);
        $formFields['pic'] = "default.png";

        $user = User::create($formFields);

        $token = $user->createToken('AuthToken');

        $tokenText = $token->plainTextToken;

        $user->roles()->attach(5);

        auth()->login($user);

        return redirect(session('link'))->with('message', 'Registration succesful, you are now logged in! Your token is: ' . $tokenText);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $date = new DateTime($user->created_at);
        $strdate = $date->format('Y/m/d H:i');

        return view('user', [
            'user' => $user,
            'strdate' => $strdate
        ]);
    }

    public function edit(string $id)
    {
        $user = User::find($id);

        if (Gate::allows('messWith-user', $user)) {
            return view('users.edit', [
                'id' => $id
            ]);
        } elseif (! Auth::check()) {
            return back()->with('message', 'You need to be logged in to edit your profile');
        } elseif (! Gate::allows('messWith-user', $user)) {
            return back()->with('message', 'You can only edit your own profile');
        }
    }

    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (! Gate::allows('messWith-user', $user)) {
            abort(403);
        }

        $dbFields = [];

        if ($request['name'] == $user->name) {
            $request['name'] = null;
        }

        if ($request['email'] == $user->email) {
            $request['email'] = null;
        }

        $request->validate([
            'name' => ['min:6', 'unique:users', 'nullable'],
            'email' => ['email', 'unique:users', 'nullable'],
            'about'=> ['string', 'max:10000', 'nullable'],
            'avatar' => [File::types(['jpeg', 'jpg', 'png', 'webp', 'gif', 'avif']), 'nullable'],
            'new-password' => ['confirmed', Password::defaults(), 'nullable'],
        ]);

        $login = [];
        $login['name'] = $user->name;
        $login['password'] = $request['password'];

        if (Auth::attempt($login)) {
            if ($request['new-password']) {
                $dbFields['password'] = bcrypt($request['new-password']);
            }
    
            if ($request['avatar']) {
                // Store the file
                $file = $request->avatar->store('avatars', 'public');
                
                $filenames = explode('/', $file);
    
                $filename = $filenames[1];
    
                $dbFields['pic'] = $filename;
            } else {
                $dbFields['pic'] = $user->pic;
            }
    
            $dbFields['name'] = $request['name'];
            $dbFields['email'] = $request['email'];
            $dbFields['about'] = $request['about'];
    
            $dbFields = array_filter($dbFields);
    
            $user->update($dbFields);
    
            return to_route('users.profile')->with('message', 'Profile edited!');
        } else {
            return back()->with('message', 'Incorrect password');
        }

    }

    public function destroy(string $id)
    {
        $user =  User::find($id);

        if (! Gate::allows('messWith-user', $user)) {
            abort(403);
        }

        auth()->logout();

        session()->invalidate();
        session()->regenerateToken();

        User::destroy($id);
        return to_route('torrents.index')->with('message', 'Your profile has been deleted');
    }
}
