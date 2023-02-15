<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ResponseFormatter;
    
    public function all(Request $request)
    {
        return $this->responseFormatterWithMeta($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], User::select('id', 'name', 'email', 'email_verified_at', 'password', 'profile_photo_path', 'created_at')->orderBy('created_at', 'DESC')->cursorPaginate($request->input('limit', 15)));
    }

    public function login(Request $request)
    {
        try{
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $credentials = request(['email', 'password']);
            if(!Auth::attempt($credentials))
                return $this->errorResponseFormatter('Login Failed', 500);

            $user = User::where('email', $request->email)->first();
            if(!Hash::check($request->password, $user->password, []))
                throw new Exception('Invalid Credentials');   
            
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], [
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user]);
        }catch (Exception $error){
            return $this->errorResponseFormatter($error->getMessage(), 500);
        }
    }

    public function Register(Request $request)
    {
        try{
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], [
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user]);

        }catch (Exception $error){
            return $this->errorResponseFormatter($error->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();
        return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $token);
    }
}
