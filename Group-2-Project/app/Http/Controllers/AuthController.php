<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\JwtHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
//use Illuminate\Validation\ValidationException;

// Role number -> name map
const ROLE_MAP = [
    1 => "ADMIN",
    2 => "MANAGER",
    3 => "VOLUNTEER",
    4 => "SUPER",
    99 => "UNKNOWN",
];

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $request->validate([
            "first_name" => "required",
            "last_name" => "required",
            "email" => "required|email|unique:cw_user,email",
            "password" => "required|min:6",
            "mobile" => "nullable",
        ]);

        $user = User::create([
            "FirstName" => $request->first_name,
            "LastName" => $request->last_name,
            "email" => $request->email,
            "PassWord" => Hash::make($request->password),
            "mobile" => $request->mobile,
            "userID" => $request->email,
            "userEnabled" => 1,
            "userTypeNr" => 3, // VOLUNTEER
        ]);

        return response()->json(
            [
                "message" => "User registered",
                "user" => $user,
            ],
            201,
        );
    }

    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        $user = User::where("email", $request->email)->first();

        try {
            $passwordValid =
                $user && Hash::check($request->password, $user->PassWord);
        } catch (\Throwable $e) {
            // For legacy SHA-256 stored hashes, verify old password.
            // If it matches, try upgrading to bcrypt, but never fail login
            // if DB schema cannot store the longer bcrypt hash yet.
            $sha256 = base64_encode(hash("sha256", $request->password, true));
            $passwordValid = $user && hash_equals($sha256, $user->PassWord);
            if ($passwordValid) {
                try {
                    $user->PassWord = Hash::make($request->password);
                    $user->save();
                } catch (\Throwable $upgradeError) {
                    Log::warning('Password hash upgrade skipped during login: ' . $upgradeError->getMessage());
                }
            }
        }

        if (!$user || !$passwordValid) {
            return response()->json(["message" => "Invalid credentials"], 401);
        }

        $roleName = ROLE_MAP[$user->userTypeNr] ?? "UNKNOWN";
        $rememberMe = $request->boolean("remember_me");
        // 7 days when "remember me" is checked, otherwise 2 hours so the
        // token becomes invalid even if the session cookie somehow lingers.
        $ttl = $rememberMe ? 7 * 24 * 3600 : 2 * 3600;

        $token = JwtHelper::encode([
            "sub" => $user->UserNr,
            "email" => $user->email,
            "role" => $user->userTypeNr,
            "roleName" => $roleName,
            "rememberMe" => $rememberMe,
            "iat" => time(),
            "exp" => time() + $ttl,
        ]);

        return response()->json([
            "message" => "Login successful",
            "token" => $token,
            "user" => $user,
        ]);
    }

    // RESET PASSWORD (simple version)
    public function reset(Request $request)
    {
        $request->validate([
            "email" => "required|email|exists:cw_user,email",
            "password" => "required|min:6",
        ]);

        $user = User::where("email", $request->email)->first();
        $user->PassWord = Hash::make($request->password);
        $user->save();

        return response()->json([
            "message" => "Password reset successful",
        ]);
    }
}
