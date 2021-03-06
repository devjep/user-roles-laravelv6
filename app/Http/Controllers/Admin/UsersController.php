<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use Gate;
use App\Http\Requests\UserAddRequest;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function _construct(){
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.index')->withUsers(User::all());
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
       if(Gate::denies('edit-users'))
       {
           return redirect()->route('admin.users.index');
       }
        $roles = Role::All();
        return view('user.edit')->with([
            "user" => $user,
            "roles" => $roles
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(request $request, User $user)
    {
        $user->roles()->sync($request->roles);
        $user->update([
            'name' => $request->name,
            'email' => $request->email

        ]);
        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(Gate::denies('delete-users'))
        {
            return redirect()->route('admin.users.index');
        }
        $user->roles()->detach();
        $user->delete();
        return redirect()->route('admin.users.index');
    }
}
