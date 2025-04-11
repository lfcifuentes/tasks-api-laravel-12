<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers
 *
 * Controller for handling authentication related operations like login, register,
 * logout and retrieving the authenticated user.
 */
class AuthController extends Controller
{
    /**
     * Authenticate a user and generate an access token
     *
     * @param LoginRequest $request The validated login request
     * @return JsonResponse Token on success, error message on failure
     *
     * @response 200 {"token": "1|your-token-here"}
     * @response 401 {"message": "Invalid credentials"}
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (auth()->attempt($request->only('email', 'password'))) {
            $user = auth()->user();
            $token = $user->createToken('Personal Access Token')->plainTextToken;

            return response()->json(['token' => $token], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    /**
     * Register a new user in the system
     *
     * @param RegisterRequest $request The validated registration request
     * @return JsonResponse New user's access token
     *
     * @response 201 {"token": "1|your-token-here"}
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('Personal Access Token')->plainTextToken;

        return response()->json(['token' => $token], 201);
    }

    /**
     * Logout the authenticated user by revoking their token
     *
     * @param Request $request The request instance
     * @return JsonResponse Success message
     *
     * @response 200 {"message": "Logged out successfully"}
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    /**
     * Get the authenticated user's information
     *
     * @param Request $request The request instance
     * @return JsonResponse User information
     *
     * @response 200 {"id": 1, "name": "John Doe", "email": "john@example.com"}
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json($request->user(), 200);
    }
}
