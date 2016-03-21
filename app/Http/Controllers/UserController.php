<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Hash;
use App\Http\Requests;
use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.user.account');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(\Auth::user()->id == $id){
            $user = User::where('id', $id)->first();

            if($request->fieldToBeUpdated == 'name'){
                $this->validate($request, [
                    'name' => 'required|string|max:64'
                ]);

                if($user->name != $request->name){
                    //Redirect if name is unchanged
                    $user->name = $request->name;
                    $user->save();
                    \Session::flash('nameChangeSuccess', 'Your Name was changed successfully!');
                }
            }
            elseif($request->fieldToBeUpdated == 'password'){
               $this->validate($request, [
                    'oldPassword' => 'required|max:64',
                    'password' => 'required|confirmed|max:64',
                    'password_confirmation' => 'required|max:64',
                ]);

                if(Hash::check($request->oldPassword, $user->password)){
                    $user->password = Hash::make($request->password);
                    $user->save();
                    \Session::flash('passwordChangeSuccess', 'Your Password was changed successfully!');
                }
                else{
                    \Session::flash('passwordChangeFailure', "The 'Current Password' you provided was incorrect!");
                }
            }
        }
        return redirect('/user/account');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
