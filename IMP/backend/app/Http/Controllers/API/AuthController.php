<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ResponseFormatter;

    public function register(Request $request){
        try{
            $request->validate([
                "name" => "required|string|max:255",
                "email" => "required|email|unique:users",
                "password" => "required|string|min:8",
            ]);

            $user = new User([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password),
            ]);

            $user->id = Str::uuid();
            $user->save();

            $user = User::where("email", $request->email)->first();
            $tokenResult = $user->createToken("authToken")->plainTextToken;
            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], [
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user]);
            
        }catch(Exception $e){
            return $this->responseFormatter($this->httpCode['StatusInternalServerError'], $this->httpMessage['StatusInternalServerError'], $e->getMessage());
        }
    }

    public function login(Request $request)
    {
        try{
            $request->validate([
                "email" => "required|email",
                "password" => "required",
            ]);

            $credentials = request(["email", "password"]);
            if(!Auth::attempt($credentials))
                return $this->responseFormatter($this->httpCode['StatusUnauthorized'], $this->httpMessage['StatusUnauthorized'], null);
            
            $user = User::where("email", $request->email)->first();
            if(!$user || !Hash::check($request->password, $user->password))
                throw new Exception("Invalid credentials");

            $tokenResult = $user->createToken("authToken")->plainTextToken;
            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], [
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user]);

        }catch (Exception $e){
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        try{
            $request->user()->currentAccessToken()->delete();
            return response()->json(["message" => "Logout successful"], 200);
        }catch (Exception $e){
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }
}
