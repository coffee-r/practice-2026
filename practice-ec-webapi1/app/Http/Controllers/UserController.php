<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Packages\User\Usecase\UserGetAction;
use App\Packages\User\Usecase\UserRegisterAction;
use App\Packages\User\Usecase\UserRegisterCommand;
use App\Packages\User\Usecase\UserUpdateAction;
use App\Packages\User\Usecase\UserUpdateCommand;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, UserRegisterAction $userRegisterAction)
    {
        $userRegisterCommand = new UserRegisterCommand(
            $request->input('name'),
            $request->input('nameFurigana'),
            $request->input('gender'),
            $request->input('birthdayYear'),
            $request->input('birthdayMonth'),
            $request->input('email'),
            $request->input('password'),
            $request->input('postalCode'),
            $request->input('addressPrefectures'),
            $request->input('addressMunicipalities'),
            $request->input('addressOthers'),
            $request->input('tel'),
            $request->input('emailMagazineSubscription'),
        );
        
        $userData = $userRegisterAction($userRegisterCommand);
        return new UserResource($userData);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, UserGetAction $userGetAction)
    {
        $userData = $userGetAction($id);
        return new UserResource($userData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, UserUpdateAction $userUpdateAction)
    {
        $UserUpdateCommand = new UserUpdateCommand(
            $id,
            $request->input('name'),
            $request->input('nameFurigana'),
            $request->input('gender'),
            $request->input('birthdayYear'),
            $request->input('birthdayMonth'),
            $request->input('email'),
            $request->input('password'),
            $request->input('postalCode'),
            $request->input('addressPrefectures'),
            $request->input('addressMunicipalities'),
            $request->input('addressOthers'),
            $request->input('tel'),
            $request->input('emailMagazineSubscription'),
        );

        $userData = $userUpdateAction($UserUpdateCommand);
        return new UserResource($userData);
    }
}
