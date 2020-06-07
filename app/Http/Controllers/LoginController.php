<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
class LoginController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
        
        $userexist = User::where('email', $request->email)->exists();
        if(!$userexist){
            return response('Cette
            adresse e-mail est inconnue', 422);
        }
    
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
    
        $token = $user->createToken('token-login');
        return response()->json(['token' => $token->plainTextToken]);
    }

    public function reset(Request $request)
    {
        Artisan::call("migrate:refresh", ["--force" => true]);
       
        return response()->json(['message' => 'Reset All']);
        
    }
}
