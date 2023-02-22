<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Rules\Password;

class AuthController extends Controller
{
    use ResponseFormatter;

    public function index(Request $request)
    {
        return $this->responseFormatterWithMeta($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], User::orderBy('created_at', 'desc')->cursorPaginate($request->input('per_page', 15)));
    }

    public function register(Request $request){
        try{
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'role' => 'required'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role
            ]);

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], [
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user]);

        }catch (Exception $error){
            return $this->errorResponseFormatter($this->httpCode['StatusInternalServerError'], $error->getMessage());
        }
    }

    public function login(Request $request){
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

            if(Auth::user()->role === 1){
                $user->role = 'admin';
            }else{
                $user->role = 'customer';
            }
        
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], [
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user]);
        }catch (Exception $error){
            return $this->errorResponseFormatter($this->httpCode['StatusInternalServerError'], $error->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();
        return $this->responseFormatter($this->httpCode['StatusOK'], $this->httpMessage['StatusOK'], $token);
    }

}
