<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    protected $_tokenName = 'auth-api';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $objRequest)
    {
        $objRequest->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required',
        ]);

        $objUser = User::where('email', $objRequest->email)->first();

        if (! $objUser || ! Hash::check($objRequest->password, $objUser->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $objToken = $objUser->createToken( $this->_tokenName, [$objRequest->role] );

        // abilities
        $arrResponse = [
            'token' => $objToken->plainTextToken,
            'expires' => $objUser->tokenExpires( $objToken->accessToken ),
        ];

        return $arrResponse;
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
     * @param  \Illuminate\Http\Request  $objRequest
     * @return \Illuminate\Http\Response
     */
    public function store(Request $objRequest)
    {
        $arrValidate = [
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|min:8|string|confirmed',
        ];
        $objValidated = (object) $objRequest->validate( $arrValidate );

        $objUser = User::create( [
            'name' => $objValidated->name,
            'email' => $objValidated->email,
            'password' => bcrypt( $objValidated->password ),
        ] );

        $strToken = $objUser->createToken( $this->_tokenName, [ 'user:lookup' ] )->plainTextToken;

        $arrResponse = [
           'user' => $objUser,
            'token' => $strToken,
            'message' => 'User created succesfully!',
        ];
        return response( $arrResponse, 201 );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $objRequest,User $user)
    {
        return  $objRequest->user();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $objRequest
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $objRequest, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $objRequest)
    {
        $objRequest->user()->currentAccessToken()->delete();
        return [ 'message' => 'User has been logged out!' ];
    }
}
