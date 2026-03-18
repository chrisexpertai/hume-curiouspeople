<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('user_access')) {
            return abort(401);
        }
        $users = User::all();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('user_create')) {
            return abort(401);
        }
        $roles = Role::get()->pluck('title', 'id');

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! Gate::allows('user_create')) {
            return abort(401);
        }
        $user = User::create($request->only('name','email') + ['password' => bcrypt($request->password)]);
        $user->role()->sync(array_filter((array)$request->input('role')));

        return redirect()->route('admin.users.index');
    }


    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // Profile
    public function showProfile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }


    public function updateProfile(Request $request)
{
    $this->validate($request, [
        'name' => 'required|string',
        'email' => ['required', 'email', Rule::unique('users')->ignore(Auth::id())],
        'password' => 'nullable|min:8|confirmed',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $user = Auth::user();

    // Update profile image if a new one is provided
    if ($request->hasFile('profile_image')) {
        // Delete the previous profile image, if exists
        if ($user->profile_image) {
            Storage::delete($user->profile_image);
        }

        $profileImage = $request->file('profile_image');
        $path = $profileImage->store('public/profile_images');
        $user->update(['profile_image' => $path]);
    }

    $userData = [
        'name' => $request->input('name'),
        'email' => $request->input('email'),
    ];

    // Update password only if a new password is provided
    if ($request->filled('password')) {
        $userData['password'] = Hash::make($request->input('password'));
    }

    $user->update($userData);

    return redirect()->route('user.profile')->with('success', 'Profile updated successfully.');
}
public function dashboard()
{
    return view('front.dashboard.index');
}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if (! Gate::allows('user_edit')) {
            return abort(401);
        }
        $roles = Role::get()->pluck('title', 'id');

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,User $user)
    {
        if (! Gate::allows('user_edit')) {
            return abort(401);
        }
        $user->update($request->only('name','email') + ['password' => bcrypt($request->password)]);
        $user->role()->sync(array_filter((array)$request->input('role')));

        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (! Gate::allows('user_delete')) {
            return abort(401);
        }

        $user->delete();

        return redirect()->route('admin.users.index');
    }
}
