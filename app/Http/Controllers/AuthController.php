<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['User_no' => $request->input("User_no"), 'password' => $request->input("user_pwd")])) {
            /** @var \App\Models\MyUserModel $user **/
            $user = Auth::user();

            $token = $user->createToken("user")->accessToken;

            $userWithoutToken = new UserResource(User::find($user['User_no']));
            $userWithToken = $userWithoutToken->additional(["data" => ["token" => $token]]);

            return  $userWithToken;
        }
        return response(["error", "Invalid credentials!"], Response::HTTP_UNAUTHORIZED);
    }
}
