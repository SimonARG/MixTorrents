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
    /**
     * Display a listing of the resource.
     */
    public function index() {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        session(['link' => url()->previous()]);
        
        return view('register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $formFields = $request->validate([
            'name' => ['required', 'min:6', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()]
        ]);

        $formFields['password'] = bcrypt($formFields['password']);
        $formFields['pic'] = "default.png";

        $user = User::create($formFields);

        $user->roles()->attach(5);

        auth()->login($user);

        return redirect(session('link'))->with('message', 'Registration succesful, you are now logged in!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user) {
        $date = new DateTime($user->created_at);
        $strdate = $date->format('Y/m/d H:i');

        return view('user', [
            'user' => $user,
            'strdate' => $strdate
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        $user = User::find($id);

        if (Gate::allows('messWith-user', $user)) {
            return view('users.edit', [
                'id' => $id
            ]);
        } else if (! Auth::check()) {
            return back()->with('message', 'You need to be logged in to edit your profile');
        } else if (! Gate::allows('messWith-user', $user)) {
            return back()->with('message', 'You can only edit your own profile');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
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

    public function login() {
        session(['link' => url()->previous()]);
        
        return view('login');
    }

    public function authenticate(Request $request) {
        $formFields = $request->validate([
            'name' => ['required'],
            'password' => ['required']
        ]);

        if (Auth::attempt($formFields)) {
            $request->session()->regenerate();

            return redirect(session('link'))->with('message', 'You are now logged in');
        }

        return back()->withErrors(['name' => 'Invalid credentials'])->onlyInput('name');
    }

    public function logout(Request $request) {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()->with('message', 'You are logged out!');
    }

    public function profile() {
        if (Auth::check()) {
            $date = new DateTime(auth()->user()->created_at);
            $strdate = $date->format('Y/m/d H:i');
            return view('profile', [
                'strdate' => $strdate
            ]);
        } else {
            return back()->with('message', 'You need to be logged in to view your profile');
        }
    }

    public function uploads(Request $request) {
        $user = User::where($request->field, $request->name)->first();
 
        $uploads = $user->uploads()->paginate(20);

        $upStrdates = [];
        foreach ($uploads as $key => $upload) {
            $upDate = new DateTime($upload->created_at);
            $upStrdate = $upDate->format('Y/m/d H:i');
            $upStrdates[$key] = [$upStrdate];
        }
        
        return view('users.uploads', [
            'uploads' => $uploads,
            'upStrdates' => $upStrdates
        ]);
    }
}
